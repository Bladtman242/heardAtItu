<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../backend/framework/GeneralModel.php";
require_once "../backend/models/ManagerModel.php";

echo $_GET['u']." ".$_GET['p'];
ManagerModel->authenticate($_GET['u'],$_GET['p']);
?>
