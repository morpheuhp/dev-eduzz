<?php

/*
 * Desenvolvido por Fernando Camacho
 * fernando@fmsistemas.net
 */

/**
 * Arquivo seleciona o arquivo que deverá ser carregado.
 * Nenhum arquivo funciona se não passar por aqui, por questões de segurança
 */
@session_start();
$db = new connect();

if ($_SESSION['usuario']['id'] > 0) {
    include('menu.php');
    switch (dfm($_GET['mode'])) {
        case 'minhaconta':
            include('conta.php');
            break;
        case 'deposito':
            include('deposito.php');
            break;
        case 'dashboard':
            include('dashboard.php');
            break;
        
        case 'sair':
            
            $_SESSION['usuario'] = array();
            header('Location: index.php');

            break;
        default:
            include('conta.php');
            break;
    }
    include('rodape.php');
} else {
    include('login.php');
}
?>