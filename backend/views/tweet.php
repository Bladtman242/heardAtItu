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