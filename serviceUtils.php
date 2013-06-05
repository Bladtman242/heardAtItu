#!/usr/bin/php
<?php
error_reporting(E_ALL);

require_once "backend/framework/Path.php";
Path::$VENDOR = "vendor";

require_once "backend/framework/GeneralModel.php";
require_once "backend/models/TwitterModel.php";
require_once "backend/components/Tweet.php";
require_once "vendor/twitteroauth/twitteroauth.php";
require_once "vendor/twitteroauth/OAuth.php";
 
$managers = "Sigurt <sigurt91@gmail.com>, Niels <niels.abildgaard@gmail.com>";
$mailSubject = "Pending tweets on heard.atItu.dk.";
$mailBody = "There are pending tweets on heard.atItu.dk";

class ServiceUtils{
    private $model;

    public function __construct() {
        $this->model = new TwitterModel(json_decode(file_get_contents("backend/config.json"),true));
        $this->model->setUp();
    }

    public function postTweet() {
        $tw = $this->model->loadMostRecent(TwitterModel::$STATE_APPROVED);
        if($tw != null) {
            if($tw->send()) {
                echo "Sent tweet #{$tw->getId()}:\n{$tw->content}\n";
            }
            else echo "Failed to send tweet #{$tw->getId()}:\n{$tw->content}\n";
        }
        else echo "No availible tweets\n";
    }

    public function mailManagers() {
        global $managers, $mailSubject, $mailBody;

        $tw = $this->model->getAll(TwitterModel::$STATE_PENDING);
        if(is_array($tw) && count($tw)>0){
            echo mail($managers, $mailSubject, $mailBody) ? "Mail sent to managers\n" : "Mailing error\n";
        } else {
            echo "no pending tweets, no mails sent";
        }
    }
}

$opt = getopt("pm");

$su = new ServiceUtils();

if (isset($opt['p'])) {
    $su->postTweet();
}

if (isset($opt['m'])) {
    $su->mailManagers();
}
?>
