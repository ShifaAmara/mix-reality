<?php
/**
 * Homepage template.
 */

queue_js_file(array('minimasonry.min', 'browse'));

$homepage_text = get_theme_option('homepage_text') ?? '';
?>

<?php echo head(array('bodyid' => 'home')); ?>

<!-- MENU ICON -->
<div class="menu-icon">
    ☰
</div>

<!-- SEARCH BAR -->
<div class="custom-search-bar">

    <span class="search-icon">⌕</span>

    <input type="text" placeholder="Search historical collections">

</div>

<!-- HERO -->
<div class="histech-hero">

    <img
        src="/mix_reality/themes/apelinda/images/histech-banner.jpg"
        alt="Banner Museum Digital Aceh"
    >

    <div class="hero-overlay"></div>

    <div class="histech-hero-content">

        <h2>
            Digital Museum of Aceh <br>
            Cultural Heritage
        </h2>

        <p>
            Welcome! This website connects you directly with Aceh's history and 3D cultural heritage.
            Here, you can explore virtual tours and examine 3D tombstone artifacts up close.
            Start exploring our digital collections now through the Virtual Tour 360° and 3D Models pages.
        </p>

    </div>

</div>

<!-- EXPLORE TITLE -->
<div class="histech-explore-header">

    <h3>Explore</h3>

</div>

<!-- CARD SLIDER -->
<div class="histech-card-grid">

    <!-- CARD 1 -->
    <div class="histech-card">

        <img
            src="/mix_reality/themes/apelinda/images/museum.jpg"
            class="histech-card-image"
            alt="Museum Aceh"
        >

        <div class="histech-card-content">

            <h4>
                <a href="#">
                    Museum Aceh
                </a>
            </h4>

            <p>Kota Banda Aceh</p>

        </div>

    </div>

    <!-- CARD 2 -->
    <div class="histech-card">

        <img
            src="/mix_reality/themes/apelinda/images/museum.jpg"
            class="histech-card-image"
            alt="Museum Aceh"
        >

        <div class="histech-card-content">

            <h4>
                <a href="#">
                    Rumoh Aceh
                </a>
            </h4>

            <p>Kota Banda Aceh</p>

        </div>

    </div>

    <!-- CARD 3 -->
    <div class="histech-card">

        <img
            src="/mix_reality/themes/apelinda/images/museum.jpg"
            class="histech-card-image"
            alt="Museum Aceh"
        >

        <div class="histech-card-content">

            <h4>
                <a href="#">
                    Gunongan
                </a>
            </h4>

            <p>Kota Banda Aceh</p>

        </div>

    </div>

    <!-- CARD 4 -->
    <div class="histech-card">

        <img
            src="/mix_reality/themes/apelinda/images/museum.jpg"
            class="histech-card-image"
            alt="Museum Aceh"
        >

        <div class="histech-card-content">

            <h4>
                <a href="#">
                    Kerkhof
                </a>
            </h4>

            <p>Kota Banda Aceh</p>

        </div>

    </div>

    <!-- CARD 5 -->
    <div class="histech-card">

        <img
            src="/mix_reality/themes/apelinda/images/museum.jpg"
            class="histech-card-image"
            alt="Museum Aceh"
        >

        <div class="histech-card-content">

            <h4>
                <a href="#">
                    PLTD Apung
                </a>
            </h4>

            <p>Kota Banda Aceh</p>

        </div>

    </div>

</div>

<?php echo foot(); ?>