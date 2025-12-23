<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\models\User;
use Reddit\models\Notification;
use Reddit\models\Post;
use Reddit\models\Comment;
use Reddit\models\Community;

$user = new User();
$community = new Community();
$post = new Post();
$comment = new Comment();
$notification = new Notification();
$session = new SessionService();

if(!$session->sessionExists("username")) {
    header("Location: ../index.php");
}

$id = $session->getFromSession('user_id');
$notifications = $notification->unreadNotifications($id);
$nottNumber = count($notifications);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/settings.css?v=<?php echo time(); ?>">
</head>

<body>

<div class="header-container">
    <a class="logo-container" href="../index.php">
        <img src="../images/logo.png" alt="Reddit Logo" class="reddit-logo">
    </a>

    <div class="search-container">
        <img src="../images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <input type="text" placeholder="Search Reddit" id="searchInput">
        <div class="search-results" id="searchResults"></div>
    </div>

    <div class="buttons-container">
        <a href="../view/createPost.php" class="create-post-btn" title="Create Post">
            <img class='plus-icon' src="../images/icons/plus.png">
            <p>Create</p>
        </a>
    <div class="notifications-container">
        <img src="../images/icons/bell.png">
        <div class="notification-number"><?= $nottNumber ?></div>
    </div>
    <div class="notification-grid" id="notificatioGrid">
        
        <?php if(empty($notifications)): ?>
        <p class="empty-notification">There is no new notifications</p>
        <?php else: ?>
        <?php foreach($notifications as $notificationItem): ?>
        <?php $senderInfo = $user->getUserByAttribute("id",$notificationItem["sender_id"]); ?>
        <?php if($notificationItem["seen"] == "false"): ?>
        <?php if($notificationItem["type"] == "like"): ?>
        <?php if(!empty($notificationItem["post_id"])): ?>
        <?php $notificationPost = $post->getPost("id",$notificationItem["post_id"]) ?>
        <a href="community.php?comm_id=<?= $notificationPost[0]["community_id"] ?>&nott_id=<?= $notificationItem["id"] ?>" 
        onclick="<?php $notification->changeSeenStatus($notificationItem["id"],"true") ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="../images/avatars/<?= $senderInfo["avatar"] ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo["username"] ?></span> liked your post 
            r/<span><?= $notificationPost[0]["title"] ?></span></p>
        </div>  
        </a>
        <?php else: ?>
        <?php $notificationComment = $comment->getComments("id",$notificationItem["comment_id"]) ?>
        <a href="comment.php?post_id=<?= $notificationComment[0]["post_id"] ?>&nott_id=<?= $notificationItem["id"] ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="../images/avatars/<?= $senderInfo["avatar"] ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo["username"] ?></span> liked your comment
            r/<span><?= $notificationComment[0]["text"] ?></span></p>
        </div>
        </a>
        <?php endif; ?>
        <?php elseif($notificationItem["type"] == "comment"): ?>
        <?php $notificationPost = $post->getPost("id",$notificationItem["post_id"]); ?>
        <a href="comment.php?post_id=<?= $notificationPost[0]["id"] ?>&nott_id=<?= $notificationItem["id"] ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="../images/avatars/<?= $senderInfo["avatar"] ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo["username"] ?></span> commented on your post
            r/<span><?= $notificationPost[0]["title"] ?></span></p>
        </div>
        </a>
        <?php elseif($notificationItem["type"] == "post"): ?>
        <?php $notificationCommunity = $community->getCommunity("id",$notificationItem["community_id"]); ?>
        <a href="community.php?comm_id=<?= $notificationCommunity[0]["id"] ?>&nott_id=<?= $notificationItem["id"] ?>" class="single-notification">
        <div class="sender-avatar">
           <img src="../images/avatars/<?= $senderInfo["avatar"] ?>.webp">
        </div>
        <div class="notification-body">
            <p>u/<span><?= $senderInfo["username"] ?></span> posted in your community
            r/<span><?= $notificationCommunity[0]["name"] ?></span></p>
        </div>
        </a>
        <?php else: ?>
        <?php endif; ?>
        <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
        <div class="user-info" id="userInfo">
            <div class="green-dot"></div>
            <img class="user-avatar" src="../images/avatars/<?= $session->getFromSession('avatar')?>.webp">
        </div>
        <div class="menu-container" id="userMenu">
            <a class="profile-container" href="profile.php">
                <div class="avatar-container">
                    <img class="user-avatar" src="../images/avatars/<?= $session->getFromSession('avatar')?>.webp">
                </div>
                <div class="info-container">
                    <h3>View Profile</h3>
                    <p>u/<?= $session->getFromSession("username") ?></p>
                </div>
            </a>
            <a class="edit-container" href="../view/editAvatar.php">
                <img src="../images/icons/shirt.png">
                <p>Edit Avatar</p>
</a>
            <a class="logout-container" href="../src/controllers/Logout.php">
                <img src="../images/icons/house-door.png">
                <p>Log Out</p>
            </a>
        </div>
    </div>
</div>

<div class="main-container">
    <div class="message-container">
      <p class="message"><?=$session->displayMessage()?></p>
    </div>
    <h1>Settings</h1>
    <div class="fields-container">
        <div class="form">
                <p>Username</p>
                <button class="edit-btn" data-target="username-form">Edit</button>
        </div>
        <form action="../decisionMaker.php" method="post" id="username-form">
            <p>New Username</p>
            <input type="text" name="username-update">
            <button>Submit</button>
        </form>
        <div class="form">
                <p>Email</p>
                <button class="edit-btn"  data-target="email-form">Edit</button>
        </div>
        <form action="../decisionMaker.php" method="post" id="email-form">
            <p>New Email</p>
            <input type="text" name="email-update">
            <button>Submit</button>
        </form>
        <div class="form">
                <p>Password</p>
                <button class="edit-btn"  data-target="password-form">Edit</button>
        </div>
        <form action="../decisionMaker.php" method="post" id="password-form">
            <p>OldPassword</p>
            <input type="text" name="old-password">
            <p>New Password</p>
            <input type="text" name="new-password">
            <p>Repeat Password</p>
            <input type="text" name="confirm-password">
            <button>Submit</button>
        </form>
        <div class="form">
                <p>Bio</p>
                <button class="edit-btn"  data-target="bio-form">Edit</button>
        </div>
        <form action="../decisionMaker.php" method="post" id="bio-form">
            <p>New Bio (<span class="letters">235</span> Letters)</p>
            <textarea name="bio-update" id="bioId" rows="4"
            placeholder="Enter your Bio"></textarea>
            <button>Submit</button>
        </form>
        <div class="form">
                <p>Avatar</p>
                <button class="edit-btn"  data-target="avatar-form">Edit</button>
        </div>
        <form action="../decisionMaker.php" method="post" id="avatar-form">
            <div class="avatar-grid" id="avatarGrid">
                <div class="image-wrapper" data-target="blue">
                <img src="../images/avatars/blue.webp">
                <input type="checkbox" name="avatar-update" id="blue" value="blue">
                </div>
                <div class="image-wrapper" data-target="green">
                    <img src="../images\avatars\green.webp">
                    <input type="checkbox" name="avatar-update" id="green" value="green">
                </div>
                <div class="image-wrapper" data-target="greenBlue">
                    <img src="../images\avatars\greenBlue.webp">
                    <input type="checkbox" name="avatar-update" id="greenBlue" value="greenBLue">
                </div>
                <div class="image-wrapper" data-target="lightBlue">
                    <img src="../images\avatars\lightBlue.webp">
                    <input type="checkbox" name="avatar-update" id="lightBlue" value="lightBlue">
                </div>
                <div class="image-wrapper" data-target="orange">
                    <img src="../images\avatars\orange.webp">
                    <input type="checkbox" name="avatar-update" id="orange" value="orange">
                </div>
                <div class="image-wrapper" data-target="pink">
                    <img src="../images\avatars\pink.webp">
                    <input type="checkbox" name="avatar-update" id="pink" value="pink">
                </div>
                <div class="image-wrapper" data-target="purple">
                    <img src="../images\avatars\purple.webp">
                    <input type="checkbox" name="avatar-update" id="purple" value="purple">
                </div>
                <div class="image-wrapper" data-target="yellow">
                    <img src="../images\avatars\yellow.webp">
                    <input type="checkbox" name="avatar-update" id="yellow" value="yellow">
                </div>
            </div>
            <button>Submit</button>
        </form>
    </div>
</div>

<script type="module">
    import { toggleMenu, toggleNotification, toggleSearch } from "../script/tools.js";
    const menu = document.getElementById("userInfo");
    const editBtn = document.querySelectorAll(".edit-btn");
    const bio = document.getElementById("bioId");
    const letters = document.querySelector(".letters");
    const images = document.querySelectorAll(".image-wrapper");
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');
    const searchEnter = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    let checkboxes = [];

    bellIcon.addEventListener('click',toggleNotification);
    menu.addEventListener('click', toggleMenu);

    searchEnter.addEventListener('input', () => {
        let search = searchEnter.value.trim();
        if(search.length >= 2)
        {
            toggleSearch();
        }

        fetch("../decisionMaker.php?general-search=" + search)
        .then(res => res.json())
        .then(data => {
            searchResults.innerHTML = "";
            data.forEach(result => {
                const div = document.createElement('div');
                const divImg = document.createElement('div');
                const divInfo = document.createElement('div');
                const img = document.createElement('img');
                const h3 = document.createElement('h3');
                const p = document.createElement('p');
                const span = document.createElement('span');

                div.className = "search-result-container";
                divImg.className = "search-image-container";
                divInfo.className = "search-info-container";

                if(result['type'] === "community"){
                    h3.innerHTML = "r/" + result['display_name'];
                    p.innerHTML = result['info'];
                    fetch("../decisionMaker.php?community-image=" + result['id'])
                    .then(res => res.json())
                    .then(image => {
                        if(!image || !image.name){
                            img.src = "../images/reddit.png";
                        }
                        else{
                            img.src = "../images/community/" + image['name'];
                        }
                    });
                    div.appendChild(divImg);
                    divImg.appendChild(img);
                    div.appendChild(divInfo);
                    divInfo.appendChild(h3);
                    divInfo.appendChild(p);
                    searchResults.appendChild(div);

                    div.addEventListener("click",()=>{
                        window.location.href = "../view/community.php?comm_id=" + result['id'];
                    });
                }

                if(result['type'] === "post"){
                    h3.innerHTML = "p/" + result['display_name'];
                    p.innerHTML = result['info'];
                    img.src = "../images/reddit.png";
                    
                    div.appendChild(divImg);
                    divImg.appendChild(img);
                    div.appendChild(divInfo);
                    divInfo.appendChild(h3);
                    divInfo.appendChild(p);
                    searchResults.appendChild(div);

                    div.addEventListener("click",()=>{
                        window.location.href = "../view/community.php?comm_id=" + result['picture'];
                    });
                }
                if(result['type'] === "user"){
                    h3.innerHTML = "u/" + result['display_name'];
                    img.src = "../images/avatars/" + result['picture'] + ".webp";

                    div.appendChild(divImg);
                    divImg.appendChild(img);
                    div.appendChild(divInfo);
                    divInfo.appendChild(h3);
                    searchResults.appendChild(div);
                }
            });
        
        });
    });

    images.forEach(image => {
        const avatarColor = image.getAttribute('data-target');
        const avatarCheckbox = document.getElementById(avatarColor);

        checkboxes.push(avatarCheckbox);
    });

    images.forEach(image => {
        image.addEventListener('click', ()=>{
            const avatarColor = image.getAttribute('data-target');
            const avatarCheckbox = document.getElementById(avatarColor);

            if(avatarCheckbox.checked)
            {
              checkboxes.forEach(cb => cb.checked = false);
            }
            else
            {
              checkboxes.forEach(cb => cb.checked = false);
              avatarCheckbox.checked = true;
            }
        });
    })

    editBtn.forEach(button => {
      button.addEventListener('click', ()=>{
        const targetFormId = button.getAttribute('data-target');
        const targetForm = document.getElementById(targetFormId);

        if (targetForm) {
                if (targetForm.style.display === "none" || targetForm.style.display === "") {
                    targetForm.style.display = "flex"; 
                    button.textContent = "Cancel"; 
                } else {
                    targetForm.style.display = "none"; 
                    button.textContent = "Edit";
                }
            }
        });
    });

    bio.addEventListener('keydown', ()=>{
        let maxLetters = 235;
        let used = bio.value.length;
        let remaining = maxLetters - used;
        letters.innerHTML = remaining;
    });
    
   bio.addEventListener('input', () => {
        const maxLetters = 235;
        if (bio.value.length > maxLetters) {
            bio.value = bio.value.slice(0, maxLetters);
        }
        letters.textContent = maxLetters - bio.value.length;
    });

</script>
</body>
</html>
