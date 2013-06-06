<?php

//Setup error handling
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../backend/framework/Path.php";

//Autoload
function __autoload($class) {
    $file = "/".$class.".php";
    
    //TODO: Contains check+include: Widget.
    
    //Could it be a controller?
    if(strstr($class,"Controller")) {
        if(is_file(Path::$CONTROLLERS.$file)) {
            include Path::$CONTROLLERS.$file;
            return;
        }
    }
    
    if(strstr($class,"Model")) {
        if(is_file(Path::$MODELS.$file)) {
            include Path::$MODELS.$file;
            return;
        }
    }
    
    if(is_file(Path::$COMPONENTS.$file)) {
        include Path::$COMPONENTS.$file;
        return;
    }
    
    if(is_file(Path::$FRAMEWORK.$file)) {
        include Path::$FRAMEWORK.$file;
        return;
    }
    
    //TODO: As a last resort, try the vendor folder (somehow search through this).
}

/**
 * Primary class of the framework, delegating all tasks to the relevant places.
 */
class Delegator extends GeneralController {

    private $error_controller_name = "error";
    private $error_controller_class = "ErrorController";

    private $controller;

    /**
     * Argumentless construction: starts without the config loaded.
     */
    public function __construct() {
        parent::__construct(array());
    }
    
    public function pre() {
        $config = $this->loadConfig();
        
        Page::setSiteTitle("Heard at ITU");
        Page::setTitleSeparator(" | ");
        
        $controller_name = ucfirst(isset($_GET['controller']) ? $_GET['controller'] : $config['defaultController'])."Controller";

        //Verify existance of controller.
        if(class_exists($controller_name)) {
            $this->controller = new $controller_name($config);
            $this->controller->pre();
        }
        else if(class_exists($this->error_controller_class)) {
            $this->redirect($this->error_controller_name,"404");
        }
        else throw new Exception("Controller not found, and error controller missing!");
        
    }
    
    /**
     * Sets up anything that is required to set up in the setup phase - like controller setup.
     */
    public function setUp($route, $get_args, $post_args) {
        $this->controller->setUp($route,$get_args,$post_args);
    }
    
    /**
     * Delegate rendering to actual controller.
     */
    public function render() {
        $this->controller->render();
    }
    
    /**
     * Starts the system and delegates like a baws.
     */
    public function allEnginesGo() {
        $this->pre();
        $this->setUp(Path::ParseRoute(isset($_GET['route']) ? $_GET['route'] : ""), Path::ParseQuery($_GET), $_POST);
        $this->render();
    }
    
    public function loadConfig() {
        return json_decode(file_get_contents(Path::$BACKEND."/config.json"), true);
    }

}

?>