<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\models\User;
use Reddit\models\Community;
use Reddit\models\Image;
use Reddit\models\Post;
use Reddit\models\Like;
$session = new SessionService();
$time = new TimeService();
$community = new Community();
$image = new Image();
$post = new Post();
$user = new User();
$like = new Like();

if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$get = $_GET['comm_id'];
$communityId = intval($get);
$selectedCommunity = $community->getCommunity("id",$communityId);
$communityImage = $image->getCommunityImage($communityId);
$userId = $session->getFromSession("user_id");
$communityUserId = $selectedCommunity[0]["user_id"];
$communityPosts = $post->getPost("community_id",$communityId);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/community.css?v=<?php echo time(); ?>">
</head>

<body>
    
<div class="header-container">
    <a class="logo-container" href="../index.php">
        <img src="../images/logo.png" alt="Reddit Logo" class="reddit-logo">
    </a>
    
    <div class="search-container">
        <img src="../images/icons/magnifying-glass.png" alt="Search Icon" class="search-icon">
        <input type="text" placeholder="Search Reddit">
    </div>
    
    <div class="buttons-container">
        <a href="../view/createPost.php" class="create-post-btn" title="Create Post">
            <img class='plus-icon' src="../images/icons/plus.png">
            <p>Create</p>
        </a>
        <a class="notifications-container" href="">
            <img src="../images/icons/bell.png">
        </a>
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

<div class="body-container">
    <div class="banner" style="background-image: url('../images/community/banner.jpg');"></div>
    <div class="title-container">
        <div class="image-container">
            <img src="../images/community/<?=$communityImage["name"]?>">
        </div>
        <div class="name-container">
            <p><span>r/</span><?=$selectedCommunity[0]["name"]?></p>
        </div>
        
        <a href="createPost.php?comm_id=<?=$selectedCommunity[0]['id']?>" class="create-post-container">
            <img src="../images/icons/add.png">
            <p>Create Post</p>
        </a>

        <?php if($communityUserId == $userId): ?>
            <form action="../decisionMaker.php" method="post">
                <input type="hidden" name="delete-community" value="<?=$selectedCommunity[0]['id']?>">
                <button class="delete-container">
                     <img src="../images/icons/set.png">
                </button>
            </form>
        <?php endif; ?>
    </div>

    <div class="content-container">
    <main class="posts-grid">
        <?php if(!empty($communityPosts)): ?>
            <?php foreach($communityPosts as $postItem): ?>
                <?php $postUser = $user->getUserByAttribute("id",$postItem["user_id"]); ?>
               <div class="post-container">
                <div class="post-user-container">
                    <img src="../images/avatars/<?=$postUser['avatar']?>.webp">
                    <p><span>u/</span><?= $postUser["username"] ?></p>
                    <h3><?= $time->calculateTime($postItem["time"]); ?></h3>
                </div>
                <div class="post-content-container">
                  <h3><?= $postItem["title"] ?></h3>
                  <p><?= $postItem["text"] ?></p>  
                </div>
                <div class="post-button-container">
                  <div class="like-comment-btns">
                    <div class="like-btn">
                        <div class="up-btn">
                           <img src="../images/icons/arrow-up.png">
                        </div>
                        <p><?= $like->getLikeCount("post_id",$postItem["id"]) ?></p>
                        <div class="down-btn">
                           <img src="../images/icons/arrow-down.png">
                        </div>
                    </div>
                    <div class="comment-btn">
                        <img src="../images/icons/bubble.png">
                        <p>0</p>
                    </div>
                  </div>
                  <?php if($postItem["user_id"] == $userId): ?>
                  <div class="delete-btn">
                    <img src="../images/icons/set.png">
                  </div>
                  <?php endif; ?>
                </div>
               </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-posts">
                <img src="../images/logo-not-found.png">
                <h2>There is no posts in this community yet.</h2>
            </div>
        <?php endif; ?>
    </main>

    <aside class="community-info">
        <div class="info-box">
            <h3><span>r/</span><?=$selectedCommunity[0]["name"]?></h3>
            <p class="desc"><?=$selectedCommunity[0]["description"]?></p>

            <div class="created">
                <img src="../images/icons/cake.png">
                <p>Created: <?=$selectedCommunity[0]["time"]?></p>
            </div>
        </div>
    </aside>
</div>

</div>

<script type="module">
import { toggleMenu} from "../script/tools.js?v=<?php echo time(); ?>";

const menu = document.getElementById("userInfo");
const deleteBtn = document.querySelector('.delete-container');

menu.addEventListener('click',toggleMenu);
deleteBtn.addEventListener('click',()=>{
    if(confirm("Are you sure you want do delete this community"))
    {
        deleteBtn.disabled = false;
    }
});

</script>
</body>

</html>