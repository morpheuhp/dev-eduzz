<?php
/*
 * Desenvolvido por Fernando Camacho
 * fernando@fmsistemas.net
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './phpmailer/src/Exception.php';
require './phpmailer/src/PHPMailer.php';
require './phpmailer/src/SMTP.php';

class connect extends PHPMailer {

    function __construct() {
        $this->myPassword = 'wHsPqFy3$!&^';
        $this->DB_HOST = 'remotemysql.com';
        $this->DB_USER = 'rVxfvX2BiR';
        $this->DB_PASS = 'RNNEBqQbzR';
        $this->DB_NAME = 'rVxfvX2BiR';


        $this->mail = 'Project BitCoin';
        $this->mailName = 'bitc7843@gmail.com';
        $this->mailPassword = '1bitc0in';


        $this->myPassword = 'wHsPqFy3$!&^';
        $this->pdo = new PDO("mysql:host=$this->DB_HOST;dbname=$this->DB_NAME", $this->DB_USER, $this->DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Executa uma SQL
     * @param type $sql
     * @return type
     */
    public function db_query($sql) {
        try {
            return $this->pdo->query($sql);
        } catch (PDOException $e) {
            echo $sql . '<hr>';
            print_r($e->getMessage());
        }
    }

    /**
     * Função para retornar array dimensional de uma SQL
     * @sql type string
     * @return type Array
     */
    function return_array($sql) {
        $qry = $this->pdo->query($sql);
        $qry->execute();

        $a = array();
        $i = 0;
        while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
            foreach ($res as $name => $v) {
                $a[$i][$name] = $v;
            }
            $i++;
        }

        return $a;
    }

    /**
     * Função para retornar um array
     * @sql type String
     * @return type array
     */
    function return_array_result($sql) {
        $qry = $this->pdo->query($sql);
        $qry->execute();

        $a = array();
        $i = 0;
        while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
            foreach ($res as $name => $v) {
                $a[$name] = $v;
            }
            $i++;
        }

        return $a;
    }

    /**
     * Função retorna hum único resultado de uma consulta sql
     * @sql type string
     * @return type string
     */
    public function return_result($sql) {
        $qry = $this->db_query($sql);
        $qry->execute();

        $a = array();
        $i = 0;

        while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
            foreach ($res as $name => $v) {
                return $v;
            }
        }
    }

    /**
     * Grava Log
     * @param type $msg
     */
    function savelog($msg) {
        $msg = addslashes($msg);

        $this->db_query("replace into log values (current_timestamp,'{$_SESSION['usuario']['id']}','$msg')");
    }

    /**
     * Cria JWT
     * @name type string
     * @email type string
     * @password type string
     * @return type string
     */
    public function CreateJWT($name, $email, $password) {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $header = json_encode($header);
        $header = base64_encode($header);

        $information = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];
        $information = json_encode($information);
        $information = base64_encode($information);

        $signature = hash_hmac('sha256', "$header.$information", $this->myPassword, true);
        $signature = base64_encode($signature);

        return "$header.$information.$signature";
    }

    /**
     * Valida JWT
     * @token type string
     * @return boolean
     */
    public function OpenJWT($token) {
        $token = str_replace(' ', '+', $token);

        $token = explode(".", $token);

        $header = $token[0];
        $payload = $token[1];
        $signature = $token[2];

        $valid = hash_hmac('sha256', "$header.$payload", $this->myPassword, true);
        $valid = base64_encode($valid);
        if ($signature == $valid) {

            $json = json_decode(base64_decode($payload));
            return $this->return_array_result("select * from usuario where email='$json->email'");
        } else {
            return false;
        }
    }

    /**
     * Login no Sistema
     * @type type integer
     * @data type array
     * @return boolean
     */
    public function Login($type, $data) {

        switch ($type) {
            case 'jwt':

                return $this->OpenJWT($data['token']);
                break;
            case 'user':

                return $this->return_array_result("select nome,email,senha,id from usuario where email='{$data['email']}' and senha='{$data['senha']}'");
                break;
            default:
                break;
        }
    }

    /*
     * Função para Criar Id de Usuário
     */
    public function CreateId() {
        $dt = date('ymd');
        $sql = "select mid(id,7,10),id from usuario where mid(id,1,6)='$dt' order by id desc limit 1";
        return $dt . str_pad($this->return_result($sql) + 1, 6, 0, STR_PAD_LEFT);
    }

    
    /**
     * Função para pegar o BitCoin
     * @return type Object
     */
    public function getBitCoin() {
        $url = 'https://www.mercadobitcoin.net/api/BTC/ticker/';
        $dados = json_decode(file_get_contents($url));
        return $dados;
    }

    /**
     * Cria Usuario
     * @data type Array
     */
    public function CreateUser($data) {
        IF ($this->return_result("select id from usuario where email='{$data['email']}'") != '') {
            $this->savelog('Usuario já existe - ' . $data['email']);
            return 'Usuário já existe na base de dados';
        } else {
            $id = $this->CreateId();
            $sql = "INSERT into usuario (email,senha,id,cadastro) values ('{$data['email']}','{$data['email']}','{$data['senha']}','{$id}',current_timestamp)";
            $this->db_query($sql);

            /*
             * Cria saldo zero para usuario;
             */
            $this->db_query("insert into saldo (id) value ('$id')");
            $this->savelog('Criou Usuario - ' . $data['email']);
            $msg['text'] = 'Parabéns, ' . $data['email'] . ' <br><br>Bem vindo ao BitCoin, seu cadastro foi feito com sucesso!!!<br><br>Equipe BitCoin';
            $msg['subject'] = 'Res: Bem vindo ao Bitcoin';
            $to['name'] = $to['mail'] = $data['email'];
            $this->enviaMail($to, $msg);
        }
    }

    /**
     * Atualiza nome do Usuario
     * @data type Array
     */
    public function UpdateUser($data) {
        $data['email'] = addslashes($data['email']);
        $data['nome'] = addslashes($data['nome']);
        $this->savelog('Atualizou Usuario - ' . $data['email'] . ' -> ' . $data['nome']);
        $this->db_query("update usuario set nome='{$data['nome']}' where email='{$data['email']}'");
    }

    /**
     * Pegar dados do Usuario com E-mail
     * @email type String
     * @return type Array
     */
    public function GetUser($email) {
        $data = addslashes($email);
        return $this->return_result("select nome,email,senha from usuario where email='$data'");
    }

    /**
     * Função para Depósito
     * @param type $value
     * @return string
     */
    public function Deposit($value) {
        $value = addslashes($value);
        $id = $this->return_result("select max(id) from transacoes where user='{$_SESSION['usuario']['id']}' and ano=YEAR(CURDATE()) and data=CURDATE()") + 1;
        $sql = "insert into transacoes (id,user,ano,data,hora,deposito) values ($id,'{$_SESSION['usuario']['id']}',YEAR(CURDATE()),CURDATE(),CURTIME(),'$value')";
        $this->db_query($sql);
        $this->db_query("update saldo set valor=valor+$value where id='{$_SESSION['usuario']['id']}'");

        $to['name'] = $_SESSION['usuario']['nome'];
        $to['mail'] = $_SESSION['usuario']['email'];
        $msg['text'] = 'Parabéns, ' . $data['email'] . ' <br><br>Depósito no valor de value foi feito com sucesso!!!<br><br>Equipe BitCoin';
        $msg['subject'] = 'Depósito Bitcoin';
        $this->enviaMail($to, $msg);


        return 'Deposito efetuado com sucesso!!!';
    }


    /**
     * FUNCAO PARA ENVIO DE EMAIL 
     * @to[mail] type string
     * @to[name] type string
     * @msg[subject] type string
     * @msg[text] type string
     * @return boolean
     */
    public function enviaMail($to, $msg) {

        try {
            $this->SMTPDebug = 0;
            $this->isSMTP();
            $this->Host = 'smtp.gmail.com';
            $this->SMTPAuth = true;
            $this->Username = $this->mail;
            $this->Password = $this->mailPassword;
            $this->SMTPSecure = 'tls';
            $this->Port = 587;
            $this->setFrom($this->mail, $this->mailName);
            $this->addAddress($to['mail'], $to['name']);
            $this->isHTML(true);
            $this->Subject = $msg['subject'];
            $this->Body = $msg['text'];
            $this->send();
            $this->savelog('Message  sent' . $msg['text']);
            return true;
        } catch (Exception $e) {
            $this->savelog('Message could not be sent. Mailer Error: ', $this->ErrorInfo);
            return false;
        }
    }

    public function date_dbbr($dt) {
        if ($dt != '') {
            $d = explode('-', $dt);
            return "$d[2]/$d[1]/$d[0]";
        }
    }

    public function date_brdb($dt) {
        if ($dt != '') {
            $d = explode('/', $dt);
            if (strlen($d[2]) < 3) {
                $d[2] = '20' . $d[2];
            }
            return "$d[2]-$d[1]-$d[0]";
        } else {
            return "0000-00-00";
        }
    }

    public function n_View($number = 0, $decimais = 2) {
        return number_format($number, $ncasas, ',', '.');
    }

}

/**
 * Função para mascarar informação
 * @param type $k
 * @return type string
 */
function fm($k) {
    return base64_encode(base64_encode(base64_encode(date('d') . '#' . $k)));
}

/**
 * Função para desmascarar informação
 * @param type $k
 * @return type
 */
function dfm($k) {
    $x = explode('#', base64_decode(base64_decode(base64_decode($k))));
    return $x[1];
}

/* * '
 * FUNÇÃO PARA DEBUG
 */

function pre($k) {
    echo '<pre>';
    print_r($k);
    echo '</pre>';
}
