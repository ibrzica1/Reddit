<?php

namespace Reddit\controllers;

use Reddit\models\Notification;
use Reddit\models\Community;
use Reddit\models\Post;
use Reddit\models\Comment;
use Reddit\services\TimeService;

class NotificationController extends Notification
{
    public function likeCommentNotification($senderId,$likeId,$commentId)
    {
        $timeStamp = new TimeService();
        $comment = new Comment();

        $selectedComment = $comment->getComments("id",$commentId);
        $recieverId = $selectedComment[0]["user_id"];
        $time = $timeStamp->time;

        if($this->existNotification($recieverId,"like","comment_id",$commentId))
        {
            return;
        }

        if($recieverId == $senderId || empty($recieverId))
        {
            return;
        }

        $type = "like";
        $seen = "false";

        $this->registerLikeCommentNotification($recieverId,$senderId,$likeId,$commentId,$type,$seen,$time);
    }

    public function likePostNotification($senderId,$likeId,$postId)
    {
        $timeStamp = new TimeService();
        $post = new Post();

        $selectedPost = $post->getPost("id",$postId);
        
        $recieverId = $selectedPost[0]["user_id"];
        $time = $timeStamp->time;

        if($this->existNotification($recieverId,"like","post_id",$postId))
        {
            return;
        }

        if($recieverId == $senderId || empty($recieverId))
        {
            return;
        }

        $type = "like";
        $seen = "false";

        $this->registerLikePostNotification($recieverId,$senderId,$likeId,$postId,$type,$seen,$time);
    }

    public function commentNotification($senderId,$commentId,$postId,$time)
    {
        $post = new Post();

        $selectedPost = $post->getPost("id",$postId);
        $recieverId = $selectedPost[0]["user_id"];

        if($recieverId == $senderId || empty($recieverId))
        {
            return;
        }

        $type = "comment";
        $seen = "false";

        $this->registerCommentNotification($recieverId,$senderId,$commentId,$postId,$type,$seen,$time);
    }

    public function postNotification($senderId,$postId,$communityId,$time)
    {
        $community = new Community();
        
        $selectedCommunity = $community->getCommunity("id",$communityId);
        $recieverId = $selectedCommunity[0]["user_id"];
        
        
        if($recieverId == $senderId || empty($recieverId))
        {
            return;
        }

        $type = "post";
        $seen = "false";

        $this->registerPostNotification($recieverId,$senderId,$postId,$communityId,$type,$seen,$time);
    }

    public function markAllNottSeen($userId)
    {
        $seen = "true";
        $this->markAllSeen($userId,$seen);

        header("Location: view/notification.php");
        exit();
    }
}