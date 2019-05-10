<?php

class db{
    private $dbhost = 'localhost';
    private $dbuser = 'projectManager';
    private $dbpass = '123456';
    private $dbname = 'foldynatulbp_v1_2';

    public function connect(){
        $pgString = "pgsql:host=$this->dbhost;port=5432;dbname=$this->dbname;user=$this->dbuser;password=$this->dbpass";
        $dbConnection = new PDO($pgString);
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbConnection;
    }
}