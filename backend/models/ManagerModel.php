<?php
class ManagerModel extends GeneralModel {

    public function setUp() {
        parent::setUp();
    }
    
    public function authenticate($username, $passwd) {
        $mysqli = $this->getMysqli();
	$res = $mysqli->query("SELECT password AS p FROM managers WHERE username = '$username'");
	$res = $res->fetch_assoc();
	if ($res){
	    echo "lars";
	    if($res['p'] == hash('sha256',hash('sha256',($username)).$passwd)){
	        echo "lars2";
		return true; 
	    }
	}
	echo "larsf";
        return false;
    }
}

?>
