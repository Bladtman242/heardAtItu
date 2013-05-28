<?php

class TwitterModel extends GeneralModel {
    public static $STATE_ANY = 'any';
    public static $STATE_PENDING = 'pending';
    public static $STATE_APPROVED = 'approved';
    public static $STATE_DENIED = 'denied';
    public static $STATE_SENT = 'sent';
    
    private $consumerKey;
    private $consumerSecret;
    private $OAuthToken;
    private $OAuthSecret;
    
    public function setUp() {
        parent::setUp();
        
        //Setup twitter info
        $configLoadFail = false;
        if(isset($this->config['twitter']) && isset($this->config['twitter']['OAuth'])) {
            $oauthconf = $this->config['twitter']['OAuth'];
            if(isset($oauthconf['consumerKey']) && isset($oauthconf['consumerSecret']) &&
                    isset($oauthconf['OAuthToken']) && isset($oauthconf['OAuthSecret'])) {
                
                $this->consumerKey = $oauthconf['consumerKey'];
                $this->consumerSecret = $oauthconf['consumerSecret'];
                $this->OAuthToken = $oauthconf['OAuthToken'];
                $this->OAuthSecret = $oauthconf['OAuthSecret'];
            }
        }
        else $configLoadFail = true;
        
        if($configLoadFail) throw new Exception("Attempted to create a twitter model with "+
                "an incomplete config. You MUST include the following information for Twitter "+
                "OAuth: consumerKey, consumerSecret, OAuthToken, and OAuthSecret.");
    }
    
    private function stateWhereClause($state) {
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

    public function loadMostRecent($state = 'any') {
        $mysqli = $this->getMysqli();

        $state_where = $this->stateWhereClause($state);

        $result = $mysqli->query("SELECT * FROM tweets $state_where ORDER BY id ASC LIMIT 1");
        if($result) {
            $t = $result->fetch_assoc();
            if($t) {
                return new Tweet($this,$t['id'],$t['content'],$t['state']);
            }
        }
        return null;
    }

    public function getAll($state = 'any') {
        $mysqli = $this->getMysqli();

        $state_where = Tweet::StateWhereClause($state);

        $result = $mysqli->query("SELECT * FROM tweets $state_where ORDER BY id ASC");

        $result_array = array();

         if($result) {
            while($t = $result->fetch_assoc()) {
                $result_array[] = new Tweet($this,$t['id'],$t['content'],$t['state']);
            }
            return $result_array;
        }
        return array();
    }

    public function get($id) {
        $mysqli = $this->getMysqli();

        $id = $mysqli->escape_string($id);

        $result = $mysqli->query("SELECT * FROM tweets WHERE id = '$id'");
        if($result) {
            $t = mysql_fetch_assoc($result);
            if($t) {
                return new Tweet($this,$t['id'],$t['content'],$t['state']);
            }
        }
        return null;
    }
    
    public function saveState($tweet) {
        $mysqli = $this->getMysqli();
        
        return $mysqli->real_query("UPDATE tweets SET state = '{$tweet->state}' WHERE id = '{$tweet->getId()}'");
    }
    
    public function enqueue($tweet_content) {
        $mysqli = $this->getMysqli();
        
        $tweet_content = $mysqli->escape_string($tweet_content);

        $count = $mysqli->query("SELECT count(*) AS c FROM tweets WHERE content = '$tweet_content'")->fetch_assoc();
        
        if ($count['c'] > 0) {
            return array(
                "success" => false,
                "status" => "This tweet is a duplicate of a previously submitted one");
        }

        if($mysqli->real_query("INSERT INTO tweets (content,state) VALUES ('$tweet_content','pending')")) {
            return array(
                "success" => true,
                "status" => "nothing to see here");
        }

        //catch-all
        return array(
            "success" => false,
            "status" => "Unknown error. Contact moderator.");
        }
    
}

?>