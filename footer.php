<?php
if(isset($_COOKIE['lang'])) {
    $lang = $_COOKIE['lang'];
} else {
    // si aucune langue n'est déclaré, la langue par default est l'anglais
    $lang = 'en';
}

// fichier de langue a importer
if ($lang=='fr') {           // si la langue est 'fr' (français) on inclut le fichier (...)_fr_FR.php
    include('i18n/fr_FR/footer_fr_FR.php');
} elseif ($lang=='en') {      // si la langue est 'en' (anglais) on inclut le fichier (...)_en_GB.php
    include('i18n/en_UK/footer_en_UK.php');
}
?>

<div class="container-fluid">
<div class="row">
<footer>
    <ul>
        <li><a href="index.php"><?php echo $home ?></a></li>|
        <li><a href="nous.php"><?php echo $lab ?></a></li>|
        <li><a href="search_species.php"><?php echo $species ?></a></li>|
        <li><a href="search_followed.php"><?php echo $followed ?></a></li>|
        <li><a href="search_facility.php"><?php echo $facility ?></a></li>|
        <li><a href="help.php"><?php echo $help ?></a></li>
    </ul>
    &copy; All rights reserved GABA
    <a id="goTop"><span class="glyphicon glyphicon-chevron-up"></span></a>
</footer>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"
integrity="sha512-A7vV8IFfih/D732iSSKi20u/ooOfj/AGehOKq0f4vLT1Zr2Y+RX7C+w8A1gaSasGtRUZpF/NZgzSAu4/Gc41Lg=="
crossorigin=""></script>
<script src="js/myScript.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('#goTop').stop().animate({
                bottom: '20px'
            }, 500);
        } else {
            $('#goTop').stop().animate({
                bottom: '-100px'
            }, 500);
        }
    });
});
$('#goTop').click(function() {
    $('html, body').stop().animate({
        scrollTop: 0
    }, 500, function() {
        $('#goTop').stop().animate({
            bottom: '-100px'
        }, 500);
    });
});
</script>
