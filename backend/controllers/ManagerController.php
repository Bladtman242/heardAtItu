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
        
        Page::setTitle("mngr");
        Page::setHeader("mngr");
        
        $model = $this->loadModel("TwitterModel");
        
        $action = array( "initiated" => false );
        
        if(isset($get_args['deny'])) {
            $tweet = $model->get($get_args['deny']);
            if($tweet != null) {
                try {
                    $tweet->deny();
                    $action['initiated'] = true;
                    $action['success'] = true;
                    $action['message'] = "Denied tweet - keeping the streets clean!";
                }
                catch(InputException $exc) {
                    $action['initiated'] = true;
                    $action['success'] = false;
                    $action['message'] = $exc->getMessage();
                }
            }
            else {
                $action['initiated'] = true;
                $action['success'] = false;
                $action['message'] = "No tweet exists with the selected id.";
            }
        }
        else if(isset($get_args['approve'])) {
            $tweet = $model->get($get_args['approve']);
            if($tweet != null) {
                try {
                    $tweet->approve();
                    $action['initiated'] = true;
                    $action['success'] = true;
                    $action['message'] = "Approved tweet! It should be up in... you know... some hours or something.";
                }
                catch(InputException $exc) {
                    $action['initiated'] = true;
                    $action['success'] = false;
                    $action['message'] = $exc->getMessage();
                }
            }
            else {
                $action['initiated'] = true;
                $action['success'] = false;
                $action['message'] = "No tweet exists with the selected id.";
            }
        }
        
        $view = "pending"; //default view
        if(isset($route) && $route != "" && isset($route[0]) && $route[0] != "") {
            $view = $route[0];
        }
        
        $tweets = array();
        $editTweets = false;
        
        switch($view) {
            case "pending":
                $tweets = $model->getAll(TwitterModel::$STATE_PENDING);
                $editTweets = true;
                break;
            case "denied":
                $tweets = $model->getAll(TwitterModel::$STATE_DENIED);
                break;
            case "approved":
                $tweets = $model->getAll(TwitterModel::$STATE_SENT);
                $tweets = array_merge($tweets,$model->getAll(TwitterModel::$STATE_APPROVED));
                break;
            default:
                $tweets = $model->getAll(TwitterModel::$STATE_ANY);
                break;
        }
        
        $this->setData(array("tweets" => $tweets, "filter" => $view, "editTweets" => $editTweets, "action" => $action));
        $this->setView("manager");
    }

}

?>
