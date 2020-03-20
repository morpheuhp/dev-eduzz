<?php

/*
 * Desenvolvido por Fernando Camacho
 * fernando@fmsistemas.net
 */
@session_start();
ini_set('display_errors', 'on');

include 'lib.php';
$db = new connect();
$mode = dfm($_GET['mode']);

if ($_SESSION[usuario][id] > 0 || $mode == 'loginUser') {
    switch ($mode) {
        case 'getToken':
            /*
             * Entrega Token
             */
            echo $db->CreateJWT($_SESSION['usuario']['nome'], $_SESSION['usuario']['email'], $_SESSION['usuario']['senha']);
            break;
        case 'createUser':
            /*
             * Cria Usuário
             */
            echo $db->CreateUser($_GET);
            break;
        case 'deposito':
            /*
             * Executa um depósito
             */
            echo $db->Deposit($_GET[valor]);
            break;
        case 'atUser':
            /*
             * Atualiza Usuário
             */
            $data['email'] = $_SESSION['usuario']['email'];
            $_SESSION['usuario']['nome'] = $data['nome'] = $_GET['nome'];
            echo $db->UpdateUser($data);
            break;
        case 'loginUser':
            /*
             * Faz o Login
             */

            if ($_GET['token'] != '' && $_GET['token'] != 'undefined') {
                $type = 'jwt';
            } else {
                $type = 'user';
            }

            $user = $db->Login($type, $_GET);

            if ($user['id'] != '') {

                session_start();
                $_SESSION['usuario'] = $user;
            } else {
                echo 'Usuário ou senha inválidos';
            }
            break;
        case 'confirmaTransacao':
            /*
             * Executa uma Transação
             */
            $btc = $_SESSION['btc']->ticker->last;
            if ($_GET[type] == 1) {
                /*
                 * Efetua a compra do Bitcoin
                 */

                $saldo = $_GET['valor'] / $btc;
                $db->db_query("update saldo set valor=valor-$_GET[valor],bitcoin=bitcoin+$saldo where id='{$_SESSION['usuario']['id']}'");
                $id = $db->return_result("select max(id) from transacoes where user='{$_SESSION['usuario']['id']}' and ano=YEAR(CURDATE()) and data=CURDATE()") + 1;
                $sql = "insert into transacoes (id,user,ano,data,hora,compra,btc) values ($id,'{$_SESSION['usuario']['id']}',YEAR(CURDATE()),CURDATE(),CURTIME(),'{$_GET['valor']}','$btc')";
                $db->db_query($sql);
            } else {
                /*
                 * Efetua a venda do BitCoin
                 */
                $saldo = $_GET[valor] * $btc;
                $db->db_query("update saldo set valor=valor+$saldo,bitcoin=bitcoin-$_GET[valor] where id='{$_SESSION['usuario']['id']}'");
                $id = $db->return_result("select max(id) from transacoes where user='{$_SESSION['usuario']['id']}' and ano=YEAR(CURDATE()) and data=CURDATE()") + 1;
                $sql = "insert into transacoes (id,user,ano,data,hora,venda,btc) values ($id,'{$_SESSION['usuario']['id']}',YEAR(CURDATE()),CURDATE(),CURTIME(),'{$_GET['valor']}','$btc')";
                $db->db_query($sql);
            }
            break;
        case 'ultimovalor':
            /*
             * Pega ultimo valor do bitcoin
             */
            echo $_SESSION['btc']->ticker->last;
            break;

        case 'gerUltimo':
            /*
             * Atualiza arquivo e pega valor
             */
            $a = json_decode(file_get_contents('https://www.mercadobitcoin.net/api/BTC/ticker/'));
            $_SESSION['btc'] = $a;
            $arquivo = $_SERVER['DOCUMENT_ROOT'] . "/time.csv";
            $fp = fopen($arquivo, "a+");
            $texto = gmdate("Y-m-d\TH:i:s\Z") . ',' . $a->ticker->last . chr(13);
            fwrite($fp, $texto);
            fclose($fp);
            echo 'Preço da unidade de Bitcoin <br><b class=ultimovalor>R$ ' . number_format($a->ticker->last, 5, ',', '.') . '<b>';
            break;
        default:
            break;
    }
} else {
    echo 'ACESSO NEGADO';
}