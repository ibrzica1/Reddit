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
$user = new User();
$like = new Like();
$comment = new Comment();
$notification = new Notification();

if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$getPost = $_GET['post_id'];
if(!empty($_GET['nott_id']))
{
    $notificationId = $_GET['nott_id'];
    if(!empty($notificationId)) $notification->changeSeenStatus($notificationId,"true");
}
$postId = intval($getPost);
$selectedPost = $post->getPost("id",$postId);
$postCommunityId = $selectedPost[0]["community_id"];
$postCommunity = $community->getCommunity("id",$postCommunityId);
$communityImage = $image->getCommunityImage($postCommunityId);
$userId = $session->getFromSession("user_id");
$postUserId = $selectedPost[0]["user_id"];
$postUser = $user->getUserByAttribute("id",$postUserId);
$userId = $session->getFromSession("user_id");
$postLikes = $like->getLike("post_id",$postId,$userId);
$likeStatus = empty($postLikes["status"]) ? "neutral" : $postLikes["status"];
$comments = $comment->getComments("post_id",$postId);
$imgNum = 0;
$notifications = $notification->unreadNotifications($userId);
$nottNumber = count($notifications);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comment</title>
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/comment.css?v=<?php echo time(); ?>">
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

<div class="body-container">

<div class="body-comments">
    <div class="post-container">
    <div class="post-header">
    <div class="info-header">
        <button class="get-back-btn" onclick="history.back()">
            <img src="../images/icons/back.png">
        </button>
        <div class="comm-image">
            <img src="../images/community/<?= $communityImage["name"] ?>">
        </div>
        <div class="comm-user-info">
            <div class="comm-name-time">
                <h4>r/<?= $postCommunity[0]["name"] ?></h4>
                <p class="post-time-ago"> • <?= $time->calculateTime($selectedPost[0]["time"]); ?></p>
            </div>
            <div class="user-name">
                <p><?= $postUser["username"] ?></p>
            </div>
        </div>
    </div>
    <?php if($selectedPost[0]["user_id"] == $userId): ?>
        <form action="../decisionMaker.php" method="post" onsubmit="return confirm('Jeste li sigurni da želite obrisati ovaj post?');">
            <input type="hidden" name="location" value="profile">
            <input type="hidden" name="post-delete" value="<?= $postId ?>">
            <button type="submit" class="delete-btn" id="delete-post-<?= $postId ?>">
                <img src="../images/icons/set.png">
            </button>
        </form>
    <?php endif; ?>
</div>
<div class="post-title">
    <h3><?= $selectedPost[0]["title"] ?></h3>
</div>
<?php if(!empty($selectedPost[0]["text"])): ?>

<div class="post-text">
    <p><?= $selectedPost[0]["text"] ?></p>
</div>
<?php else: ?>
    <?php $postImages = $image->getUploadedImages("post_id",$postId) ?>
    <?php $imgCount = count($postImages); ?>
    
    <div class="image">
        <div class="left-arrow" id="leftArrow">
            <img src="../images/icons/arrowLeft.png">
        </div>
        <img src="../images/uploaded/<?= $postImages[0]["name"] ?>" id="imageDisplay">
        <div class="right-arrow" id="rightArrow">
            <img src="../images/icons/arrowRight.png">
        </div>
    </div>

<?php endif; ?>
<div class="post-button-container">
<div class="like-comment-btns">
    <div class="like-btn" id="like-<?= $postId ?>">
        <button class="up-btn" id="up-<?= $postId ?>" data-post-id="<?= $postId ?>" data-active="<?= $likeStatus == 'liked' ? 'liked' : '' ?>">
            <img src="../images/icons/arrow-up.png">
        </button>
        <p class="likes" id="count-<?= $postId ?>"><?= $like->getLikeCount("post_id",$postId) ?></p>
        <button class="down-btn" id="down-<?= $postId ?>" data-post-id="<?= $postId ?>" data-active="<?= $likeStatus == 'disliked' ? 'disliked' : '' ?>">
            <img src="../images/icons/arrow-down.png">
        </button>
    </div>
    <a href="comment.php?post_id=<?= $postId ?>" class="comment-btn">
        <img src="../images/icons/bubble.png">
        <p><?= $comment->getCommentCount("post_id",$postId) ?></p>
    </a>
</div>
</div>
</div>

<div class="reply-container">
    <p class="commenting-as">Comment as <span class="reply-username">u/<?= $session->getFromSession("username") ?></span></p>
    <form action="../decisionMaker.php" method="POST" class="comment-form">
        <input type="hidden" name="post_id" value="<?= $postId ?>">
        <textarea name="comment_text" placeholder="What are your thoughts?" rows="4"></textarea>
        <div class="form-footer">
            <button type="submit" name="submit-comment" class="comment-submit-btn">Comment</button>
        </div>
    </form>
</div>

<div class="comment-separator"></div>

<div class="comments-grid">
<?php foreach($comments as $commentItem): ?>
<?php if(empty($commentItem['comment_id'])): ?>
    <?php $commId = $commentItem["id"]; ?>
    <?php $commentUser = $user->getUserByAttribute("id",$commentItem["user_id"]) ?>
    <?php $commentLikes = $like->getLike("comment_id",$commId,$commentItem["user_id"])  ?>
    <?php $commentLikeStatus = empty($commentLikes["status"]) ? "neutral" : $commentLikes["status"] ?>
    
    <div class="single-comment">
        <div class="comment-author-info">
            <img src="../images/avatars/<?= $commentUser['avatar'] ?>.webp" class="comment-avatar">
            <span class="comment-username">u/<?= $commentUser["username"] ?></span>
            <span class="comment-time"><?= $time->calculateTime($commentItem["time"]) ?></span>
        </div>
        <div class="comment-content">
            <p><?= $commentItem["text"] ?></p>
        </div>
        <div class="comment-actions">
            <div class="like-btn" id="comm-like-<?= $commId ?>">
                <button class="up-btn" id="comm-up-<?= $commId ?>" data-comm-id="<?= $commId ?>">
                <img src="../images/icons/arrow-up.png">
                </button>
                <p class="likes" id="comm-count-<?= $commId ?>"><?= $like->getLikeCount("comment_id",$commId) ?></p>
                <button class="down-btn" id="comm-down-<?= $commId ?>" data-comm-id="<?= $commId ?>">
                <img src="../images/icons/arrow-down.png">
                </button>
            </div>
            <button class="comment-reply-btn" id="commentReplyBtn-<?= $commId ?>">
                Reply</button>
        </div>
        <form action="../decisionMaker.php" method="post" class="reply-form" id="replyForm-<?= $commId ?>">
            <input type="hidden" name="post_id" value="<?= $postId ?>">
            <input type="hidden" name="comment_id" value="<?= $commId ?>">
            <textarea name="reply-text" rows="1" id="replyText-<?= $commId ?>"></textarea>
            <div class="reply-btns">
                <button type="button" class="reply-cancel" id="replyCancel-<?= $commId ?>">
                    Cancel</button>
                <button type="submit" class="reply-submit" id="replySubmit-<?= $commId ?>">
                    Submit</button>
            </div>
        </form>
        <div class="reply-comments-grid">
        <?php foreach($comments as $replyItem): ?>
        <?php if($replyItem["comment_id"] === $commentItem["id"]): ?>
        <?php $replyId = $replyItem["id"]; ?>
        <?php $replyUser = $user->getUserByAttribute("id",$replyItem["user_id"]) ?>
        <?php $replyLikes = $like->getLike("comment_id",$replyId,$replyItem["user_id"])  ?>
        <?php $replyLikeStatus = empty($replyLikes["status"]) ? "neutral" : $replyLikes["status"] ?>
            <div class="single-comment">
            <div class="comment-author-info">
                <img src="../images/avatars/<?= $replyUser['avatar'] ?>.webp" class="comment-avatar">
                <span class="comment-username">u/<?= $replyUser["username"] ?></span>
                <span class="comment-time"><?= $time->calculateTime($replyItem["time"]) ?></span>
            </div>
            <div class="comment-content">
                <p><?= $replyItem["text"] ?></p>
            </div>
            <div class="comment-actions">
            <div class="like-btn" id="comm-like-<?= $replyId ?>">
                <button class="up-btn" id="comm-up-<?= $replyId ?>" data-comm-id="<?= $replyId ?>">
                <img src="../images/icons/arrow-up.png">
                </button>
                <p class="likes" id="comm-count-<?= $replyId ?>"><?= $like->getLikeCount("comment_id",$replyId) ?></p>
                <button class="down-btn" id="comm-down-<?= $replyId ?>" data-comm-id="<?= $replyId ?>">
                <img src="../images/icons/arrow-down.png">
                </button>
            </div>
        </div>  
        </div>
    <script>
    {
      const replyId = <?= $replyId ?>;
      const replyLikeCount = document.getElementById(`comm-count-${replyId}`);
      const replyLikeContainer = document.getElementById(`comm-like-${replyId}`);
      const replyUpBtn = document.getElementById(`comm-up-${replyId}`);
      const replyDownBtn = document.getElementById(`comm-down-${replyId}`);

    if("<?= $replyLikeStatus ?>" === "liked")
    {
        replyLikeContainer.style.backgroundColor = "rgba(223, 120, 120, 1)";
        replyUpBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
        replyDownBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
    }
    if("<?= $replyLikeStatus ?>" === "disliked")
    {
        replyLikeContainer.style.backgroundColor = "rgba(112, 148, 220, 1)";
        replyUpBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
        replyDownBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
    }

    const handleReplyLike = (liketype)=>{
                                    
        fetch('../decisionMaker.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `comment-${liketype}=${replyId}` 
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === "success") {
                let count = data.new_count < 0 ? 0 : data.new_count;
                replyLikeCount.textContent = count;
                const status = data.like_status; 

            if (status === "liked") {
                replyLikeContainer.style.backgroundColor = "rgba(223, 120, 120, 1)";
                replyUpBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
                replyDownBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
            } else if (status === "disliked") {
                replyLikeContainer.style.backgroundColor = "rgba(112, 148, 220, 1)";
                replyUpBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
                replyDownBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
            } else { 
                replyLikeContainer.style.backgroundColor = "#dee8fe";
                replyUpBtn.style.backgroundColor = "#dee8fe";
                replyDownBtn.style.backgroundColor = "#dee8fe";
            }
        }})
        .catch(error => console.error('Network error:', error));
    };

    replyUpBtn.addEventListener('click', () => handleReplyLike('like'));
    replyDownBtn.addEventListener('click', () => handleReplyLike('dislike'));
    }
    </script>
        <?php endif; ?>
        <?php endforeach; ?>
        </div>
    </div>
<script>
{
const commentId = <?= $commId ?>;
const commLikeCount = document.getElementById(`comm-count-${commentId}`);
const commLikeContainer = document.getElementById(`comm-like-${commentId}`);
const commUpBtn = document.getElementById(`comm-up-${commentId}`);
const commDownBtn = document.getElementById(`comm-down-${commentId}`);
const commReplyBtn = document.getElementById(`commentReplyBtn-${commentId}`);
const replyText =document.getElementById(`replyText-${commentId}`);
const replyForm = document.getElementById(`replyForm-${commentId}`);
const replyCancel = document.getElementById(`replyCancel-${commentId}`);
const replySubmit = document.getElementById(`replySubmit-${commentId}`);


if("<?= $commentLikeStatus ?>" === "liked")
{
    commLikeContainer.style.backgroundColor = "rgba(223, 120, 120, 1)";
    commUpBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
    commDownBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
}
if("<?= $commentLikeStatus ?>" === "disliked")
{
    commLikeContainer.style.backgroundColor = "rgba(112, 148, 220, 1)";
    commUpBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
    commDownBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
}

const handleCommLike = (liketype)=>{
                                
    fetch('../decisionMaker.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `comment-${liketype}=${commentId}` 
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === "success") {
            let count = data.new_count < 0 ? 0 : data.new_count;
            commLikeCount.textContent = count;
            const status = data.like_status; 

        if (status === "liked") {
            commLikeContainer.style.backgroundColor = "rgba(223, 120, 120, 1)";
            commUpBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
            commDownBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
        } else if (status === "disliked") {
            commLikeContainer.style.backgroundColor = "rgba(112, 148, 220, 1)";
            commUpBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
            commDownBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
        } else { 
            commLikeContainer.style.backgroundColor = "#dee8fe";
            commUpBtn.style.backgroundColor = "#dee8fe";
            commDownBtn.style.backgroundColor = "#dee8fe";
        }
    }})
    .catch(error => console.error('Network error:', error));
};

commUpBtn.addEventListener('click', () => handleCommLike('like'));
commDownBtn.addEventListener('click', () => handleCommLike('dislike'));
commReplyBtn.addEventListener('click', () => {
    replyForm.style.display = 'flex';
    replyText.focus();
});
replyCancel.addEventListener('click', () => {
    replyForm.style.display = 'none';
});
}
</script>
    <?php endif; ?>
    <?php endforeach; ?>    
    </div>
</div>

<div class="body-community">
    <div class="community-card-header">
        <h4 class="community-name">About r/<?= $postCommunity[0]["name"] ?></h4>
    </div>
    <div class="community-description">
        <p><?= $postCommunity[0]["description"] ?></p>
    </div>
    <div class="community-created">
        <p class="community-stats">
            <img src="../images/icons/cake.png" alt="Cake Day">
            Created: <?= $time->calculateTime($postCommunity[0]['time']); ?>
        </p>
    </div>
    <a href="community.php?comm_id=<?= $postCommunityId ?>" class="community-view-btn">View Community</a>
</div>

</div>

<script type="module">
import { toggleMenu, toggleNotification} from "../script/tools.js?v=<?php echo time(); ?>";

const menu = document.getElementById("userInfo");
const bellIcon = document.querySelector('.notifications-container');
const notificationNum = document.querySelector('.notification-number');
const idPost = <?= $postId ?>;
const likeCount = document.getElementById(`count-${idPost}`);
const likeContainer = document.getElementById(`like-${idPost}`);
const upBtn = document.getElementById(`up-${idPost}`);
const downBtn = document.getElementById(`down-${idPost}`);
const deletePost = document.getElementById(`delete-post-${idPost}`);
const imgDisplay = document.getElementById(`imageDisplay`);
const leftArrow = document.getElementById(`leftArrow`);
const rightArrow = document.getElementById(`rightArrow`);
const postImages = <?= isset($postImages) ? json_encode($postImages) : '[]' ?>;
const imageCount = postImages.length;
let currentImgIndex = 0;
console.log(likeCount);

menu.addEventListener('click',toggleMenu);
bellIcon.addEventListener('click',toggleNotification);

const updateImageDisplay = () => {
    imgDisplay.src = `../images/uploaded/${postImages[currentImgIndex].name}`;

    if (currentImgIndex > 0) {
        leftArrow.style.display = "flex";
    } else {
        leftArrow.style.display = "none";
    }

    if (currentImgIndex < imageCount - 1) {
        rightArrow.style.display = "flex";
    } else {
        rightArrow.style.display = "none";
    }
    
    if (imageCount <= 1) {
        leftArrow.style.display = "none";
        rightArrow.style.display = "none";
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

if("<?= $likeStatus ?>" === "liked")
{
    likeContainer.style.backgroundColor = "rgba(223, 120, 120, 1)";
    upBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
    downBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
}
if("<?= $likeStatus ?>" === "disliked")
{
    likeContainer.style.backgroundColor = "rgba(112, 148, 220, 1)";
    upBtn.style.backgroundColor = "rgba(112, 148, 220, 1)";
    downBtn.style.backgroundColor = "rgba(66, 117, 220, 1)";
}

const handleLike = (liketype)=>{
                                
    fetch('../decisionMaker.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `post-${liketype}=${idPost}` 
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



</script>
</body>

</html>