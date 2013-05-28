<?php

class IndexController extends GeneralController {
    
    public function setUp($route, $get_args, $post_args) {
        parent::setUp($route, $get_args, $post_args);
        
        $post = array("initiated" => false);
        //POST handling
        if($post_args != null && isset($post_args['tweet-content'])) {
            $post['initiated'] = true;
            $post['success'] = false;
            $post['message'] = "Failed to send tweet :(";
            
            if(strlen($post_args['tweet-content']) > 140) {
                $post['message'] = "The tweet you submitted was longer than 140 characters!";
            }
            else {
                //Load up the model
                $model = $this->setModel("TwitterModel");
                
                //Post a tweet
                $tmp = $model->enqueue($post_args['tweet-content']);
                $post['success'] = $tmp['success'];
                $post['message'] = $tmp['success'] ? "Posted tweet to system - thank you!" : $tmp['status'];
            }
        }
        
        //Passing data to view
        $this->setData(array("post" => $post));
        
        //Setting the view
        $this->setView("tweet");
    }
    
}

?>