<?php

class GeneralModel {

    private static $db = null;
    protected $config = array();
    
    public function __construct($config) {
        $this->config = $config;
    }
    
    protected function getMysqli() {
        if(GeneralModel::$db == null) {
            //Check that all the required fields are set and load them
            $db_host;
            $db_username;
            $db_password;
            $db_database;
            
            if(isset($this->config["db"])) {
                $dbconf = $this->config["db"];
                if(isset($dbconf['host']) &&
                        isset($dbconf['username']) &&
                        isset($dbconf['password']) &&
                        isset($dbconf['database'])) {
                        
                    $db_host = $dbconf['host'];
                    $db_username = $dbconf['username'];
                    $db_password = $dbconf['password'];
                    $db_database = $dbconf['database'];
                }
                else throw new Exception("Tried to connect to database with "+
                        "incomplete config file. You must specify all of the "+
                        "following properties: host, username, password, and "+
                        "database name.");
            }
            
            GeneralModel::$db = new mysqli($db_host,$db_username,$db_password,$db_database);
        }
        return GeneralModel::$db;
    }
    
    public function setUp() {}

}