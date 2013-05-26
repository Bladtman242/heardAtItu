<?php

class Tweet {

    public static $STATE_ANY = 'any';
    public static $STATE_PENDING = 'pending';
    public static $STATE_APPROVED = 'approved';
    public static $STATE_DENIED = 'denied';
    public static $STATE_SENT = 'sent';

    private static $consumerKey = 'HezTsfwVyKtvjUmaLVzQZA';
    private static $consumerSecret = 'FN82ceIuUcjcb6SN3kiy1HBksHg08uoA9e6DA6UpOPY';
    private static $OAuthToken = '1448708144-LHvbDp34zZLmoMz8lknw0k7xJnrz7WoqTvUQfzz';
    private static $OAuthSecret = 'a6F2PqBfqGe8ZN8eBp7XW0KBnTTTWKeJ6hUjVwA';

    private static $dbconnection = false;

    private $id;

    private function __construct($id,$content,$state) {
        $this->id = $id;
        $this->content = $content;
        $this->state = $state;
    }

    public function getId() {
        return $this->id;
    }

    private static function StateWhereClause($state) {
        $state_where = "";
        switch($state) {
            case Tweet::$STATE_ANY:
                break;
            default:
                $state_where = "WHERE state = '$state'";
                break;
        }
        return $state_where;
    }

    public static function LoadMostRecent($state = 'any') {
        //TODO: Maybe use mysqli instead of mysql?
        $dbc = Tweet::GetDbConnection();

        $state_where = Tweet::StateWhereClause($state);

        $result = mysql_query("SELECT * FROM tweets $state_where ORDER BY id ASC LIMIT 1", $dbc);
        if($result) {
            $tweet_data = mysql_fetch_assoc($result);
            if($tweet_data) {
                return new Tweet($tweet_data['id'],$tweet_data['content'],$tweet_data['state']);
            }
        }
        return null;
    }

    public static function GetAll($state = 'any') {
        $dbc = Tweet::GetDbConnection();

        $state_where = Tweet::StateWhereClause($state);

        $result = mysql_query("SELECT * FROM tweets $state_where ORDER BY id ASC", $dbc);

        $result_array = array();

         if($result) {
            while($t = mysql_fetch_assoc($result)) {
                $result_array[] = new Tweet($t['id'],$t['content'],$t['state']);
            }
            return $result_array;
        }
        return array();
    }

    public static function Get($id) {
        $dbc = Tweet::GetDbConnection();

        $id = mysql_real_escape_string($id);

        $result = mysql_query("SELECT * FROM tweets WHERE id = '$id'",$dbc);
        if($result) {
            $t = mysql_fetch_assoc($result);
            if($t) {
                return new Tweet($t['id'],$t['content'],$t['state']);
            }
        }
        return null;
    }

    public function approve() {
        switch($this->state) {
            case(Tweet::$STATE_PENDING):
                $this->state = Tweet::$STATE_APPROVED;
                return $this->saveState();
                break ;
            default:
                return false;
                break;
        }
    }

    public function deny() {
        switch($this->state) {
            case(Tweet::$STATE_PENDING):
                $this->state = Tweet::$STATE_DENIED;
                return $this->saveState();
                break;
            default:
                return false;
                break;
        }
    }

    public function send() {
        switch($this->state) {
            case(Tweet::$STATE_APPROVED):
                $t = new TwitterOAuth(Tweet::$consumerKey, Tweet::$consumerSecret, Tweet::$OAuthToken, Tweet::$OAuthSecret); 
                
                $t->post('statuses/update',
                    array('status' => "$this->content"));
                 
                $this->state = Tweet::$STATE_SENT;
                return $this->saveState();
                break;
            default:
                echo "failed to send tweet in code lol";
                return false;
                break;
        }
    }

    private static function GetDbConnection() {
        if(!Tweet::$dbconnection) {
            Tweet::$dbconnection = mysql_connect("localhost","heardAtItu","h3ard");
            mysql_select_db("heardAtItu",Tweet::$dbconnection);
        }
        return Tweet::$dbconnection;
    }

    private function saveState() {
        $dbc = Tweet::GetDbConnection();

        return mysql_query("UPDATE tweets SET state = '$this->state' WHERE id = '$this->id'",$dbc);
    }

    public static function Enqueue($tweet_content) {
        $dbc = Tweet::GetDbConnection();
        
        $tweet_content = mysql_real_escape_string($tweet_content);

        if(mysql_query("INSERT INTO tweets (content,state) VALUES ('$tweet_content','pending')", $dbc)) {
            return true;
        }

        return false;
    }

}

?>
