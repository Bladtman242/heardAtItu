<?php

/**
 * Controllers should take care of handling all request data, and run the
 * appropriate files.
 */
class GeneralController {

    private $model = null;
    private $view = null;

    /**
     * Sets the model used.
     * @param model Should be a class that is compatible with the view set.
     *      this model is passed on the the view when rendering.
     */
    protected function setModel($model) {
        this->$model = $model;
    }
    
    /**
     * Sets the view used.
     * @param view Should be a string, which is the name of the view file
     *      (with .php excluded).
     */
    protected function setView($view) {
        this->$view = Path::$VIEWS."/".$view.".php";
    }

    /**
     * Renders the current view, while providing it with the model set as
     *      $model (if any was provided);
     */
    public function render() {
        $model = this->$model;
        include this->$view;
    }

}

?>