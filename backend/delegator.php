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

//Autoload
function __autoload($class) {
    echo "Autoloading ".$class;
    include "/controllers/".$class.".php";
}

function getEvaluatedFile($file) {
    ob_start();
    ob_implicit_flush(false);
    
    $content = "hello, world";
    require $file;
    
    return ob_get_clean();
}

$file = getEvaluatedFile(Path::$VIEWS."/composite/layout.php");

echo $file;

// $config = json_decode(file_get_contents(Path::$BACKEND."/config.json"));

// $controller_name = $config->defaultController."controller";

// $controller = new $controller_name;
// $controller->setUp();
// $controller->render();

?>