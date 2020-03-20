<?php
/*
 * Desenvolvido por Fernando Camacho
 * fernando@fmsistemas.net
 * Pega o valor do bictoin e gravar
 */

include('lib.php');
$db = new connect();
ini_set('display_errors','on');
$a = json_decode(file_get_contents('https://www.mercadobitcoin.net/api/BTC/ticker/'));
//$arquivo = $_SERVER['DOCUMENT_ROOT']."/time.csv";
//$fp = fopen($arquivo, "a+");
//$texto = gmdate("Y-m-d\TH:i:s\Z") . ',' . $a->ticker->last.chr(13);
//fwrite($fp, $texto);
//fclose($fp);
$db->db_query("replace into ultimos values (current_timestamp,'{$a->ticker->last}')");
$db->db_query("delete from ultimos where data<date_sub(current_timestamp,interval 90 day)");

if($_GET[dados]){
    echo 'Time, Value'.chr(13);
    $data=$db->return_array("select * from ultimos where data>=date_sub(current_timestamp,interval 24 hour) order by data desc;");
    foreach($data as $ind => $v){
        echo date("Y-m-d\TH:i:s\Z",strtotime($v[data])).','.$v[valor].chr(13); 
    }
}

?>