<?php

namespace Reddit\controllers;

use Reddit\models\Comment;
use Reddit\services\SessionService;
use Reddit\services\TimeService;

class CommentController extends Comment
{
    
    public function createComment($text,$postId)
    {
        $session = new SessionService();
        $timeStamp = new TimeService();

        if(!isset($text))
        {
        $message = "You didnt send comment text";
        $session->setSession("message",$message);
        header("Location: view/comment.php?post_id=$postId");
        exit();
        }

        if(!isset($postId))
        {
        $message = "You didnt send post id";
        $session->setSession("message",$message);
        header("Location: view/comment.php?post_id=$postId");
        exit();
        }

        if(!$this->commentLength($text))
        {
          $message = "Text cant be longer than 500 letters";
          $session->setSession("message",$message);
          header("Location: view/comment.php?post_id=$postId");
          exit();  
        }

        $userId = $session->getFromSession("user_id");
        $time = $timeStamp->time;

        $this->registerComment($text,$userId,$postId,$time);

        header("Location: view/comment.php?post_id=$postId");

    }

}