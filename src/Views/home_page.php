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

            <button onclick='registerServiceWorker(this)'>Save device</button>
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