<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<table class="menu" cellpadding="0" cellspacing="0">
    <tr>
        <td onclick="menu('<?=fm('minhaconta');?>')">Minha Conta</td>
        <td onclick="menu('<?=fm('deposito');?>')">Depósito</td>
        <td onclick="menu('<?=fm('dashboard');?>')">Dashboard</td>
        <td onclick="menu('<?=fm('sair');?>')">Sair</td>
        <td id="ultimo" onclick="carregaValores()"></td>
    </tr>
</table>