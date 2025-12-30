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
    <title>Edit Avatar</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/editAvatar.css?v=<?php echo time(); ?>">
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
<div class="banner"></div>
<div class="body-container">
    <div class="form-container">
        <form action="../decisionMaker.php" method="post">
            <img src="../images/avatars/<?= $session->getFromSession('avatar')?>.webp" class="selected-avatar">
            <input type="hidden" value="" class="form-input" name="avatar-update">
            <button type="submit">Change</button>
        </form>
    </div>

    <div class="avatar-grid">
        <div class="image-wrapper" data-target="blue">
            <img src="../images/avatars/blue.webp">
        </div>
        <div class="image-wrapper" data-target="green">
            <img src="../images\avatars\green.webp">
        </div>
        <div class="image-wrapper" data-target="greenBlue">
            <img src="../images\avatars\greenBlue.webp">
        </div>
        <div class="image-wrapper" data-target="lightBlue">
            <img src="../images\avatars\lightBlue.webp">
        </div>
        <div class="image-wrapper" data-target="orange">
            <img src="../images\avatars\orange.webp">
        </div>
        <div class="image-wrapper" data-target="pink">
            <img src="../images\avatars\pink.webp">
        </div>
        <div class="image-wrapper" data-target="purple">
            <img src="../images\avatars\purple.webp">
        </div>
        <div class="image-wrapper" data-target="yellow">
            <img src="../images\avatars\yellow.webp">
        </div>
    </div>
</div>

<script type="module">
    import {changeBanner, toggleNotification, toggleSearch } from "../script/tools.js?v=<?php echo time(); ?>";
    const avatarOptions = document.querySelectorAll(".image-wrapper");
    const avatarSelected = document.querySelector(".selected-avatar");
    const formInput = document.querySelector(".form-input");
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');
    const searchEnter = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    changeBanner('<?=$session->getFromSession('avatar')?>');

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

    avatarOptions.forEach(option => {
        const avatarColor = option.getAttribute('data-target');

        option.addEventListener('click',() => {
            avatarSelected.src = `../images/avatars/${avatarColor}.webp`;
            formInput.value = avatarColor;
            changeBanner(avatarColor);
        });
    });


</script>
</body>

</html>