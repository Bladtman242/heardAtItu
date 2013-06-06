<?php

class IndexController extends GeneralController {
    
    public function setUp($route, $get_args, $post_args) {
        parent::setUp($route, $get_args, $post_args);
        
        Page::setHeader("Heard at ITU");
        
        $post = array("initiated" => false);
        //POST handling
        if($post_args != null && isset($post_args['tweet-content'])) {
            $post['initiated'] = true;
            $post['success'] = false;
            $post['message'] = "Failed to send tweet for an unknown reason :(";
            
            //Load up the model
            $model = $this->loadModel("TwitterModel");
            
            //Submit a tweet
            try {
                $post['success'] = $model->enqueue($post_args['tweet-content']);
                $post['message'] = "Succesfully submitted tweet!";
            }
            catch(InputException $exc) {
                $post['success'] = false;
                $post['message'] = $exc->getMessage();
            }
        }
        
        //Passing data to view
        $this->setData(array("post" => $post));
        
        //Setting the view
        $this->setView("tweet");
    }
    
}

?>