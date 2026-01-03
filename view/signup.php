<?php

require_once "../vendor/autoload.php";

use Reddit\services\SessionService;
$session = new SessionService();

if($session->sessionExists("username"))
{
header("Location: ../index.php");
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Reddit Style</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../style/signup.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../style/header.css?v=<?php echo time(); ?>">
    
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
        <div class="login-container">
            <a href="../view/login.php">Log In</a>
        </div>
        <div class="signup-container">
            <a href="../view/signup.php">Sign Up</a>
        </div>
    </div>
</div>

<div class="main-content-wrapper">
    <div class="message-container">
        <p class="message"><?=$session->displayMessage()?></p>
    </div>
    
    <div class="reddit-container">
        <form class="reddit-form" method="POST" action="../decisionMaker.php">
            <input type="hidden" name="signup">
            
            <div class="form-header">
                <h2>Sign Up</h2>
                <p>By continuing, you are setting up a Reddit account and agree to our <a href="#">User Agreement</a> and <a href="#">Privacy Policy</a>.</p>
            </div>

            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            
            <div class="input-group">
                <input type="text" name="password" placeholder="Password" required>
            </div>
            
            <div class="input-group">
                <input type="text" name="password_confirm" placeholder="Confirm Password" required>
            </div>
            
            <button type="submit" class="reddit-btn-primary">Continue</button>
            
            <div class="form-footer">
                <p>Already a Redditor? <a href="index.php">Log In</a></p>
            </div>
        </form>
    </div>
</div>

<script type="module">
    import {generalSearch} from "../script/search.js?v=<?php echo time(); ?>";

    generalSearch();
</script>

</body>
</html>