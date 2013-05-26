<?php
session_start();
require_once "../backend/Tweet.php";
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Manager | Heard at ITU</title>
        <link href="bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,400italic,700,700italic' rel='stylesheet' type='text/css'>
        <style type="text/css">
            html {
                text-align: center;
            }
            body {
                font-family: 'Open Sans';
                display: inline-block;
                width: 415px;
            }
            header {
                margin-top: 20px;
                margin-bottom: 60px;
            }
            header h1 {
                font-family: 'Open Sans';
                font-weight: 800;
                color: rgb(35,71,170);
                line-height: 80%;
                font-size: 8em;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>mngr</h1>
        </header>
        <?php
            $authorized = false;
            $pwd = trim(file_get_contents("../twitter-pwd"));
            if($_SESSION['pwd'] == $pwd) {
                $authorized = true;
            }
            else if($_SERVER['REQUEST_METHOD'] == "POST") {
                if($_POST['pwd'] == $pwd) {
                    $authorized = true;
                    $_SESSION['pwd'] = $_POST['pwd'];
                }
                else echo "wrong pw";
            }
            if($authorized) {

                if(isset($_GET['deny'])) {
                    if(Tweet::Get($_GET['deny'])->deny()) {
                        echo '<p>Succesfully denied tweet.</p>';
                    }
                    else {
                        echo '<p>Failed to deny tweet.</p>';
                    }
                }
                else if(isset($_GET['approve'])) {
                    if(Tweet::Get($_GET['approve'])->approve()) {
                        echo '<p>Succesfully approved tweet.</p>';
                    }
                    else {
                        echo '<p>Failed to approve tweet.</p>';
                    }
                }

                $pending_tweets = Tweet::GetAll(Tweet::$STATE_PENDING);
                if(empty($pending_tweets) > 0) echo "No pending tweets.";
                foreach($pending_tweets as $t) {
        ?>
                <div>
                    <?php echo $t->content; ?>
                    <a href="?deny=<?php echo $t->getId(); ?>">Deny</a> |
                    <a href="?approve=<?php echo $t->getId(); ?>">Approve</a>
                </div>
        <?php }} else { ?>
        <form method="post">
            <input type="password" name="pwd"> <button>Authorize</button>
        </form>
        <?php } ?>
    </body>
</html>
