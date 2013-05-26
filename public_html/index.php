<?php
$post = array("initiated" => false);
//POST handling
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['tweet-content'])) {
    require_once "../backend/Tweet.php";
    
    $post['initiated'] = true;
    $post['success'] = false;
    $post['message'] = "Failed to send tweet :(";
    
    if(strlen($_POST['tweet-content']) > 140) {
        $post['message'] = "The tweet you submitted was longer than 140 characters!";
    }
    else if(Tweet::Enqueue($_POST['tweet-content'])) {
        $post['success'] = true;
        $post['message'] = "Posted tweet to system - thank you!";
    }
}
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Heard at ITU</title>
        <link href="/vendor/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,800,400italic,700,700italic' rel='stylesheet' type='text/css'>
        <link href="/res/style.css" rel="stylesheet" type="text/css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="/res/tweet.js"></script>
    </head>
    <body>
        <header>
            <h1>Heard at ITU</h1>
        </header>
        <div id="content">
            <?php
                if($post['initiated']) {
                    if($post['success']) {
                        echo '<div class="alert alert-success"><strong>Success!</strong> '.$post['message'].'</div>';
                    } else {
                        echo '<div class="alert alert-error"><strong>Error!</strong> '.$post['message'].'</div>';
                    }
                }
            ?>
            <form id="tweet-form" method="post">
                <textarea id="tweet-text" placeholder="Lorem ipsum..." name="tweet-content"></textarea><br>
                <span id="characters-left">140</span>
                <button class="btn btn-primary" id="tweet-submit">Submit</button>
            </form>
            <p>
                Submit a tweet of something funny you heard, saw or otherwise experienced (we don't judge) at the IT-University of Copenhagen. The tweets will be posted to the Twitter account <a href="http://twitter.com/HeardAtItu" target="_blank">@HeardAtItu</a> - follow it for fun quotes from the university.
            </p>
            <p>
                When you have submitted a tweet, it will await the approval of a moderator before being posted on Twitter.
            </p>
        </div>
    </body>
</html>
