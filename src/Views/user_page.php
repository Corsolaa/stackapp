<?php

use StackSite\UserManagement\Factories\UserControllerFactory;

$user = (UserControllerFactory::create())->getUserBySessionToken();

?>

<h1>welcome to the user page 🥳</h1>

<p style="font-style: italic">This is your user details that your logged in with</p>

<p>user_id: <?php echo $user->getId() ?></p>
<p>email: <?php echo $user->getEmail() ?></p>
<p>username: <?php echo $user->getUsername() ?></p>
<p>password: ********</p>
<p>confirmed: <?php echo (int)$user->getConfirmed(); ?></p>
<p>created_at: <?php echo $user->getCreatedAt() ?></p>
<p>modified_at: <?php echo $user->getModifiedAt() ?></p>

<br><br>

<a href="https://app.stacksats.ai/user?logout">logout</a>