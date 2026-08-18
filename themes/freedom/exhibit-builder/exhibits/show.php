<?php
// Deteksi judul halaman pameran aktif
$judulHalamanPameran = metadata('exhibit_page', 'title') ? metadata('exhibit_page', 'title') : metadata('exhibit', 'title');
$judulBersih = strtolower(trim($judulHalamanPameran));

echo head(array(
    'title' => $judulHalamanPameran . ' &middot; ' . metadata('exhibit', 'title'),
    'bodyclass' => 'exhibits show'));

$prevText = __('&larr; Previous Exhibit page');
$prevLink = exhibit_builder_link_to_previous_page($prevText);

$nextText = __("Next Exhibit page &rarr;");
$nextLink = exhibit_builder_link_to_next_page($nextText);
?>

<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    #main-content.container {
        max-width: 100%;
        width: 100%;
        padding: 0;
        margin: 0;
    }

    .galeri-figma-wrapper {
        background-color: #ffffff;
        width: 100%;
        min-height: 100vh;
        padding: 50px 60px;
        box-sizing: border-box;
    }

    .galeri-figma-wrapper h1 {
        font-family: 'Lora', serif;
        font-size: 32px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 40px 0;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .galeri-figma-wrapper .grid-kustom-kuula {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 40px;
        width: 100%;
        margin-bottom: 50px;
    }

    .link-pembungkus-box {
        text-decoration: none;
        display: block;
        width: 100%;
        box-sizing: border-box;
    }

    .grid-kustom-kuula .kartu-box-baru {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        display: flex;
        flex-direction: column;
        width: 100%;
        box-sizing: border-box;
    }

    .link-pembungkus-box:hover .kartu-box-baru {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(59, 130, 246, 0.15);
        border-color: #3b82f6;
    }

    .kartu-box-baru .box-cover-kuula {
        width: 100%;
        aspect-ratio: 16 / 10;
        background-color: #f8fafc;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid #e2e8f0;
    }

    .box-cover-kuula iframe {
        width: 100%;
        height: 100% !important;
        border: none;
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
    }

    .kegiatan-click-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 2;
    }

    .kartu-box-baru .box-info-teks {
        padding: 24px 15px;
        text-align: center;
        background: #ffffff;
    }

    .box-info-teks .judul-situs {
        font-size: 16px;
        margin: 0;
        line-height: 1.4;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: #1e293b;
        transition: color 0.2s ease;
    }

    .link-pembungkus-box:hover .judul-situs {
        color: #3b82f6;
    }

    .galeri-figma-wrapper .hidden-omeka-render {
        display: none;
    }

    #exhibit-pages {
        display: none !important;
    }

    .galeri-figma-wrapper .site-page-pagination {
        display: flex;
        justify-content: space-between;
        list-style: none;
        padding: 0;
        margin: 50px 0 20px 0;
        border-top: 1px solid #e2e8f0;
        padding-top: 20px;
    }

    .site-page-pagination .site-page-pagination-button a {
        text-decoration: none;
        color: #3b82f6;
        font-weight: 500;
        font-size: 14px;
    }
</style>

<div class="galeri-figma-wrapper">

    <h1><?php echo html_escape($judulHalamanPameran); ?></h1>

    <div id="data-asli-sistem" class="hidden-omeka-render">
        <?php echo exhibit_builder_render_exhibit_page(); ?>
        <nav id="exhibit-pages">
            <?php echo exhibit_builder_page_nav(); ?>
        </nav>
    </div>

    <div id="container-galeri-kustom" class="grid-kustom-kuula"></div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var targetGrid = document.getElementById('container-galeri-kustom');
        var containerAsli = document.getElementById('data-asli-sistem');
        var judulSekarang = "<?php echo $judulBersih; ?>";
        
        // Ambil link menu navigasi internal pameran Omeka
        var subPages = document.querySelectorAll('.exhibit-page-list a, ul.exhibit-page-list li a, .exhibit-child-pages a, #exhibit-pages a');
        var itemAsli = containerAsli.querySelectorAll('.exhibit-item');

        var daftarNamaNisan = [
            "Nisan Tuanku Zainal Abidin",
            "Nisan Sultan Alauddin Mahmudsyah",
            "Nisan Putri Raja Anak Raja Bangka Hulu",
            "Nisan Sultan Ali Mughayat Syah",
            "Nisan Sultan Alauddin Inayat/Ri'ayat Syah",
            "Nisan Sultan Abdullah",
            "Nisan Sultan Ali Ri'ayat Syah",
            "Nisan Sultan Salahudin bin Sultan Ali mughayat Syah",
            "Nisan Sultan Yusuf Bin Abdullah",
            "Nisan Raja Perempuan Darussalam"
        ];

        // LOGIKA 1: Halaman Kategori / Pembungkus (Jika tidak ada item pameran langsung, tapi punya sub-halaman/anak)
        if (itemAsli.length === 0 && subPages.length > 0) {
            var urlTerproses = new Set();

            subPages.forEach(function(pageLink) {
                var pageUrl = pageLink.getAttribute('href');
                var pageTitle = pageLink.textContent.trim();
                
                if (!pageUrl || urlTerproses.has(pageUrl)) return;
                urlTerproses.add(pageUrl);
                
                // Normalisasi penamaan agar rapi sesuai keinginanmu
                if(pageTitle.toLowerCase() === 'makam') {
                    pageTitle = "3D Model Makam Kandang XII";
                }

                var linkWrapper = document.createElement('a');
                linkWrapper.className = 'link-pembungkus-box';
                linkWrapper.setAttribute('href', pageUrl);
                
                var visualCover = '<div style="display:flex; flex-direction:column; justify-content:center; align-items:center; height:100%; color:#94a3b8; font-family:\'Poppins\'; font-size:14px; background:#f8fafc; padding:20px; text-align:center;">' +
                                  '<svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.5" style="margin-bottom:12px;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>' +
                                  'Klik untuk melihat koleksi 3D' +
                                  '</div>';

                linkWrapper.innerHTML = '<div class="kartu-box-baru">' +
                                        '<div class="box-cover-kuula">' + visualCover + '</div>' +
                                        '<div class="box-info-teks"><h3 class="judul-situs">' + pageTitle + '</h3></div>' +
                                        '</div>';
                targetGrid.appendChild(linkWrapper);
            });
        }
        // LOGIKA 2: Jika halaman tersebut berisikan data koleksi nisan langsung (Sub-Halaman)
        else if (itemAsli.length > 0) {
            itemAsli.forEach(function(item, indeks) {
                var linkNode = item.querySelector('a'); 
                var itemUrl = linkNode ? linkNode.getAttribute('href') : '#';

                var namaSitus = "";
                if (daftarNamaNisan[indeks]) {
                    namaSitus = daftarNamaNisan[indeks];
                } else {
                    var titleEl = item.querySelector('.exhibit-item-title') || item.querySelector('.item-title') || item.querySelector('h3');
                    namaSitus = titleEl ? titleEl.textContent.trim() : "Nisan Kompleks Kandang XII";
                }

                if (judulSekarang.includes('tur virtual')) {
                    namaSitus = (indeks === 0) ? "Museum Aceh" : "Kompleks Makam Kandang XII";
                }

                var mediaKonten = "";
                var isIframe = false;
                
                if (judulSekarang.includes('tur virtual')) {
                    var kuulaMakam = '<iframe frameborder="0" allow="xr-spatial-tracking; gyroscope; accelerometer" allowfullscreen="allowfullscreen" scrolling="no" src="https://kuula.co/share/collection/7K1Bj?logo=1&amp;info=1&amp;fs=1&amp;vr=0&amp;sd=1&amp;thumbs=1"></iframe>';
                    var kuulaMuseumAceh = '<iframe frameborder="0" allow="xr-spatial-tracking; gyroscope; accelerometer" allowfullscreen="allowfullscreen" scrolling="no" src="https://kuula.co/share/collection/7K1J9?logo=1&amp;info=1&amp;fs=1&amp;vr=0&amp;sd=1&amp;initload=0&amp;thumbs=1"></iframe>';
                    mediaKonten = (indeks === 1) ? kuulaMakam : kuulaMuseumAceh;
                    isIframe = true;
                } else {
                    var iframeAsli = item.querySelector('iframe');
                    if(iframeAsli) {
                        mediaKonten = iframeAsli.outerHTML;
                        isIframe = true;
                    } else {
                        var fileAsli = item.querySelector('.exhibit-item-file');
                        mediaKonten = fileAsli ? fileAsli.innerHTML : '<div style="display:flex; justify-content:center; align-items:center; height:100%; color:#94a3b8; font-family:\'Poppins\'; font-size:14px; background:#f1f5f9;">Preview Model 3D</div>';
                    }
                }

                var linkWrapper = document.createElement('a');
                linkWrapper.className = 'link-pembungkus-box';
                linkWrapper.setAttribute('href', itemUrl);

                var overlayKlik = !isIframe ? '<div class="kegiatan-click-overlay"></div>' : '';

                linkWrapper.innerHTML = '<div class="kartu-box-baru">' +
                                        '<div class="box-cover-kuula">' + overlayKlik + mediaKonten + '</div>' +
                                        '<div class="box-info-teks"><h3 class="judul-situs">' + namaSitus + '</h3></div>' +
                                        '</div>';
                targetGrid.appendChild(linkWrapper);
            });
        } 
        // LOGIKA 3: Fallback jika kosong total
        else {
            targetGrid.innerHTML = '<p style="color: #64748b; font-family:\'Poppins\'; font-size: 14px; padding: 20px;">Belum ada item pameran di halaman ini.</p>';
        }
    });
    </script>

    <?php if ($prevLink || $nextLink) : ?>
        <nav style="width: 100%; clear: both;">
            <ul class="site-page-pagination">
                <?php if ($prevLink) : ?>
                    <li id="previous-item" class="site-page-pagination-button previous" style="font-family: 'Poppins', sans-serif;"><h4 style="margin:0; font-weight:500; font-size:14px;"><?php echo $prevLink; ?></h4></li>
                <?php endif; ?>
                <?php if ($nextLink) : ?>
                    <li id="next-item" class="site-page-pagination-button next" style="font-family: 'Poppins', sans-serif;"><h4 style="margin:0; font-weight:500; font-size:14px;"><?php echo $nextLink; ?></h4></li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>

</div>

<?php echo foot(); ?>