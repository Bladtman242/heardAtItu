<?php

class Tweet {

    private $id;
    private $twitterModel;
    public $content;
    public $state;

    private function __construct($twitterModel,$id,$content,$state) {
        $this->twitterModel = $twitterModel;
        $this->id = $id;
        $this->content = $content;
        $this->state = $state;
    }

    public function getId() {
        return $this->id;
    }

    public function approve() {
        $this->twitterModel->approve($this);
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
        $this->twitterModel->deny($this);
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
        $this->twitterModel->send($this);
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

    private function saveState() {
        $this->twitterModel->saveState($this);
    }
}
?>
