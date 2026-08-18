<?php

class Autopagejson_IndexController extends Omeka_Controller_AbstractActionController
{
    public function indexAction()
    {
        $db = get_db();

        // Ambil daftar exhibit aktif untuk keperluan dropdown di view
        try {
            $this->view->exhibits = $db->getTable('Exhibit')->findAll();
        } catch (Exception $e) {
            $this->view->exhibits = array();
        }

        // Jalankan proses ketika form disubmit
        if ($this->getRequest()->isPost()) {
            if (!empty($_FILES['json_file']['tmp_name'])) {

                $targetType = $this->getParam('target_type'); // Menentukan target 'exhibit' atau 'simple_page'
                $exhibitId = $this->getParam('exhibit_id');
                $kategoriHalaman = trim($this->getParam('kategori_halaman'));
                $autoNavbar = $this->getParam('auto_navbar');

                $jsonString = file_get_contents($_FILES['json_file']['tmp_name']);
                $dataArtefak = json_decode($jsonString, true);

                if (!$dataArtefak) {
                    $this->_helper->flashMessenger(__('Invalid JSON format!'), 'error');
                    return;
                }

                // Ambil ID Element Dublin Core Source untuk wadah simpan tag iframe / media link
                $elementSource = $db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Source');
                $elementSourceId = $elementSource ? $elementSource->id : null;

                $itemRecords = array(); // Menyimpan objek item asli untuk referensi URL nanti

                // Proses perulangan data dengan pemetaan key JSON universal (Agnostik Data)
                foreach ($dataArtefak as $artefak) {
                    
                    // 1. Pemetaan Fleksibel untuk Judul (Mencegah Unnamed Item)
                    $namaNisan = 'Untitled Item';
                    $possibleTitleKeys = array('title', 'Title', 'nama', 'nama_nisan', 'Nama', 'NAME');
                    foreach ($possibleTitleKeys as $tk) {
                        if (isset($artefak[$tk]) && trim($artefak[$tk]) !== '') {
                            $namaNisan = trim($artefak[$tk]);
                            break;
                        }
                    }

                    // 2. Pemetaan Fleksibel untuk Deskripsi / Keterangan
                    $deskripsi = 'No Description Available';
                    $possibleDescKeys = array('description', 'Description', 'deskripsi', 'Deskripsi', 'desc');
                    foreach ($possibleDescKeys as $dk) {
                        if (isset($artefak[$dk]) && trim($artefak[$dk]) !== '') {
                            $deskripsi = trim($artefak[$dk]);
                            break;
                        }
                    }

                    // 3. Pemetaan Fleksibel untuk Media / Iframe / Source Link
                    $embedCode = '';
                    $possibleEmbedKeys = array('embed_code', 'Embed_Code', 'source', 'Source', 'embed');
                    foreach ($possibleEmbedKeys as $ek) {
                        if (isset($artefak[$ek]) && trim($artefak[$ek]) !== '') {
                            $embedCode = trim($artefak[$ek]);
                            break;
                        }
                    }

                    $itemMetadata = array(
                        'Dublin Core' => array(
                            'Title' => array(array('text' => $namaNisan, 'html' => false)),
                            'Description' => array(array('text' => $deskripsi, 'html' => false))
                        )
                    );

                    try {
                        $item = insert_item($itemMetadata);
                        if ($item) {
                            // Ambil ID Item baru secara langsung
                            $itemId = $item->id;

                            // Simpan data lengkap item ke dalam array untuk proses looping layout
                            $itemRecords[] = array(
                                'id' => $itemId,
                                'title' => $namaNisan,
                                'description' => $deskripsi,
                                'embed' => $embedCode
                            );

                            if (!empty($embedCode) && $elementSourceId) {
                                $elementText = new ElementText;
                                $elementText->record_id = $itemId;
                                $elementText->record_type = 'Item';
                                $elementText->element_id = $elementSourceId;
                                $elementText->text = $embedCode;
                                $elementText->html = true;
                                $elementText->save();
                            }
                        }
                    } catch (Exception $e) {
                        $this->_helper->flashMessenger(__('Failed to create item: ' . $e->getMessage()), 'error');
                    }
                }

                if (empty($itemRecords)) {
                    $this->_helper->flashMessenger(__('No items successfully processed.'), 'error');
                    return;
                }

                // Pilihan Target A: Memasukkan data ke halaman Exhibit standar Omeka
                if ($targetType === 'exhibit' && !empty($exhibitId)) {
                    
                    $pageTitle = !empty($kategoriHalaman) ? $kategoriHalaman : 'New Collection';
                    $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $pageTitle));
                    $slug = trim($slug, '-');

                    $existingPage = null;
                    try {
                        $allPages = $db->getTable('ExhibitPage')->findAll();
                        foreach ($allPages as $page) {
                            if ($page->slug == $slug && $page->exhibit_id == $exhibitId) {
                                $existingPage = $page;
                                break;
                            }
                        }
                    } catch (Exception $e) {
                        $existingPage = null;
                    }

                    if ($existingPage) {
                        $exhibitPage = $existingPage;
                    } else {
                        $exhibitPage = new ExhibitPage();
                        $exhibitPage->exhibit_id = $exhibitId;
                        $exhibitPage->title = $pageTitle;
                        $exhibitPage->slug = $slug;

                        $lastOrder = 0;
                        try {
                            $allPages = $db->getTable('ExhibitPage')->findAll();
                            foreach ($allPages as $page) {
                                if ($page->exhibit_id == $exhibitId && $page->order > $lastOrder) {
                                    $lastOrder = $page->order;
                                }
                            }
                        } catch (Exception $e) {
                            $lastOrder = 0;
                        }

                        $exhibitPage->order = $lastOrder + 1;
                        try {
                            $exhibitPage->save();
                        } catch (Exception $e) {
                            $this->_helper->flashMessenger(__('Failed to create exhibit page: ' . $e->getMessage()), 'error');
                            return;
                        }
                    }

                    // Mengaitkan struktur Item ke dalam blok halaman Exhibit
                    foreach ($itemRecords as $index => $itemData) {
                        try {
                            $block = new ExhibitPageBlock();
                            $block->page_id = $exhibitPage->id;
                            $block->layout = 'file-text';
                            $block->order = $index + 1;
                            $block->save();

                            $attachment = new ExhibitBlockAttachment();
                            $attachment->block_id = $block->id;
                            $attachment->item_id = $itemData['id'];
                            $attachment->order = 1;
                            $attachment->caption = $itemData['title'];
                            $attachment->save();
                        } catch (Exception $e) {
                            $this->_helper->flashMessenger(__('Failed to attach item to block: ' . $e->getMessage()), 'error');
                        }
                    }

                    $parentExhibit = $db->getTable('Exhibit')->find($exhibitId);
                    $navLabel = $pageTitle;
                    $navUri = 'exhibits/show/' . $parentExhibit->slug . '/' . $exhibitPage->slug;
                    $successMsg = __('Success! Items successfully added to Exhibit Page.');

                // Pilihan Target B: Membuat struktur HTML kustom Grid Cards ke Simple Pages (Lengkap Animasi Gabungan)
                } else {
                    $pageTitle = !empty($kategoriHalaman) ? $kategoriHalaman : 'New Gallery Page';
                    $pageSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $pageTitle)) . '-' . substr(time(), -3);

                    // Menyuntikkan Google Fonts Poppins ke dalam pembungkus halaman halaman depan publik
                    $htmlContent = '<link rel="preconnect" href="https://fonts.googleapis.com">';
                    $htmlContent .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
                    $htmlContent .= '<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">';
                    
                    $htmlContent .= '<div class="custom-tour-wrapper" style="padding: 10px 0; font-family: \'Poppins\', sans-serif;">';
                    $htmlContent .= '<h2 style="font-family: \'Poppins\', sans-serif; font-weight: 600; border-bottom: 2px solid #dfa139; padding-bottom: 8px; margin-bottom: 25px; color:#222;">' . html_escape($pageTitle) . '</h2>';
                    $htmlContent .= '<div class="tour-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">';

                    foreach ($itemRecords as $itemData) {
                        // Mengunci URL agar mutlak mengarah ke halaman publik depan situs
                        $itemUrl = url('items/show/' . $itemData['id']);

                        // Animasi Gabungan: Mengangkat kartu (translateY), mengubah border biru (#3b82f6), dan memberi bayangan pendaran biru halus.
                        $htmlContent .= '<a href="' . $itemUrl . '" class="tour-card" style="font-family: \'Poppins\', sans-serif; border: 2px solid #e2e8f0; border-radius: 12px; overflow: hidden; background:#fff; display:flex; flex-direction:column; text-decoration:none; color:inherit; transition: transform 0.25s ease-in-out, border-color 0.25s ease-in-out, box-shadow 0.25s ease-in-out;" onmouseover="this.style.transform=\'translateY(-6px)\'; this.style.borderColor=\'#3b82f6\'; this.style.boxShadow=\'0 10px 20px rgba(59, 130, 246, 0.15)\';" onmouseout="this.style.transform=\'none\'; this.style.borderColor=\'#e2e8f0\'; this.style.boxShadow=\'none\';">';
                        
                        if (!empty($itemData['embed'])) {
                            // Pointer-events:none dipasang agar klik pada area iframe tidak tertahan oleh media interaktifnya
                            $htmlContent .= '<div class="media-box" style="position:relative; width:100%; height:220px; background:#000; pointer-events: none;">' . $itemData['embed'] . '</div>';
                        }
                        
                        $htmlContent .= '<div style="padding: 15px; flex-grow:1; display:flex; flex-direction:column;">';
                        $htmlContent .= '<h4 style="font-family: \'Poppins\', sans-serif; font-weight: 600; margin: 0 0 10px 0; color: #2d3748; font-size:16px;">' . html_escape($itemData['title']) . '</h4>';
                        $htmlContent .= '<p style="font-family: \'Poppins\', sans-serif; font-weight: 400; font-size: 13.5px; color: #718096; line-height: 1.6; margin:0;">' . html_escape($itemData['description']) . '</p>';
                        $htmlContent .= '</div></a>';
                    }
                    $htmlContent .= '</div></div>';

                    // Ambil ID User aktif untuk keamanan relasi database Simple Pages
                    $currentUser = current_user();
                    $userId = $currentUser ? $currentUser->id : null;

                    if (empty($userId)) {
                        $userTable = $db->getTable('User');
                        $firstUser = $userTable->fetchObject($userTable->getSelect()->limit(1));
                        $userId = $firstUser ? $firstUser->id : 1;
                    }

                    $simplePage = new SimplePagesPage();
                    $simplePage->title = $pageTitle;
                    $simplePage->slug = $pageSlug;
                    $simplePage->text = $htmlContent;
                    $simplePage->use_html_editor = 1;
                    $simplePage->public = 1;
                    $simplePage->is_published = 1;
                    $simplePage->created_by_user_id = $userId;
                    $simplePage->modified_by_user_id = $userId;
                    $simplePage->save();

                    $navLabel = $pageTitle;
                    $navUri = $pageSlug;
                    $successMsg = __('Success! Page created with custom HTML layout in Simple Pages.');
                }

                // Proses sinkronisasi tautan halaman baru ke navbar navigasi utama website
                if ($autoNavbar == '1') {
                    $navOption = get_option('nav_configuration');
                    $currentNav = json_decode($navOption, true) ?: array();
                    
                    $currentNav[] = array(
                        'label' => $navLabel,
                        'type' => 'uri',
                        'uri' => url($navUri)
                    );
                    
                    set_option('nav_configuration', json_encode($currentNav));
                }

                $this->_helper->flashMessenger($successMsg . ' ' . __('And automatically added to the main navigation navbar.'), 'success');
            }
        }
    }
}