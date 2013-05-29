<?php
class ManagerModel extends GeneralModel {

    public function setUp() {
        parent::setUp();
    }
    
    public static function authenticate($username, $passwd) {
        $mysqli = $this->getMysqli();
	$res = $mysqli->query("SELECT password as p FROM managers WHERE username = $username");
	if ($res){
	    if($res['p'] == sha256(sha256($username).passwd)){
		return true; 
	    }
	}
        return false;
    }
}

?>
