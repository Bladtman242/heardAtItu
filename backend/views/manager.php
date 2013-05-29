<ul class="nav nav-pills" id="tweet-filter">
    <li class="disabled"><a>Filter tweets:</a></li>
    <li<?php if($filter == "pending") echo ' class="active"'; ?>><a href="<?php echo Path::MakeUrl("manager","pending"); ?>">Pending</a></li>
    <li<?php if($filter == "denied") echo ' class="active"'; ?>><a href="<?php echo Path::MakeUrl("manager","denied"); ?>">Denied</a></li>
    <li<?php if($filter == "approved") echo ' class="active"'; ?>><a href="<?php echo Path::MakeUrl("manager","approved"); ?>">Approved+Sent</a></li>
</ul>

<?php
    if($action['initiated']) {
        if($action['success']) {
            echo '<div class="alert alert-success"><strong>Success!</strong> '.$action['message'].'</div>';
        } else {
            echo '<div class="alert alert-error"><strong>Error!</strong> '.$action['message'].'</div>';
        }
    }
    
    GeneralWidget::makeWidget("_tweetList",array("tweets" => $tweets, "tweet_state_changeable" => $editTweets));
?>