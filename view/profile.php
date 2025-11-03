<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
use Reddit\models\User;
$session = new SessionService();

if(!$session->sessionExists("username"))
{
    header("Location: ../index.php");
}

$user = new User();

$id = $session->getFromSession('user_id');
$username = $session->getFromSession("username");
$karma = 4567; 
$accountAge = "3 years"; 
$bio = $user->getUserAtribute('bio',$id);

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
        <img src="../images/reddit.png" alt="Reddit Logo" class="reddit-logo">
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

            <button class="new-post-btn">Create Post</button>
        </div>
    </div>

    <div class="content-wrapper">
        <main class="main-content">
            <nav class="profile-nav">
                <a href="#" class="active">POSTS</a>
                <a href="#">COMMENTS</a>
                <a href="#">ABOUT</a>
                <a href="#">SAVED</a>
            </nav>
            
            <div class="post-item">
                <div class="upvote-area">
                    <span class="up-arrow">▲</span>
                    <span class="score">42</span>
                    <span class="down-arrow">▼</span>
                </div>
                <div class="post-content">
                    <h3>Title of the Posted Content</h3>
                    <p class="post-meta">Posted 2h ago in <a href="#">r/mySubreddit</a></p>
                    <p>This is a short excerpt of the post body or image preview.</p>
                    <div class="post-actions">
                        <a href="#"><img src="../images/icons/comment.png" alt="Comments"> 12 Comments</a>
                        <a href="#"><img src="../images/icons/share.png" alt="Share"> Share</a>
                        <a href="#"><img src="../images/icons/save.png" alt="Save"> Save</a>
                    </div>
                </div>
            </div>
            
            <div class="post-item no-content">
                <p>No more posts to display.</p>
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
    import { toggleMenu, changeBanner} from "../script/tools.js?v=<?php echo time(); ?>";
    const  menu = document.getElementById("userInfo");
    menu.addEventListener('click',toggleMenu);

    changeBanner('<?=$session->getFromSession('avatar')?>');
</script>

</body>
</html>