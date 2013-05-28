<header>
    <h1>mngr</h1>
</header>
<?php
    if($action['initiated']) {
        if($action['success']) {
            echo '<div class="alert alert-success"><strong>Success!</strong> '.$action['message'].'</div>';
        } else {
            echo '<div class="alert alert-error"><strong>Error!</strong> '.$action['message'].'</div>';
        }
    }
    
    /** TWEETS **/
    if(empty($tweets)) {
        echo '<p>No pending tweets.</p>';
    }
    foreach($tweets as $t) {
        echo '<div class="well well-small"><p>'.$t->content.'</p><div class="tweet-interactions">';
        echo '<a href="'.Path::MakeUrl("manager","",array("approve" => $t->getId())).'">Approve</a> |';
        echo ' <a href="'.Path::MakeUrl("manager","",array("deny" => $t->getId())).'">Deny</a></div>';
        echo '</div>';
    }
?>
