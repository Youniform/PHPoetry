<?php

# Fill in $dbuser $dbpass and "CREATE database word_list;" in your mysql cli, also then from project root
# $ pv word_list.sql.gz | gunzip | mysql -u user -p word_list
class DbConn
{
    function __construct(){
        $dbname = "word_list";
        $dbuser = "*****";
        $dbpass = "*****";
        $host = "localhost";
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    }
    public function givePdoHandle() {
        return $this->pdo;
    }
}
