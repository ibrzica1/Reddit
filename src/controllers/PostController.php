<?php

namespace Reddit\controllers;

use Reddit\models\Post;
use Reddit\services\SessionService;
use Reddit\services\TimeService;

class PostController extends Post
{
    public function textPost($title, $text)
    {
        $session = new SessionService();
        $timeStamp = new TimeService();

        $user_id = $session->getFromSession('user_id');
        $time = $timeStamp->time;
        $likes = 0;
    
        if(!isset($title))
        {
        $message = "You didnt send confim username";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!isset($text))
        {
        $message = "You didnt send password";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!$this->titleLength($title))
        {
        $message = "Title cant be bigger then 300 letters and smaller then 2 letter";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!$this->textLength($text))
        {
        $message = "Text cant be bigger then 1000 letters and smaller then 2 letter";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        $this->registerTextPost($title,$text,$user_id,$time,$likes);
    }
}