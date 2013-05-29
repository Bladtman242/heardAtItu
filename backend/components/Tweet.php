<?php

class Tweet {

    private $id;
    private $twitterModel;
    public $content;
    public $state;

    public function __construct($twitterModel,$id,$content,$state) {
        $this->twitterModel = $twitterModel;
        $this->id = $id;
        $this->content = $content;
        $this->state = $state;
    }

    public function getId() {
        return $this->id;
    }

    public function approve() {
        return $this->twitterModel->approve($this);
    }

    public function deny() {
        return $this->twitterModel->deny($this);
    }

    public function send() {
        return $this->twitterModel->send($this);
    }
}
?>
