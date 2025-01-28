<?php

use StackSite\PushSubscriptions\PushSubscriptionsPersistence;
use StackSite\UserManagement\UserPersistence;

$notifications   = PushSubscriptionsPersistence::fetchAll();
$userPersistence = new UserPersistence();
$users           = $userPersistence->fetchAll();
?>

<div class="section">
    <h1>Welcome to app.stacksats.ai!</h1>
</div>

<div class="section">
    <div class="inner">
        <h2>User center</h2>

        <div class="user_register form">
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

        <div class="login form">
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

            <button class="hover_grow" onclick="login_user(this)">Login</button>
        </div>

        <br>

        <table>
            <tr>
                <th>id</th>
                <th>username</th>
                <th>email</th>
                <th>password</th>
                <th>confirmed</th>
                <th>created_at</th>
                <th>modified_at</th>
            </tr>

            <?php
            foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo $user->getId() ?></td>
                    <td><?php echo $user->getUsername() ?></td>
                    <td><?php echo $user->getEmail() ?></td>
                    <td>********</td>
                    <td><?php echo $user->getConfirmed() ?></td>
                    <td><?php echo date("Y-m-d H:i:s", $user->getCreatedAt()) ?></td>
                    <td><?php echo date("Y-m-d H:i:s", $user->getModifiedAt()) ?></td>
                </tr>
            <?php } ?>


        </table>
    </div>
</div>

<div class="section">
    <div class="inner">
        <h2>Notification center</h2>

        <div class="notification_create">
            <label for="username">for user:</label>
            <select name="username" id="username">
                <?php
                foreach ($users as $user) { ?>
                    <option value="<?php echo $user->getId() ?>"><?php echo $user->getUsername() ?></option>
                <?php } ?>
            </select>

            <br>

            <label for="name">name of device:</label>
            <input type="text" id="name">

            <br>

            <button class="hover_grow" onclick='registerServiceWorker(this)'>Save device</button>
        </div>

        <br>

        <table>
            <tr>
                <th>id</th>
                <th>username</th>
                <th>device_name</th>
                <th>created_at</th>
                <th>options</th>
            </tr>


            <?php
            foreach ($notifications as $notification) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($notification->getId()) ?></td>
                    <td>corsolaa</td>
                    <td>Berno Phone</td>
                    <td><?php echo date("Y-m-d H:i:s", $notification->getCreatedAt()) ?></td>
                    <td style="width: 190px;">
                        <div class="options">
                            <div class="button send">🚀send</div>
                            <div class="button delete">⚠️delete</div>
                        </div>
                    </td>
                </tr>
            <?php } ?>


        </table>
    </div>
</div>

<div style='width: 100%; height: 100vh; display: flex; justify-content: center;align-items: center;flex-direction:
column;'>

    <div onclick='registerServiceWorker(this)'
         style='padding: 5px 10px; border: 1px solid black; cursor: pointer; border-radius: 12px'>Register push
        notification
    </div>
    <div onclick='openSendSiteNewTab();'
         style='margin-top: 15px;padding: 5px 10px; border: 1px solid black; cursor: pointer; border-radius: 12px'>Doe
        een push-up
    </div>
</div>