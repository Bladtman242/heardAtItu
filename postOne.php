<?php
error_reporting(E_ALL);
require_once "backend/components/Tweet.php";
require_once "vendor/twitteroauth/twitteroauth.php";
require_once "vendor/twitteroauth/OAuth.php";

$tw = Tweet::LoadMostRecent(Tweet::$STATE_APPROVED);
if($tw != null) {
    if($tw->send()) {
        echo "Sent tweet #{$tw->getId()}:\n{$tw->content}\n";
    }
    else echo "Failed to send tweet #{$tw->getId()}:\n{$tw->content}\n";
}
else echo "No availible tweets\n";
?>
