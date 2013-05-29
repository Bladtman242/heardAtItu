<?php
if(empty($tweets)) {
    echo '<p>No pending tweets.</p>';
}
foreach($tweets as $t) {
    $label_type = "";
    $label_text = "?";
    switch($t->state) {
        case TwitterModel::$STATE_PENDING:
            $label_type = "label-warning";
            $label_text = "Pending";
            break;
        case TwitterModel::$STATE_DENIED:
            $label_type = "label-important";
            $label_text = "Denied";
            break;
        case TwitterModel::$STATE_APPROVED:
            $label_type = "label-success";
            $label_text = "Approved";
            break;
        case TwitterModel::$STATE_SENT:
            $label_type = "label-info";
            $label_text = "Sent";
            break;
    }
    
    if($tweet_state_changeable) {
        echo '<div class="pull-right btn-group" style="margin: 4px;">';
        echo '<a href="'.Path::MakeUrl("manager","",array("approve" => $t->getId())).'" class="btn btn-success btn-small">Approve</a> ';
        echo '<a href="'.Path::MakeUrl("manager","",array("deny" => $t->getId())).'" class="btn btn-danger btn-small">Deny</a></div>';
    }
    
    echo '<div class="well well-small"><span class="label '.$label_type.'">'.$label_text.'</span> '.$t->content.'';
    echo '</div>';
}
?>