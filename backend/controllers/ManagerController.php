<?php

class ManagerController extends GeneralController {

    //TODO: Have several views (pending, denied, appoved+sent).

    public function pre() {
        session_start();
        
        //If not logged in
        //TODO: Proper validation with user system
        if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            $this->redirect("login",null,array("goto"=>"manager"));
        }
    }

    public function setUp($route, $get_args, $post_args) {
        parent::setUp($route, $get_args, $post_args);
        
        $model = $this->loadModel("TwitterModel");
        
        $action = array( "initiated" => false );
        
        if(isset($get_args['deny'])) {
            if($model->get($get_args['deny'])->deny()) {
                $action['initiated'] = true;
                $action['success'] = true;
                $action['message'] = "Denied tweet - keeping the streets clean!";
            }
            else {
                $action['initiated'] = true;
                $action['success'] = false;
                $action['message'] = "Something went wrong while denying the tweet... :(";
            }
        }
        else if(isset($get_args['approve'])) {
            if($model->get($get_args['approve'])->approve()) {
                $action['initiated'] = true;
                $action['success'] = true;
                $action['message'] = "Approved tweet! It should be up in... you know... some hours or something.";
            }
            else {
                $action['initiated'] = true;
                $action['success'] = false;
                $action['message'] = "Something went wrong while approved the tweet... :(";
            }
        }
        
        $view = TwitterModel::$STATE_PENDING;
        // if(isset($route[0])) {
        //  //depending on route, set different state filter.
        // }
        
        $this->setData(array("tweets" => $model->getAll(TwitterModel::$STATE_PENDING), "action" => $action));
        $this->setView("manager");
    }

}

?>