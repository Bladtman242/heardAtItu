<?php

class LoginController extends GeneralController {

    public function setUp($route,$get_args,$post_args) {
        if($post_args != null) {
            //TODO: Proper login here.
            if($post_args['pwd'] == "trololo") {
                session_start();
                $_SESSION['logged_in'] = true;
                
                //TODO: Better redirect-on-login taking route and get args into account!
                if(isset($get_args['goto'])) {
                    $this->redirect($get_args['goto']);
                } else {
                    $this->redirect($this->config['defaultController']);
                }
            }
        }
    
        $this->setView("login");
    }

}

?>