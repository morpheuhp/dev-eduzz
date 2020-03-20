<?php
/*
 * Desenvolvido por Fernando Camacho
 * fernando@fmsistemas.net
 */
?>
<html>
    <head>
        <title>Project Bitcoin</title>
        <script src="jquery-3.4.1.min.js?id=<?= uniqid(); ?>" type="text/javascript"></script>
        <script src="functions.js?id=<?= uniqid(); ?>" type="text/javascript"></script>
        <link crossorigin="anonymous" media="all"  rel="stylesheet" href="geral.css?id=<?= uniqid(); ?>">
    </head>
    <body>
        <div class="opaco" style="display:none" id="opaco" ><img src="carregando.gif" id="imgopaco"></div>
        <form method="post" id="form">
            <?php
            /**
             * Carrega Biblioteca de conexão e funçoes
             */
            include('lib.php');

            /*
             * Informa que arquivo deverá ser carregado
             */
            include('mode.php');
            ?>
        </form>
    </body>
</html>