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

    $tweetText.keydown(updateCharsLeft);
    $tweetText.keyup(updateCharsLeft);
});