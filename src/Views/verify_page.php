<?php

use StackSite\UserManagement\Factories\UserControllerFactory;

$userController = UserControllerFactory::create();
$good           = $userController->verifyUser()->getSuccess();

?>

<h1>Verify Page</h1>

<p>the verification was:</p>

<?php
if ($good) { ?>
    <p style="color: #4ae3ad">✅ SUCCESS ✅</p>
<?php } else { ?>
    <p style="color: red">❌ failed ❌</p>
<?php } ?>

<p>You will be redirected to the homepage in <span id="countdown">5</span> second(s)...</p>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let countdownElement = document.getElementById("countdown");
        let countdown = 5;

        let interval = setInterval(function () {
            countdown--;
            countdownElement.textContent = countdown.toString();

            if (countdown <= 0) {
                clearInterval(interval);
                window.location.href = "/";
            }
        }, 1000);
    });
</script>