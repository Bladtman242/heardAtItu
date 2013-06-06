<?php

class ErrorController extends GeneralController {

    public function setUp($route, $get_args, $post_args) {
        parent::setUp($route, $get_args, $post_args);
        
        //Get error code (default 500)
        $error_code = 500;
        if(isset($route) && $route != "" && isset($route[0]) && $route[0] != "") {
            $error_code = $route[0];
        }
        
        Page::setHeader($error_code);
        Page::setTitle("Error ".$error_code);
        
        switch($error_code) {
            case "404":
                $this->setView("error/404");
                header("HTTP/1.1 404 Not Found");
                break;
            case "500":
                $this->setView("error/500");
                header("HTTP/1.1 500 Internal Server Error");
                break;
            default:
                $this->setView("error/default");
                header("HTTP/1.1 500 Odd Error");
                break;
        }
    }

}

?>