<?php

class ManagerModel extends GeneralModel {

    public function setUp() {
        parent::setUp();
    }
    
    private function stateWhereClause($username, $passwd) {
        $mysqli = $this->getMysqli();
	$res = $mysqli->query("SELECT password as p FROM managers WHERE username = $username");
	if ($res){
	    if($res['p'] == sha256(sha256($username).passwd){
		return true; 
	    }
	}
        return false;
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

        $state_where = $this->stateWhereClause($state);

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
            $t = $result->fetch_assoc();
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
    
    public function approve($tweet) {
        switch($tweet->state) {
            case(TwitterModel::$STATE_PENDING):
                $tweet->state = TwitterModel::$STATE_APPROVED;
                return $this->saveState($tweet);
            default:
                return false;
        }
    }
    
    public function deny($tweet) {
        switch($tweet->state) {
            case(TwitterModel::$STATE_PENDING):
                $tweet->state = TwitterModel::$STATE_DENIED;
                return $this->saveState($tweet);
            default:
                return false;
        }
    }
    
    public function send($tweet) {
        switch($tweet->state) {
            case(TwitterModel::$STATE_APPROVED):
                //TODO: Make these autoloadable!
                require_once Path::$VENDOR."/twitteroauth/twitteroauth.php";
                require_once Path::$VENDOR."/twitteroauth/OAuth.php";
                
                $t = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $this->OAuthToken, $this->OAuthSecret); 
                
                $t->post('statuses/update',
                    array('status' => "$tweet->content"));
                 
                $tweet->state = Tweet::$STATE_SENT;
                return $this->saveState($tweet);
            default:
                return false;
        }
    }
    
}

?>
