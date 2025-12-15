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
$user = new User();
$community = new Community();
$image = new Image();
$post = new Post();
$like = new Like();
$comment = new Comment();
$notification = new Notification();

if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$user = new User();

$id = $session->getFromSession('user_id');
$username = $session->getFromSession("username");
$timeCreated = $user->getUserAtribute('time',$id);
$userKarma = $user->getUserAtribute('karma',$id);
$accountAge = $time->calculateTime($timeCreated[0]); 
$bio = $user->getUserAtribute('bio',$id);
$karma = $userKarma[0];
$activeTab = $_GET['tab'] ?? "posts";
$notifications = $notification->unreadNotifications($id);
$nottNumber = count($notifications);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
<link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../style/profile.css?v=<?php echo time(); ?>">
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

<div class="profile-page-container">
    <div class="profile-header">
        <div class="banner"></div>
        <div class="profile-card">
            <img src="../images/avatars/<?= $session->getFromSession('avatar')?>.webp" alt="Avatar" class="profile-avatar"> 
            
            <div class="profile-info-content">
                <div class="username-section">
                    <h1>u/<?= $username ?></h1>
                    <p class="display-name">
                        <img src="../images/icons/cake.png" alt="Cake Day" class="cake-icon">
                        User since: <?php echo $accountAge; ?>
                    </p>
                </div>
                <a href="settings.php">
                    <button class="edit-profile-btn">Edit Profile</button>
                </a>
                
            </div>
            
            <p class="bio"><?= $bio[0] ?></p>
            
            <div class="karma-info">
                <div>
                    <strong>KARMA</strong>
                    <p><?= number_format($karma) ?></p>
                </div>
            </div>
            <a href="createPost.php">
                <button class="new-post-btn">Create Post</button>
            </a>
            <a href="createCommunity.php" class="new-comm-btn">
                <img src="../images/icons/add.png">
                <p>Start a Community</p>
            </a>
            
        </div>
    </div>

    <div class="message-container">
        <p class="message"><?=$session->displayMessage()?></p>
    </div>

<div class="content-wrapper">
<main class="main-content">
    <nav class="profile-nav">
        <a href="profile.php?tab=posts" id="posts">POSTS</a>
        <a href="profile.php?tab=comments" id="comments">COMMENTS</a>
        <a href="profile.php?tab=communities" id="communities">COMMUNITIES</a>
    </nav>
    
<div class="content-container">
    <?php if($activeTab == "communities"): ?>
        <?php $communities = $community->getCommunity("user_id",$id); ?>
        <?php if(empty($communities)): ?>
    <div class="empty-container">
        <img src="../images/logo-not-found.png" class="logo-not-found">
        <h2>You dont have any communities yet</h2>
        <h3>Once you create a community, it'll show up here.</h3>
    </div>
        
        <?php else: ?>
        <?php foreach($communities as $community): ?>
            <?php $communityImg = $image->getCommunityImage($community['id']); ?>
            <div class="community-card">
    <div class="community-icon">
        <img src='../images/community/<?=$communityImg['name']?>' alt="">
    </div>
    <div class="community-info">
        <a href="community.php?comm_id=<?=$community['id']?>" class="community-name">
            <span>r/</span><?= $community['name'] ?></a>
        <p class="community-desc"><?= $community['description'] ?></p>
        <p class="community-time">Created <?= $time->calculateTime($community['time']); ?></p>
    </div>
    <form action="../decisionMaker.php" method="post">
        <input type="hidden" name="delete-community" value="<?=$community['id']?>">
        <button class="delete-container">
            <img src="../images/icons/set.png">
        </button>
    </form>
    
</div>
    <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>
<?php if($activeTab == "posts"): ?>
    <?php $posts = $post->getPost("user_id",$id) ?>
    <?php if(empty($posts)): ?>
        <div class="empty-container">
            <img src="../images/logo-not-found.png" class="logo-not-found">
            <h2>You dont have any posts yet</h2>
            <h3>Once you create a post, it'll show up here.</h3>
        </div>
    <?php else: ?>
<?php foreach($posts as $postItem): ?>
    <?php $commId = $postItem["community_id"]; ?>
    <?php $postCommunity = $community->getCommunity("id",$commId); ?>
    <?php $postId = $postItem["id"] ?>
    <?php $postLikes = $like->getLike("post_id",$postId,$id) ?>
    <?php $likeId = empty($postLikes["user_id"]) ? 0 : $postLikes["user_id"]; ?>
    <?php $likeStatus = empty($postLikes["status"]) ? "neutral" : $postLikes["status"] ?>
    <?php $postImages = []; ?>
    
    <?php $communityImg = $image->getCommunityImage($postItem["community_id"]) ?>
    <div class="post-container">
    <a href="community.php?comm_id=<?=$commId?>" class="post-user-container">
        <img src="../images/community/<?=$communityImg["name"]?>">
        <p><span>u/</span><?= $postCommunity[0]["name"] ?></p>
        <h3><?= $time->calculateTime($postItem["time"]); ?></h3>
    </a>
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
            <button class="up-btn" id="up-<?= $postId ?>" data-post-id="<?= $postId ?>">
            <img src="../images/icons/arrow-up.png">
            </button>
            <p class="likes" id="count-<?= $postId ?>"><?= $like->getLikeCount("post_id",$postId) ?></p>
            <button class="down-btn" id="down-<?= $postId ?>" data-post-id="<?= $postId ?>">
            <img src="../images/icons/arrow-down.png">
            </button>
        </div>
        <a href="comment.php?post_id=<?= $postId ?>" class="comment-btn">
            <img src="../images/icons/bubble.png">
            <p><?= $comment->getCommentCount("post_id",$postId) ?></p>
        </a>
    </div>
    <?php if($postItem["user_id"] == $id): ?>
    <form action="../decisionMaker.php" method="post">
        <input type="hidden" name="location" value="profile">
        <input type="hidden" name="post-delete" value="<?= $postId ?>">
        <button type="submit" class="delete-btn" id="delete-post-<?= $postId ?>">
        <img src="../images/icons/set.png">
        </button>
    </form>
    <?php endif; ?>
</div>
</div>
<script>
{
const idPost = <?= $postId ?>;
const likeCount = document.getElementById(`count-${idPost}`);
const likeContainer = document.getElementById(`like-${idPost}`);
const upBtn = document.getElementById(`up-${idPost}`);
const downBtn = document.getElementById(`down-${idPost}`);
const deletePost = document.getElementById(`delete-post-${idPost}`);
const imgDisplay = document.getElementById(`imageDisplay-${idPost}`);
const leftArrow = document.getElementById(`leftArrow-${idPost}`);
const rightArrow = document.getElementById(`rightArrow-${idPost}`);
const postImages = <?= json_encode($postImages) ?>;
const imageCount = postImages.length;

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

if(<?= $likeId ?> === <?= $id ?> && "<?= $likeStatus ?>" === "liked")
{
    likeContainer.style.backgroundColor = "rgba(223, 120, 120, 1)";
    upBtn.style.backgroundColor = "rgba(220, 55, 55, 1)";
    downBtn.style.backgroundColor = "rgba(223, 120, 120, 1)";
}
if(<?= $likeId ?> === <?= $id ?> && "<?= $likeStatus ?>" === "disliked")
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
    deletePost.addEventListener('click',()=>{
    if(confirm("Are you sure you want do delete this post"))
    {
        deletePost.disabled = false;
    }
    });
    }
</script>
        <?php endforeach; ?>    
        <?php endif; ?>    
        <?php endif; ?> 
    <?php if($activeTab == "comments"): ?>
     <?php $comments = $comment->getComments("user_id",$id) ?>
    <?php if(empty($comments)): ?>
        <div class="empty-container">
            <img src="../images/logo-not-found.png" class="logo-not-found">
            <h2>You dont have any comments yet</h2>
            <h3>Once you create a comment, it'll show up here.</h3>
        </div>
    <?php else: ?>   
    <?php foreach($comments as $commentItem): ?>
    <?php $commId = $commentItem["id"]; ?>
    <?php $commentUser = $user->getUserByAttribute("id",$commentItem["user_id"]); ?>
    <?php $commentPost = $post->getPost("id",$commentItem["post_id"]); ?>
    <?php $commentCommunity = $community->getCommunity("id",$commentPost[0]["community_id"]); ?>
    <?php $commentCommunityImg = $image->getCommunityImage($commentCommunity[0]["id"]); ?>
    <?php $commentLikes = $like->getLike("comment_id",$commId,$commentItem["user_id"]);  ?>
    <?php $commentLikeStatus = empty($commentLikes["status"]) ? "neutral" : $commentLikes["status"] ?>

    <div class="single-comment">
        <div class="post-info">
            <img src="../images/community/<?= $commentCommunityImg["name"] ?>" class="community-img">
            <p class="comment-community-name">r/<?= $commentCommunity[0]["name"] ?></p>
            <p class="post-title"><?= $commentPost[0]["title"] ?></p>
        </div>
        <div class="comment-user-info">
            <h3><?= $commentUser["username"] ?></h3>
            <p>commented <?= $time->calculateTime($commentItem["time"]) ?></p>
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
            <a href="comment.php?post_id=<?= $commentPost[0]["id"] ?>" class="comment-reply-btn" id="commentReplyBtn-<?= $commId ?>">
                <img src="../images/icons/bubble.png">
                <p>Reply</p>
            </a>
            
        </div>
    </div>
    <script>
    {
    const commentId = <?= $commId ?>;
    const commLikeCount = document.getElementById(`comm-count-${commentId}`);
    const commLikeContainer = document.getElementById(`comm-like-${commentId}`);
    const commUpBtn = document.getElementById(`comm-up-${commentId}`);
    const commDownBtn = document.getElementById(`comm-down-${commentId}`);

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
    }
    </script>
    <?php endforeach; ?>
    <?php endif; ?> 
    <?php endif; ?>   
</div>

</main>

<aside class="sidebar-right">
    <div class="sidebar-card about-user">
        <h4>About User</h4>
        <p>This is a short, public note about the user. The user is verified and active.</p>
        <a href="#">Add social links</a>
    </div>
    <div class="sidebar-card rules">
        <h4>Rules and Info</h4>
        <ul>
            <li><a href="#">Code of Conduct</a></li>
            <li><a href="#">Content Submission</a></li>
            <li><a href="#">FAQ</a></li>
        </ul>
    </div>
</aside>
</div>
</div>

<script type="module">
    import { toggleMenu, changeBanner, toggleNotification} from "../script/tools.js?v=<?php echo time(); ?>";
    const menu = document.getElementById("userInfo");
    const postBtn = document.getElementById("posts");
    const communityBtn = document.getElementById("communities");
    const commentsBtn = document.getElementById("comments");
    const deleteBtn = document.querySelectorAll('.delete-container');
    const bellIcon = document.querySelector('.notifications-container');
    const notificationNum = document.querySelector('.notification-number');
    
    deleteBtn.forEach(btn => {
        btn.addEventListener('click',()=>{
            if(confirm("Are you sure you want do delete this community"))
            {
                btn.disabled = false;
            }
        });
    });

    bellIcon.addEventListener('click',toggleNotification);
    menu.addEventListener('click',toggleMenu);

    "<?=$activeTab?>" == "posts" && postBtn.classList.add("active");
        
    "<?=$activeTab?>" == "communities" && communityBtn.classList.add("active");
    
    "<?=$activeTab?>" == "comments" && commentsBtn.classList.add("active");

    changeBanner('<?=$session->getFromSession('avatar')?>');
</script>

</body>
</html>