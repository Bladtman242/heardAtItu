<?php

class Path {
    public static $BACKEND = "../backend";
    public static $PUBLIC = "../public_html";
    public static $VIEWS = "../backend/views";
    public static $COMPOSITE_VIEWS = "../backend/views/composite";
    public static $CONTROLLERS = "../backend/controllers";
    public static $MODELS = "../backend/models";
    public static $FRAMEWORK = "../backend/framework";
    public static $DBA = "../backend/framework/dba";
    public static $COMPONENTS = "../backend/components";
    public static $WIDGETS = "../backend/widgets";
    public static $LAYOUT_VIEW = "../backend/views/composite/_layout.php";
    public static $VENDOR = "../vendor";
    
    /**
     * Creates an URL for a given controller and args. The controller is required,
     * whereas the route and args are optional. The route may be either an array
     * (enumerated, not associative) of points on the route OR a single string, and
     * the args MUST be an array (of any kind, even mixed is ok).
     *
     * @param controller The controller to handle the request made when accessing this
     *          URL.
     * @param route The route to give to the controller.
     * @param args Any GET arguments supplied in the URL.
     */
    public static function MakeUrl($controller, $route = "", $args = null) {
        //Controlelr is required for URLs!
        if(!isset($controller)) throw new Exception("Attempted to MakeUrl without specifying a controller.");
        
        //Decide whether a proper args array is given
        $has_args = is_array($args) && !empty($args);
    
        if(is_array($route)) {
            //Build route
            $route = "";
            foreach($route as $r) {
                $route .= "/".$r;
            }
        }
        else if(!isset($route)) {
            //Return without a route
            return "/".$controller.($has_args ? "?".http_build_query($args) : "");
        }
        
        //Return full
        return "/".$controller.$route.($has_args ? "?".http_build_query($args) : "");
    }
    
    /**
     * Returns the parts of a route.
     */
    public static function ParseRoute($route) {
        //If first or last character is a "/", trim it.
        
        //Split into items
        return explode("/", $route);
    }
    
    public static function ParseQuery($query) {
        unset($query['controller']);
        unset($query['route']);
        return $query;
    }
    
    public static function GetViewPath($viewName, $composite = false) {
        return ($composite ? Path::$COMPOSITE_VIEWS : Path::$VIEWS)."/".$viewName.".php";
    }
}

?>