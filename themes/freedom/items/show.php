<?php

$title = metadata('item', 'display_title');
$itemDescription = metadata('item', array('Dublin Core', 'Description'));
$itemSource = metadata('item', array('Dublin Core', 'Source')); 

// AMBIL DATA LINK DARI KOLOM DUBLIN CORE: RELATION
$itemModel3D = metadata('item', array('Dublin Core', 'Relation')); 

// Mengambil file gambar utama dari item untuk background banner
$bannerImageUrl = '/mix_reality/themes/freedom/images/header.png'; 
if (metadata('item', 'has files')) {
    $itemFiles = $item->getFiles();
    if (!empty($itemFiles)) {
        $firstFile = $itemFiles[0];
        if ($firstFile->hasThumbnail()) {
            $bannerImageUrl = $firstFile->getWebPath('fullsize');
        }
    }
}
?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lora:wght@400&family=Poppins:wght@400&display=swap" rel="stylesheet">

<?php echo head(array('title' => $title, 'bodyclass' => 'items show')); ?>

<style>
    /* Menghilangkan paksaan pembungkus margin bawaan Omeka */
    #main-content.container {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Layout utama fleksibel mengikuti panjang teks alami */
    .custom-full-page {
        display: flex;
        flex-direction: column;
        width: 100%;
        box-sizing: border-box;
        background: #ffffff;
        min-height: 100vh;
    }
</style>

<div class="custom-full-page">

    <div class="item-hero-banner" style="
        background-image: url('<?php echo html_escape($bannerImageUrl); ?>'); 
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat; 
        height: 240px; 
        width: 100%;
        display: flex; 
        align-items: center; 
        justify-content: center; 
        position: relative;
        margin: 0;
        padding: 0;
    ">
        <div style="position: absolute; top:0; left:0; right:0; bottom:0; background: rgba(0, 0, 0, 0.5); z-index: 1;"></div>
        
        <div style="position: relative; z-index: 2; text-align: center; color: #ffffff; max-width: 900px; padding: 0 20px;">
            <h1 style="font-size: clamp(34px, 4vw, 48px); font-weight: 400; margin: 0; color: #ffffff; text-shadow: 1px 1px 6px rgba(0,0,0,0.4); font-family: 'Lora', serif; letter-spacing: 0.5px;">
                <?php echo $title; ?>
            </h1>
        </div>
    </div>

    <div style="width: 100%; padding: 50px 60px; box-sizing: border-box;">
        
        <div style="display: flex; gap: 50px; align-items: flex-start; justify-content: space-between; width: 100%; margin-bottom: 50px;">
            
            <div style="flex: 1; text-align: justify; line-height: 1.9; font-size: 16px; color: #334155; font-family: 'Poppins', sans-serif; font-weight: 400; box-sizing: border-box;">
                
                <div style="margin-bottom: 25px;">
                    <?php if ($itemDescription): ?>
                        <div style="font-family: 'Poppins', sans-serif; font-weight: 400;">
                            <?php echo $itemDescription; ?>
                        </div>
                    <?php else: ?>
                        <p style="color: #334155; font-family: 'Poppins', sans-serif; font-weight: 400; margin: 0;">
                            Deskripsi mengenai situs sejarah ini belum ditambahkan di Dashboard Admin Omeka.
                        </p>
                    <?php endif; ?>
                </div>

                <?php if ($itemModel3D): ?>
                    <div style="margin-top: 30px;">
                        <a href="<?php echo html_escape($itemModel3D); ?>" target="_blank" style="
                            display: inline-flex; 
                            align-items: center; 
                            gap: 10px;
                            padding: 12px 28px; 
                            background: #2563eb; 
                            color: #ffffff; 
                            text-decoration: none; 
                            font-size: 14px; 
                            font-weight: 500;
                            border-radius: 6px; 
                            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
                            transition: all 0.2s ease; 
                            font-family: 'Poppins', sans-serif;
                        " onmouseover="this.style.background='#1d4ed8'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#2563eb'; this.style.transform='translateY(0)';">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                            Lihat Model 3D
                        </a>
                    </div>
                <?php endif; ?>

            </div>

            <div style="flex: 1.4; box-sizing: border-box;">
                <?php if ($itemSource): ?>
                    <div style="
                        width: 100%; 
                        aspect-ratio: 16 / 10; 
                        border-radius: 6px; 
                        overflow: hidden; 
                        box-shadow: 0 4px 20px rgba(0,0,0,0.06); 
                        border: 1px solid #e2e8f0;
                    ">
                        <?php 
                        if (strpos($itemSource, 'iframe') !== false) {
                            $fixedIframe = preg_replace('/height="[^"]*"/', 'height="100%"', $itemSource);
                            $fixedIframe = preg_replace('/width="[^"]*"/', 'width="100%"', $fixedIframe);
                            
                            if (strpos($fixedIframe, 'allowfullscreen') === false) {
                                $fixedIframe = str_replace('<iframe', '<iframe allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"', $fixedIframe);
                            }
                            echo $fixedIframe;
                        } else {
                            echo '<iframe width="100%" height="100%" src="' . html_escape($itemSource) . '" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" allow="xr-spatial-tracking"></iframe>';
                        }
                        ?>
                    </div>
                <?php else: ?>
                    <div style="width: 100%; aspect-ratio: 16 / 10; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; display: flex; align-items: center; justify-content: center; text-align: center; padding: 20px;">
                        <p style="color: #64748b; margin: 0; font-size: 14px; font-family: 'Poppins', sans-serif; font-weight: 400;">
                            Virtual Tour 360° belum disematkan.<br>Silakan isi tautan Kuula di kolom <strong>Dublin Core: Source</strong>.
                        </p>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="javascript:history.back()" style="display: inline-block; padding: 11px 50px; border: 1px solid #cbd5e1; border-radius: 4px; color: #475569; text-decoration: none; font-size: 13px; background: #ffffff; transition: all 0.2s; font-family: 'Poppins', sans-serif; font-weight: 400;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8'; this.style.color='#0f172a';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#cbd5e1'; this.style.color='#475569';">
                kembali
            </a>
        </div>
    </div>

</div>

<div style="display:none;">
    <?php fire_plugin_hook('public_items_show', array('view' => $this, 'item' => $item)); ?>
</div>

<?php echo foot(); ?>