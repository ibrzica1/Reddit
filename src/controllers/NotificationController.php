<?php

namespace Reddit\controllers;

use Reddit\models\Notification;
use Reddit\models\Community;
use Reddit\models\Post;

class NotificationController extends Notification
{
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
}