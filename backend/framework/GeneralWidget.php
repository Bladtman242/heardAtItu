<?php

class GeneralWidget {

    public static function makeWidget($view,$data) {
        $viewFile = Path::GetViewPath($view);
        echo GeneralController::renderFile($viewFile,null,$data);
    }

}

?>