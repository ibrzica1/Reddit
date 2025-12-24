<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\models\User;
use Reddit\models\Community;
use Reddit\models\Image;
use Reddit\models\Post;
use Reddit\models\Like;
use Reddit\models\Comment;
use Reddit\models\Notification;

$session = new SessionService();
$time = new TimeService();
$community = new Community();
$image = new Image();
$post = new Post();
$comment = new Comment();
$user = new User();
$like = new Like();
$notification = new Notification();

if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$get = $_GET['comm_id'];
if(!empty($_GET['nott_id']))
{
    $notificationId = $_GET['nott_id'];
    if(!empty($notificationId)) $notification->changeSeenStatus($notificationId,"true");
}
$communityId = intval($get);
$selectedCommunity = $community->getCommunity("id",$communityId);
$communityImage = $image->getCommunityImage($communityId);
$userId = $session->getFromSession("user_id");
$communityUserId = $selectedCommunity[0]["user_id"];
$communityPosts = $post->getPost("community_id",$communityId);
$notifications = $notification->unreadNotifications($userId);
$nottNumber = count($notifications);

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
        <div class="user-search-container">
            <img src="../images/community/<?=$communityImage["name"]?>">
            <p>r/<?= $selectedCommunity[0]['name'] ?></p>
        </div>
        <input type="text" placeholder="Search in r/<?= $selectedCommunity[0]['name'] ?>" id="searchInput">
         <div class="search-results" id="searchResults"></div>
    </div>
    
    <div class="buttons-container">
        <a href="../view/createPost.php" class="create-post-btn" title="Create Post">
            <img class='plus-icon' src="../images/icons/plus.png">
            <p>Create</p>
        </a>
        <div class="notifications-container">
            <img src="../images/icons/bell.png">
        <?php if($nottNumber > 0): ?>
            <div class="notification-number"><?= $nottNumber ?></div>
        <?php endif; ?>
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
            <a href="notification.php" class="see-all-nott">see all notifications</a>
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
    <?php $postId = $postItem["id"]; ?>
    <?php $postLikes = $like->getLike("post_id",$postId,$postItem["user_id"]); ?>
    <?php $postLikeStatus = empty($postLikes["status"]) ? "neutral" : $postLikes["status"] ?>
    <?php $postImages = []; ?>
    <div class="post-container">
    <div class="post-user-container">
        <img src="../images/avatars/<?=$postUser['avatar']?>.webp">
        <p><span>u/</span><?= $postUser["username"] ?></p>
        <h3><?= $time->calculateTime($postItem["time"]); ?></h3>
    </div>
    <div class="post-content-container">
        <h3><?= $postItem["title"] ?></h3>
    <?php if(!empty($postItem["text"])): ?>
        <p><?= $postItem["text"] ?></p>
    <?php else: ?>
    <?php $postImages = $image->getUploadedImages("post_id",$postId); ?>
    <?php $imgCount = count($postImages); ?>
    <div class="image">
        <div class="left-arrow" id="leftArrow-<?= $postId ?>">
            <img src="../images/icons/arrowLeft.png">
        </div>
        <img src="../images/uploaded/<?= $postImages[0]["name"] ?>" id="imageDisplay-<?= $postId ?>">
        <div class="right-arrow" id="rightArrow-<?= $postId ?>">
            <img src="../images/icons/arrowRight.png">
        </div>
    </div>
    <?php endif; ?>  
    </div>
    <div class="post-button-container">
        <div class="like-comment-btns">
        <div class="like-btn" id="like-<?= $postId ?>">
        <div class="up-btn" id="up-<?= $postId ?>">
            <img src="../images/icons/arrow-up.png">
        </div>
        <p id="count-<?= $postId ?>">
            <?= $like->getLikeCount("post_id",$postId) ?></p>
        <div class="down-btn" id="down-<?= $postId ?>">
            <img src="../images/icons/arrow-down.png">
        </div>
        </div>
        <a href="comment.php?post_id=<?= $postId ?>" class="comment-btn">
            <img src="../images/icons/bubble.png">
            <p><?= $comment->getCommentCount("post_id",$postId); ?></p>
        </a>
        </div>
        <?php if($postItem["user_id"] == $userId): ?>
        <div class="delete-btn">
        <img src="../images/icons/set.png">
        </div>
        <?php endif; ?>
    </div>
    </div>
<script>
{
    const postId = <?= $postId ?>;
    const imgDisplay = document.getElementById(`imageDisplay-${postId}`);
    const leftArrow = document.getElementById(`leftArrow-${postId}`);
    const rightArrow = document.getElementById(`rightArrow-${postId}`);
    const postImages = <?= json_encode($postImages) ?>;
    const imageCount = postImages.length;
    const likeCount = document.getElementById(`count-${postId}`);
    const likeContainer = document.getElementById(`like-${postId}`);
    const upBtn = document.getElementById(`up-${postId}`);
    const downBtn = document.getElementById(`down-${postId}`);

    if("<?= $postLikeStatus ?>" === "liked")
    {
        likeContainer.style.backgroundColor = "rgba(223, 120, 120, 1)";
        upBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
        downBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
    }
    if("<?= $postLikeStatus ?>" === "disliked")
    {
        likeContainer.style.backgroundColor = "rgba(112, 148, 220, 1)";
        upBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
        downBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
    }

    const handleLike = (liketype)=>{
                    
    fetch('../decisionMaker.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `post-${liketype}=${postId}` 
    })
    .then(response => response.json())
    .then(data => {
    if(data.status === "success") {
        let count = data.new_count < 0 ? 0 : data.new_count;
        likeCount.textContent = count;
        const status = data.like_status; 

    if (status === "liked") {
        likeContainer.style.backgroundColor = "rgba(223, 120, 120, 1)";
        upBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
        downBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
    } else if (status === "disliked") {
        likeContainer.style.backgroundColor = "rgba(112, 148, 220, 1)";
        upBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
        downBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
    } else { 
        likeContainer.style.backgroundColor = "#dee8fe";
        upBtn.style.backgroundColor = "#dee8fe";
        downBtn.style.backgroundColor = "#dee8fe";
    }
    }})
    .catch(error => console.error('Network error:', error));
    };

    upBtn.addEventListener('click', () => handleLike('like'));
    downBtn.addEventListener('click', () => handleLike('dislike'));

    let currentImgIndex = 0;

    let updateImageDisplay = () => {
        imgDisplay.src = `../images/uploaded/${postImages[currentImgIndex].name}`;

        if(currentImgIndex > 0){
            leftArrow.style.display = "flex";
        } else{
            leftArrow.style.display = "none";
        }
        if(currentImgIndex < imageCount - 1){
            rightArrow.style.display = "flex";
        } else {
            rightArrow.style.display = "none";
        }
        if(imageCount <= 1){
            rightArrow.style.display = "none";
            leftArrow.style.display = "none";
        }
    };

    if (imageCount > 0) {
        updateImageDisplay();
    } else {
        if (leftArrow) leftArrow.style.display = "none";
        if (rightArrow) rightArrow.style.display = "none";
    }

    if (rightArrow) {
    rightArrow.addEventListener('click', () => {
        if (currentImgIndex < imageCount - 1) {
            currentImgIndex++;
            updateImageDisplay();
        }
    });
    }

    if (leftArrow) {
        leftArrow.addEventListener('click', () => {
            if (currentImgIndex > 0) {
                currentImgIndex--;
                updateImageDisplay();
            }
        });
    }
}
</script>
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
import { toggleMenu, toggleNotification, toggleSearch} from "../script/tools.js?v=<?php echo time(); ?>";

const commId = <?= $communityId ?>;
const menu = document.getElementById("userInfo");
const deleteBtn = document.querySelector('.delete-container');
const bellIcon = document.querySelector('.notifications-container');
const notificationNum = document.querySelector('.notification-number');
const searchEnter = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');

bellIcon.addEventListener('click',toggleNotification);
menu.addEventListener('click',toggleMenu);
deleteBtn.addEventListener('click',()=>{
    if(confirm("Are you sure you want do delete this community"))
    {
        deleteBtn.disabled = false;
    }
});

searchEnter.addEventListener('input', () => {
    let search = searchEnter.value.trim();
    if(search.length >= 2)
    {
    toggleSearch();
    }

    fetch("../decisionMaker.php?post-search=" + search + "&comm-id=" + commId)
    .then(res => res.json())
    .then(data => {
    searchResults.innerHTML = "";
    if(data.length === 0)
    {
        const div = document.createElement('div');
        div.innerHTML = "No results...";
        searchResults.appendChild(div);
        div.className = "search-no-result";
    }
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

        h3.innerHTML = "p/" + result['title'];
        if(result['text'].length > 0) {
            p.innerHTML = result['text'];
        }
        img.src = "../images/reddit.png";
        
        div.appendChild(divImg);
        divImg.appendChild(img);
        div.appendChild(divInfo);
        divInfo.appendChild(h3);
        divInfo.appendChild(p);
        searchResults.appendChild(div);

        div.addEventListener("click",()=>{
            window.location.href = "community.php?comm_id=" + result['community_id'];
        });
     });
});
});

</script>
</body>

</html>