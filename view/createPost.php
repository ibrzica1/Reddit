<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\repositories\UserRepository;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\PostRepository;
use Reddit\repositories\ImageRepository;

$session = new SessionService();
$user = new UserRepository();
$community = new CommunityRepository();
$post = new PostRepository();
$comment = new CommentRepository();
$image = new ImageRepository();
$communityId = "";

if(!empty($_GET["comm_id"]))
{
   $communityId = $_GET["comm_id"]; 
   $selectedCommunity = $community->getCommunity("id",$communityId);
   $commImage = $image->getCommunityImage($communityId);
}


if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$userId = $session->getFromSession('user_id');


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/createPost.css?v=<?php echo time(); ?>">
</head>

<body>


<body>

<div class="header-container">
    <a class="logo-container" href="../index.php">
        <img src="../images/logo.png" alt="Reddit Logo" class="reddit-logo">
    </a>
<?php if(!empty($selectedCommunity)): ?>
    <div class="search-container">
        <img src="../images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <div class="user-search-container">
            <img src="../images/community/<?=$commImage->name?>">
            <p>r/<?= $selectedCommunity->name?></p>
        </div>
        <input type="text" placeholder="Search in r/<?= $selectedCommunity->name ?>" id="searchInput" data-comm_id="<?= $communityId ?>">
        <div class="search-results" id="searchResults"></div>
    </div>
    <?php else: ?>
    <div class="search-container">
        <img src="../images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <input type="text" placeholder="Search Reddit" id="searchInput">
        <div class="search-results" id="searchResults"></div>
    </div>   
    <?php endif; ?>
      <div class="buttons-container">
        <a href="../view/createPost.php" class="create-post-btn" title="Create Post">
            <img class='plus-icon' src="../images/icons/plus.png">
            <p>Create</p>
        </a>

    <?php include __DIR__ . "/partials/notificationHtml.php" ?>
    <?php include __DIR__ . "/partials/menuHtml.php" ?>
        
    </div>
  </div>

<div class="message-container">
    <p class="message"><?=$session->displayMessage()?></p>
</div>

<div>
   <h2>Create Post</h2>
</div>

<?php if(!empty($selectedCommunity)): ?>
<div class="community-container">
    <img src="../images/community/<?=$commImage->name?>">
    <p><span>r/</span><?= $selectedCommunity->name ?></p>
</div>
<?php endif; ?>
    
<div class="seek-container">
    <input type="text" name="community-search" id="search-input" placeholder="Search for community">
    <p id="search-display"></p>
</div>
    

<div class="options-container">
    <div class="text-option">Text</div>
    <div class="image-option">Image</div>
</div>

<div class="form-container">
    <form action="../decisionMaker.php" method="post" enctype="multipart/form-data">
        <input type="hidden" id="selectedCommunity" name="community" value="<?=$communityId?>">
        <div class="title-container">
            <input type="text" placeholder="Title" id="titleId" name="title">
            <p><span class="letters">300</span> Letters</p>
        </div>

        <div class="text-container">
            <textarea name="text" id="" placeholder="Body text (optional)"></textarea>
        </div>

        <div class="image-container">
            <input type="file" id="file-input" name="image[]" multiple>
        </div>
    <button type="submit">Post</button>
</form>
</div>

<script type="module">

import {toggleNotification} from "../script/tools.js?v=<?php echo time(); ?>";
import {postSearch} from "../script/search.js?v=<?php echo time(); ?>";
const commId = <?= $communityId ?>;
const textOption = document.querySelector('.text-option');
const imageOption = document.querySelector('.image-option');
const textContainer = document.querySelector('.text-container');
const imageContainer = document.querySelector('.image-container');
const title = document.getElementById('titleId');
const letters = document.querySelector(".letters");
const searchInput = document.getElementById("search-input");
const displayInput = document.getElementById("search-display");
const communitySelect = document.getElementById("selectedCommunity");
const communityContainer = document.querySelector(".community-container");
const seekContainer = document.querySelector(".seek-container");
const bellIcon = document.querySelector('.notifications-container');
const notificationNum = document.querySelector('.notification-number');
const isCommunitySelected = <?php echo json_encode(!empty($selectedCommunity)); ?>;

postSearch();

bellIcon.addEventListener('click',toggleNotification);

if (isCommunitySelected) {
    if (communityContainer) communityContainer.style.display = "flex";
    if (seekContainer) seekContainer.style.display = "none";
} else {
    if (communityContainer) communityContainer.style.display = "none";
    if (seekContainer) seekContainer.style.display = "flex";
}

searchInput.addEventListener("input",()=>{
  let search = searchInput.value.trim();

  if(search.length < 2)
  {
    displayInput.style.display = "none";
  }
  else
  {
    displayInput.style.display = "block";
  }

  fetch("../decisionMaker.php?community-search=" + search)
        .then(res => res.json())
        .then(data => {
            displayInput.innerHTML = "";
            data.forEach(community => {

                const communityId = community["id"];
                const div = document.createElement('div');
                const p = document.createElement('p');
                const span = document.createElement('span');
                span.innerHTML = "u/";
                p.innerHTML = community['name'];
                div.appendChild(span);
                div.appendChild(p);
                displayInput.appendChild(div);



                div.addEventListener("click",()=>{
                    window.location.href = "createPost.php?comm_id=" + communityId;
                });
            });
        });
});

if(communityContainer) {
    communityContainer.addEventListener("click",()=>{
        communityContainer.style.display = "none";
        if(seekContainer) {
        seekContainer.style.display = "flex";
    }
        if(searchInput) {
            searchInput.focus();
        }
});
}

title.addEventListener('keydown', ()=>{
    let maxLetters = 300;
    let used = title.value.length;
    let remaining = maxLetters - used;
    letters.innerHTML = remaining;
});
    
title.addEventListener('input', () => {
    const maxLetters = 300;
     if (title.value.length > maxLetters) {
        title.value = title.value.slice(0, maxLetters);
    }
    letters.textContent = maxLetters - title.value.length;
});

textOption.classList.add('active');
imageContainer.style.display = 'none';

textOption.addEventListener('click', () => {
  textOption.classList.add('active');
  imageOption.classList.remove('active');
  textContainer.style.display = 'block';
  imageContainer.style.display = 'none';
});

imageOption.addEventListener('click', () => {
  imageOption.classList.add('active');
  textOption.classList.remove('active');
  textContainer.style.display = 'none';
  imageContainer.style.display = 'block';
});

</script>
    
</body>

</html>