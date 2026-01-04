<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use Reddit\services\SessionService;

$session = new SessionService();

?>

<div class="user-info" id="userInfo">
    <div class="green-dot"></div>
    <img class="user-avatar" src="/Reddit/images/avatars/<?= $session->getFromSession('avatar')?>.webp">
</div>
<div class="menu-container" id="userMenu">
    <a class="profile-container" href="profile.php">
    <div class="avatar-container">
        <img class="user-avatar" src="/Reddit/images/avatars/<?= $session->getFromSession('avatar')?>.webp">
    </div>
    <div class="info-container">
        <h3>View Profile</h3>
        <p>u/<?= $session->getFromSession("username") ?></p>
    </div>
    </a>
    <a class="edit-container" href="/Reddit/view/editAvatar.php">
        <img src="/Reddit/images/icons/shirt.png">
        <p>Edit Avatar</p>
    </a>
    <a class="logout-container" href="/Reddit/src/controllers/Logout.php">
        <img src="/Reddit/images/icons/house-door.png">
        <p>Log Out</p>
    </a>
</div>

<script type="module">
    import { toggleMenu } from "/Reddit/script/tools.js?v=<?php echo time(); ?>";
    const menu = document.getElementById("userInfo");
    menu.addEventListener('click',toggleMenu);
    console.log("User JS Loaded");
</script>