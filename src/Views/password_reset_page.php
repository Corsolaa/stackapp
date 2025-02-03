<?php

$token = $_GET['password_reset'];

?>

<div class="user_password_reset form">
    <label for="forgot_password">
        password:
        <span class="password">
            <input type="password" id="forgot_password" value="brunoiscool">
            <span class="password_eye" onclick="password_eye(this)">👁️</span>
        </span>
    </label>

    <input type="hidden" id="forgot_token" value="<?php echo $token ?>">

    <button class="hover_grow" onclick="password_reset(this)">Send mail</button>
</div>