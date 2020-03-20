<?php
/*
 * Desenvolvido por Fernando Camacho
 * fernando@fmsistemas.net
 */
?>
<div style="margin:0 20% 0 20%;border:1px solid black;text-align: center;padding:20px">
    Digite seu E-mail: <input type="text" id="email" name="email"><br><Br>
    Digite sua Senha: <input type="password" id="senha" name="senha"><br><br>

    Acessar usando Token: <input type="text" id="token"><br><br>
    <input type="button" value="Fazer Login" onclick="loginUser()">
    <input type="button" value="Fazer Cadastro" onclick="createUser()">

</div>
<script>
    $(document).ready(function () {
        /*
         * Cria um Usuário
         * @returns {string}
         */
        createUser = function () {
            _mail = $('#email').val();
            _senha = $('#senha').val();
            _a = ajaxValue('/ajax.php?mode=<?= fm('createUser'); ?>&email=' + _mail + '&senha=' + _senha);
            alert(_a);
        }
        
        /*
         * Valida dos dados do Usuário
         */
        loginUser = function () {
            _mail = $('#email').val();
            _senha = $('#senha').val();
            _token = $('#token').val();
            _a = ajaxValue('/ajax.php?mode=<?= fm('loginUser'); ?>&email=' + _mail + '&senha=' + _senha + '&token=' + _token);
            if (_a !== '') {
                alert(_a);
            }else{
                location.reload();
            }
                
        }
    });
</script>