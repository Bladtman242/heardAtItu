<?php
    if($auth_failed) echo '<div class="alert alert-error"><strong>Failure!</strong> Failed to authenticate login details!</div>';
?>
<form method="post" id="login-form">
    <input type="text" name="user" required placeholder="Username"><br>
    <input type="password" name="pwd" required placeholder="Password"><br>
    <button class="btn btn-primary">Authorize</button>
</form>
