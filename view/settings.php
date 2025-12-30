<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\repositories\UserRepository;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\PostRepository;

$user = new UserRepository();
$community = new CommunityRepository();
$post = new PostRepository();
$comment = new CommentRepository();
$session = new SessionService();

if(!$session->sessionExists("username")) {
    header("Location: ../index.php");
}

$userId = $session->getFromSession('user_id');

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
    
    <?php include __DIR__ . "/partials/notificationHtml.php" ?>
    <?php include __DIR__ . "/partials/menuHtml.php" ?>
        
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
    import {toggleNotification, toggleSearch } from "../script/tools.js";
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
