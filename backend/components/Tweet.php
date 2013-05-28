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
    }

    public function deny() {
        $this->twitterModel->deny($this);
    }

    public function send() {
        $this->twitterModel->send($this);
    }

    private function saveState() {
        $this->twitterModel->saveState($this);
    }
}
?>
