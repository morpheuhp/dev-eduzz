<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div style="text-align:center;margin-top:20px;padding:10px;font-weight: bold">
    
    Informe o valor a ser depositado: <input type="text" style="text-align: right" id="deposito" value="" onkeyup="numberMask(this,2)"> <input type="button" value="Confirmar Depósito" onclick="depositar()">
</div>
<script>
$('#deposito').blur();
$('#deposito').focus();
</script>