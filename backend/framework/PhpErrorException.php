<?php

class PhpErrorException extends GeneralException {

    /** 
     * Worth noting: errcontext is ignored at the moment, as it is a lot of data, and generally irrelevant.
     */
    public function __construct($errno, $errstr, $errfile = null, $errline = null, $errcontext = null) {
        $str = $errstr;
        parent::__construct($str,$errno);
        
        $this->line = $errline;
        $this->file = $errfile;
    }

}

?>