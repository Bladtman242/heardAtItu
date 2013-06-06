<?php

/**
 * Controllers should take care of handling all request data, and run the
 * appropriate files.
 */
class GeneralController {

    private $view = null;
    private $data = array();
    protected $config = array();
    
    public function __construct($config) {
        $this->config = $config;
    }
    
    /**
     * Actions to be run before starting to run any actual code. Pre is run
     * before setup.
     */
    public function pre() {}
    
    /**
     * Start the controller's handling of this data.
     */
    public function setUp($route, $get_args, $post_args) {}

    /**
     * Loads a model to the controller.
     * @param model Should be a class that is compatible with the view set.
     *      This model is then usable in the rest of the controller. You may
     *      pass either an instantiated object or a string with the name of
     *      the model.
     * @returns The model that was just set.
     */
    protected function loadModel($model) {
        if(is_object($model)) {
            return $model;
        }
        else if(is_string($model)) {
            $model = new $model($this->config);
            return $model;
        }
        return false;
    }
    
    /**
     * Sets data to be used in the innermost view provided.
     */
    protected function setData($data) {
        $this->data = $data;
    }
    
    /**
     * Sets the view used.
     * @param view Should be either:
     *      - a string, which is the name of the view file (with .php excluded); or
     *      - an array (enumerated) with such strings. In this case, the views will
     *          be composited in such a way that the first is the outermost and the
     *          last is the outermost.
     */
    protected function setView($view) {
        $this->view = $view;
    }
    
    public static function redirectToUrl($url) {
        header("Location: ".$url);
        exit(0);
    }
    
    public static function redirect($controller, $route = "", $args = null) {
        GeneralController::redirectToUrl(Path::MakeUrl($controller,$route,$args));
    }
    
    public static function renderFile($file,$content = null,$data = null) {
        extract($data);
    
        ob_start();
        ob_implicit_flush(false);
        
        require $file;
        
        return ob_get_clean();
    }

    /**
     * Renders the current view, while providing it with the model set as
     *      $model (if any was provided);
     */
    public function render() {
        $content = null;
        if(is_array($this->view)) {
            $views = array_reverse($this->view);
            $content = $this->renderFile(Path::GetViewPath($views[0]), null, $this->data);
            for($i = 1; $i < sizeof($views); $i++) {
                $path = Path::GetViewPath($views[$i],true);
                if(is_file($path)) {
                    $content = $this->renderFile($path, $content, $this->data);
                }
                else throw new InputException("Composite view not found: '".$views[$i]."'");
            }
        }
        else {
            $content = $this->renderFile(Path::GetViewPath($this->view), null, $this->data);
        }
        
        //Finally render layout
        require Path::$LAYOUT_VIEW;
    }

}

?>