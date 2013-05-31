#!/usr/bin/php
<?php
error_reporting(E_ALL);

Path::$VENDOR = "vendor";

require_once "backend/framework/GeneralModel.php";
require_once "backend/models/TwitterModel.php";
require_once "backend/components/Tweet.php";
require_once "vendor/twitteroauth/twitteroauth.php";
require_once "vendor/twitteroauth/OAuth.php";

class ServiceUtils{
    private $model="";

    public function __construct() {
    $model = new TwitterModel(json_decode(file_get_contents("backend/config.json"),true));

    }

    public function postTweet() {
        $tw = $model->LoadMostRecent(TwitterModel::$STATE_APPROVED);
        if($tw != null) {
            if($tw->send()) {
                echo "Sent tweet #{$tw->getId()}:\n{$tw->content}\n";
            }
            else echo "Failed to send tweet #{$tw->getId()}:\n{$tw->content}\n";
        }
        else echo "No availible tweets\n";
    }
}

$su = new ServiceUtils();
$su->postTweet();
?>
