<?php

//Setup error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);


//Setup Path namespace
class Path {
    public static $BACKEND = "../backend";
    public static $PUBLIC = "../public_html";
    public static $VIEWS = "../backend/views";
}

$config = json_decode(file_get_contents(Path::$BACKEND."/config.json"));

?>