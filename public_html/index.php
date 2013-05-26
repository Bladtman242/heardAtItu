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
    else {
        $tmp = Tweet::Enqueue($_POST['tweet-content']);
        $post['success'] = $tmp['success'];
        $post['message'] = $tmp['success'] ? "Posted tweet to system - thank you!" : $tmp['status'];
    }
}
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Heard at ITU</title>
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
            #content {
                text-align: left;
            }
            #tweet-form {
                width: 400px;
                display: inline-block;
                text-align: right;
            }
            #tweet-text {
                resize: none;
                width: 100%;
                height: 100px;
            }
            #tweet-submit {
                margin-right: -14px;
            }
            #characters-left {
                margin-right: 15px;
                color: #888;
            }
            p {
                text-align: left;
            }
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                var $tweetText = $("#tweet-text");
                var $charsLeft = $("#characters-left");
                var $tweetSubmit = $("#tweet-submit");
                var charFaultColor = "rgb(200,0,0)";
                var charNormalColor = $charsLeft.css("color");

                var updateCharsLeft = function() {
                    var chLeft = 140 - $tweetText.val().length;
                    $charsLeft.text(chLeft);

                    if(chLeft < 0) {
                        $charsLeft.css("color",charFaultColor);
                        $tweetSubmit.attr("disabled","disabled");
                    }
                    else {
                        $charsLeft.css("color",charNormalColor);
                        $tweetSubmit.attr("disabled",false);
                    }
                };

                $tweetText.keypress(updateCharsLeft);
                $tweetText.keyup(updateCharsLeft);
            });
        </script>
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
