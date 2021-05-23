<?php
class DbConn
{
    function __construct(){
        require_once("env.php");
        $dbname = "word_list";
        $dbuser = "$UntrackedDbUser";
        $dbpass = "$UntrackedDbPass";
        $host = "localhost";
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    }
    public function givePdoHandle() {
        return $this->pdo;
    }
}
