<?php
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />';
echo '<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>';

/* BAGIAN RESOURCE FONT POPPINS (REGULAR) & LORA VIA GOOGLE FONTS */
echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
echo '<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">';

queue_js_file(array('minimasonry.min', 'browse'));
$homepage_text = get_theme_option('homepage_text') ?? '';
?>

<?php echo head(array('bodyid' => 'home')); ?>

<style>
    /* BAGIAN STYLE SEKSI UTAMA & BACKGROUND KUNING #E3A650 SAMPAI FOOTER */
    .horizontal-explore-section {
        background-color: #ffffff; 
        padding: 60px 0 80px 0; 
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        margin-bottom: -50px; 
        font-family: 'Poppins', sans-serif;
        transition: background-color 0.6s ease; 
        overflow: hidden;
    }

    .horizontal-explore-section:hover,
    .horizontal-explore-section:active,
    .horizontal-explore-section:focus-within {
        background-color: #374D6A; 
    }

    /* BAGIAN STYLE JUDUL UTAMA (TETAP PAKAI LORA) */
    .slider-title-box {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .slider-title-box h2 {
        font-size: 42px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        font-family: 'Lora', serif; 
        transition: color 0.4s ease;
    }

    .horizontal-explore-section:hover .slider-title-box h2 {
        color: #ffffff; 
    }

    .swiper-wrapper-horizontal {
        padding-bottom: 30px;
    }

    /* BAGIAN STYLE KARTU ITEM */
    .card-item-horizontal {
        width: 280px; 
        height: 420px; 
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        cursor: pointer;
        background: #f5f8fa;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-item-horizontal:hover {
        transform: translateY(-5px); 
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }

    .card-image-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

  .card-image-bg img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center center; /* Memastikan titik tengah foto lanskap selalu jadi fokus utama di dalam kartu */
    transition: transform 0.5s ease;
}

    .card-item-horizontal:hover .card-image-bg img {
        transform: scale(1.08); 
    }

    .card-overlay-shadow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 55%, rgba(0,0,0,0) 100%);
        z-index: 2;
    }

    /* BAGIAN STYLE NAMA MUSEUM / MAKAM (POPPINS REGULAR / BIASA) */
    .card-content-text {
        position: relative;
        z-index: 3;
        padding: 25px;
        color: #ffffff;
        text-align: left;
    }

    .card-content-text h3 {
        font-size: 20px;
        margin: 0;
        line-height: 1.3;
    }

    /* Memaksa link menggunakan font Poppins ketebalan normal/biasa (400) */
    .card-content-text h3 a {
        font-family: 'Poppins', sans-serif !important;
        font-weight: 400 !important; 
        color: #ffffff;
        text-decoration: none;
    }

    /* BAGIAN STYLE TOMBOL PANAH NAVIGASI */
    .swiper-button-next-custom, .swiper-button-prev-custom {
        width: 45px;
        height: 45px;
        background: #ffffff;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        color: #2c3e50;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .swiper-button-next-custom:after, .swiper-button-prev-custom:after {
        font-size: 16px;
        font-weight: bold;
    }

    .swiper-button-next-custom { right: 15px; }
    .swiper-button-prev-custom { left: 15px; }

    .swiper-button-next-custom:hover, .swiper-button-prev-custom:hover {
        background: #2c3e50;
        color: #ffffff;
    }
</style>


<!-- BAGIAN AREA EXPLORE SLIDER -->
<div class="horizontal-explore-section">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px; position: relative;">
        
        <div class="slider-title-box">
            <h2>Explore</h2>
        </div>

        <div class="swiper swiper-container-horizontal">
            <div class="swiper-wrapper swiper-wrapper-horizontal">

                <?php 
                $featuredItems = get_records('Item', array('featured' => true), 0); 
                ?>

                <?php if (count($featuredItems) > 0): ?>
                    <?php foreach ($featuredItems as $item): ?>
                        
                        <div class="swiper-slide card-item-horizontal" onclick="window.location='<?php echo record_url($item, 'show'); ?>'">
                            
                            <!-- KARTU: GAMBAR LATAR BELAKANG -->
                            <div class="card-image-bg">
                                <?php if (metadata($item, 'has thumbnail')): ?>
                                    <?php echo record_image($item, 'fullsize', array('style' => 'width:100%; height:100%; object-fit:cover;')); ?>
                                <?php else: ?>
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #bdc3c7; font-weight: bold; background: #e1e8ed;">
                                        [ No Image ]
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-overlay-shadow"></div>
                            
                            <!-- KARTU: KONTEN TEKS OVERLAY -->
                            <div class="card-content-text">
                                <h3>
                                    <a href="<?php echo record_url($item, 'show'); ?>">
                                        <?php echo metadata($item, array('Dublin Core', 'Title')); ?>
                                    </a>
                                </h3>
                            </div>

                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; color: #7f8c8d; padding: 40px; width: 100%; font-family: 'Poppins', sans-serif;">
                        <p style="font-size: 16px;">Belum ada item yang disetujui (Featured: Yes) untuk tampil di sini.</p>
                    </div>
                <?php endif; ?>

            </div>
            
            <div class="swiper-button-next swiper-button-next-custom"></div>
            <div class="swiper-button-prev swiper-button-prev-custom"></div>
        </div>

    </div>
</div>

<!-- BAGIAN JAVASCRIPT SLIDER SWIPER -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var swiperHorizontal = new Swiper(".swiper-container-horizontal", {
            slidesPerView: "auto",
            spaceBetween: 25, 
            grabCursor: true, 
            navigation: {
                nextEl: ".swiper-button-next-custom",
                prevEl: ".swiper-button-prev-custom",
            },
            breakpoints: {
                320: { slidesPerView: 1, spaceBetween: 15 },
                640: { slidesPerView: 2, spaceBetween: 20 },
                1024: { slidesPerView: 3, spaceBetween: 25 },
                1200: { slidesPerView: 4, spaceBetween: 25 }
            }
        });
    });
</script>

<?php echo foot(); ?>