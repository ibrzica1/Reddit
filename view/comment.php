<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\repositories\UserRepository;
use Reddit\repositories\CommunityRepository;
use Reddit\repositories\CommentRepository;
use Reddit\repositories\PostRepository;
use Reddit\repositories\ImageRepository;
use Reddit\repositories\LikeRepository;
use Reddit\repositories\NotificationRepository;

$session = new SessionService();
$time = new TimeService();
$community = new CommunityRepository();
$image = new ImageRepository();
$post = new PostRepository();
$user = new UserRepository();
$like = new LikeRepository();
$comment = new CommentRepository();
$notification = new NotificationRepository();

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
$selectedPost = $post->getPostById($postId);
$postCommunityId = $selectedPost->community_id;
$postCommunity = $community->getCommunity("id",$postCommunityId);
$communityImage = $image->getCommunityImage($postCommunityId);
$userId = $session->getFromSession("user_id");
$postUserId = $selectedPost->user_id;
$postUser = $user->getUserById($postUserId);
$userId = $session->getFromSession("user_id");
$postLikes = $like->getLike("post_id",$postId,$userId);
$likeStatus = empty($postLikes->status) ? "neutral" : $postLikes->status;
$comments = $comment->getComments("post_id",$postId);
$imgNum = 0;

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
        <div class="user-search-container">
            <img src="../images/community/<?=$communityImage->name?>">
            <p>r/<?= $postCommunity->name ?></p>
        </div>
        <input type="text" placeholder="Search in r/<?= $postCommunity->name ?>" id="searchInput">
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

<div class="body-container">

<div class="body-comments">
    <div class="post-container">
    <div class="post-header">
    <div class="info-header">
        <button class="get-back-btn" onclick="history.back()">
            <img src="../images/icons/back.png">
        </button>
        <div class="comm-image">
            <img src="../images/community/<?= $communityImage->name ?>">
        </div>
        <div class="comm-user-info">
            <div class="comm-name-time">
                <h4>r/<?= $postCommunity->name ?></h4>
                <p class="post-time-ago"> • <?= $time->calculateTime($selectedPost->time); ?></p>
            </div>
            <div class="user-name">
                <p><?= $postUser->username ?></p>
            </div>
        </div>
    </div>
    <?php if($selectedPost->user_id == $userId): ?>
        <form action="../decisionMaker.php" method="post" onsubmit="return confirm('Are you sure you want to delete this post?')">
            <input type="hidden" name="location" value="profile">
            <input type="hidden" name="post-delete" value="<?= $postId ?>">
            <button type="submit" class="delete-btn" id="delete-post-<?= $postId ?>">
                <img src="../images/icons/set.png">
            </button>
        </form>
    <?php endif; ?>
</div>
<div class="post-title">
    <h3><?= $selectedPost->title ?></h3>
</div>
<?php if(!empty($selectedPost->text)): ?>

<div class="post-text">
    <p><?= $selectedPost->text ?></p>
</div>
<?php else: ?>
    <?php $postImages = $image->getUploadedImages("post_id",$postId) ?>
    <?php $imgCount = count($postImages); ?>
    
    <div class="image">
        <div class="left-arrow" id="leftArrow">
            <img src="../images/icons/arrowLeft.png">
        </div>
        <img src="../images/uploaded/<?= $postImages[0]->name ?>" id="imageDisplay">
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
<?php if(empty($commentItem->comment_id)): ?>
    <?php $commId = $commentItem->id; ?>
    <?php $commentUser = $user->getUserByAttribute("id",$commentItem->user_id) ?>
    <?php $commentLikes = $like->getLike("comment_id",$commId,$commentItem->user_id)  ?>
    <?php $commentLikeStatus = empty($commentLikes->status) ? "neutral" : $commentLikes->status ?>
    
    <div class="single-comment">
        <div class="comment-author-info">
            <img src="../images/avatars/<?= $commentUser->avatar ?>.webp" class="comment-avatar">
            <span class="comment-username">u/<?= $commentUser->username ?></span>
            <span class="comment-time"><?= $time->calculateTime($commentItem->time) ?></span>
        </div>
        <div class="comment-content">
            <p><?= $commentItem->text ?></p>
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
        <?php if($replyItem->comment_id === $commentItem->id): ?>
        <?php $replyId = $replyItem->id; ?>
        <?php $replyUser = $user->getUserByAttribute("id",$replyItem->user_id) ?>
        <?php $replyLikes = $like->getLike("comment_id",$replyId,$replyItem->user_id)  ?>
        <?php $replyLikeStatus = empty($replyLikes->status) ? "neutral" : $replyLikes->status ?>
            <div class="single-comment">
            <div class="comment-author-info">
                <img src="../images/avatars/<?= $replyUser->avatar ?>.webp" class="comment-avatar">
                <span class="comment-username">u/<?= $replyUser->username?></span>
                <span class="comment-time"><?= $time->calculateTime($replyItem->time) ?></span>
            </div>
            <div class="comment-content">
                <p><?= $replyItem->text ?></p>
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
        <h4 class="community-name">About r/<?= $postCommunity->name ?></h4>
    </div>
    <div class="community-description">
        <p><?= $postCommunity->description ?></p>
    </div>
    <div class="community-created">
        <p class="community-stats">
            <img src="../images/icons/cake.png" alt="Cake Day">
            Created: <?= $time->calculateTime($postCommunity->time); ?>
        </p>
    </div>
    <a href="community.php?comm_id=<?= $postCommunityId ?>" class="community-view-btn">View Community</a>
</div>

</div>

<script type="module">
import {toggleNotification, toggleSearch} from "../script/tools.js?v=<?php echo time(); ?>";
const commId = <?= $postCommunityId ?>;
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
const searchEnter = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');

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

let currentImgIndex = 0;
console.log(likeCount);

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