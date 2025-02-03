<div class="user_login form">
    <label for="email">
        email:
        <span>
            <input type="text" id="login_email" value="bruno.bouwman4@gmail.com">
        </span>
    </label>

    <label for="password">
        password:
        <span class="password">
            <input type="password" id="login_password" value="brunoiscool">
            <span class="password_eye" onclick="password_eye(this)">👁️</span>
        </span>
    </label>

    <p onclick="request_password_reset(this)">reset password?</p>

    <button class="hover_grow" onclick="login_user(this)">Login</button>
</div>

<div class="user_register form hidden">
    <label for="email">
        email:
        <span>
            <input type="text" id="register_email" value="bruno.bouwman4@gmail.com">
        </span>
    </label>

    <label for="username">
        username:
        <span>
            <input type="text" id="register_username" value="corsolaa">
        </span>
    </label>

    <label for="password">
        password:
        <span class="password">
            <input type="password" id="register_password" value="brunoiscool">
            <span class="password_eye" onclick="password_eye(this)">👁️</span>
        </span>
    </label>

    <button class="hover_grow" onclick="register_user(this)">Register</button>
</div>
