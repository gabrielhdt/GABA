<footer>
    <ul>
        <li class="active"><a href="#">Accueil</a></li>|
        <li><a href="#">Notre labo</a></li>|
        <li><a href="#">Espèce</a></li>|
        <li><a href="#">Individu</a></li>|
        <li><a href="#">Bâtiment</a></li>|
        <li><a href="#">Chercheur</a></li>|
        <li><a href="#">Help</a></li>
    </ul>
    &copy All rights reserved GABA
    <a id="goTop"><span class="glyphicon glyphicon-chevron-up"></a>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $(window).scroll(function() {
            if($(this).scrollTop() > 100){
                $('#goTop').stop().animate({
                    bottom: '20px'
                    }, 500);
            }
            else{
                $('#goTop').stop().animate({
                   bottom: '-100px'
                }, 500);
            }
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
    });
    </script>
</footer>
