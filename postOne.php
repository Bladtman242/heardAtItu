<?php
require_once "backend/framework/Path.php";

Path::$VENDOR = "vendor";

require_once "backend/framework/GeneralModel.php";
require_once "backend/models/TwitterModel.php";
require_once "backend/components/Tweet.php";
require_once "vendor/twitteroauth/twitteroauth.php";
require_once "vendor/twitteroauth/OAuth.php";

$model = new TwitterModel(json_decode(file_get_contents("backend/config.json"),true));
$tw = $model->LoadMostRecent(TwitterModel::$STATE_APPROVED);
if($tw != null) {
    if($tw->send()) {
        echo "Sent tweet #{$tw->getId()}:\n{$tw->content}\n";
    }
    else echo "Failed to send tweet #{$tw->getId()}:\n{$tw->content}\n";
}
else echo "No availible tweets\n";
?>
