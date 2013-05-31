<?php
class ManagerModel extends GeneralModel {

    public function setUp() {
        parent::setUp();
    }
    
    /**
     * Authenticates log in information. Returns true if the user is valid.
     */
    public function authenticate($username, $passwd) {
        $mysqli = $this->getMysqli();
        $res = $mysqli->query("SELECT password AS p FROM managers WHERE username = '$username'");
        
        //If query was valid...
        if($res && $res = $res->fetch_assoc()) {
            //If result is non-empty...
            if($res && $res['p'] == hash('sha256',hash('sha256',($username)).$passwd)){
                return true; 
            }
        }
        return false;
    }
}
?>
