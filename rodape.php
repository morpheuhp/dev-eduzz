<?php
/*
 * Desenvolvido por Fernando Camacho
 * fernando@fmsistemas.net
 */

/**
 * Só carregará o arquivo se estiver logado
 */
?>
<script type="text/javascript">
    $(document).ready(function () {
        atUsuario = function () {
            _nome = prompt('Digite um novo nome', '');

            if (_nome != '') {

                _a = ajaxValue('/ajax.php?mode=<?= fm('atUser'); ?>&nome=' + _nome);

                $('#nome').html(_nome);
            }
        }

        depositar = function () {
            _valor = $('#deposito').val();
            if (_valor > 0) {
                _a = ajaxValue('/ajax.php?mode=<?= fm('deposito'); ?>&valor=' + _valor);

                alert(_a);
                $('#deposito').val('0.00');
            }
        }


        carregaValores = function () {
        console.log('/ajax.php?mode=<?= fm('gerUltimo'); ?>');
            _a = ajaxValue('/ajax.php?mode=<?= fm('gerUltimo'); ?>');
            $('#ultimo').html(_a);
            $('#ultimovalor').html(_a);
        }

        setInterval(function () {
            carregaValores();
        }, 5000);
        carregaValores();
    });
</script>