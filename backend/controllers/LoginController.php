<?php

class LoginController extends GeneralController {

    public function setUp($route,$get_args,$post_args) {
        parent::setUp($route,$get_args,$post_args);
        
        Page::setTitle("auth_");
        Page::setHeader("auth_");
        
        if($post_args != null) {
            if($this->loadModel('ManagerModel')->authenticate($post_args['user'], $post_args['pwd'])) {
                session_start();
                $_SESSION['logged_in'] = true;
                
                //TODO: Better redirect-on-login taking route and get args into account!
                if(isset($get_args['goto'])) {
                    $this->redirect($get_args['goto']);
                } else {
                    $this->redirect('manager');
                }
            }
        }
    
        $this->setView("login");
    }

}

?>
