<?php
/*
 * Desenvolvido por Fernando Camacho
 * fernando@fmsistemas.net
 */
$saldo=$db->return_array_result("select * from saldo where id='{$_SESSION['usuario']['id']}'");
$volume=$db->return_array_result("select sum(compra/btc) compra,sum(venda) venda,sum(deposito) deposito from transacoes where data=CURDATE() and user='{$_SESSION['usuario']['id']}'");
?>

<div style="text-align:center;padding:20px;">
    Olá <b id="nome"><img src="update.jpg" style="height:20px;cursor: pointer;vertical-align: middle" title="Alterar Nome" onclick="atUsuario()"> <?=$_SESSION['usuario']['nome'];?></b> 
    ----> Saldo Atual em <b>BTC</b> <?=$saldo[bitcoin];?> em <b>R$</b> <?=$saldo[valor];?> <br>
    Volume do Dia: Comprado <?=number_format($volume['compra'],5,',','.');?> BTC / Vendido <?=number_format($volume['venda'],5,',','.');?> BTC / Depositado R$ <?=number_format($volume['deposito'],2,',','.');?>
    <br><br>
    <a href="javascript:getToken()">Pegar Token de Acesso</a><br><span id="token"></span>   
</div>


<script>
    $(document).ready(function () {
        /*
         * Mostra Token ao Cliente;
         */
        getToken = function (){
            _a=ajaxValue('ajax.php?mode=<?=fm('getToken');?>');
            $('#token').html('<textarea style="width:300px;height:150px">'+_a+'</textarea>')
        }

    });
</script>