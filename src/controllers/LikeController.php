<?php

namespace Reddit\controllers;

use Reddit\models\Like;

class LikeController extends Like
{
    public function addPostDislikeController($userId,$postId)
    {
        $likeItem = $this->getLike("post_id",$postId,$userId);

        if(empty($likeItem))
        {
            $status = "disliked";
            $this->addLikePost($postId,$status,$userId);
            $newCount = $this->getPostLikeCount($postId);
            $data = [$newCount,$status];
            return $data;
        }
        
        if($likeItem['status'] == "disliked")
        {
            $likeId = $likeItem['id'];
            $this->deleteLike($likeId);
            $status = "neutral";
            $newCount = $this->getPostLikeCount($postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem['status'] == "liked")
        {
            $status = "disliked";
            $this->updateLikePost($postId,$status,$userId);
            $newCount = $this->getPostLikeCount($postId);
            $data = [$newCount,$status];
            return $data;
        }   
    }

    
    public function addPostLikeController($userId,$postId)
    {
        $likeItem = $this->getLike("post_id",$postId,$userId);

        if(empty($likeItem))
        {
            $status = "liked";
            $this->addLikePost($postId,$status,$userId);
            $newCount = $this->getPostLikeCount($postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem['status'] == "liked")
        {
            $likeId = $likeItem['id'];
            $this->deleteLike($likeId);
            $status = "neutral";
            $newCount = $this->getPostLikeCount($postId);
            $data = [$newCount,$status];
            return $data;
        }

        if($likeItem['status'] == "disliked")
        {
            $status = "liked";
            $this->updateLikePost($postId,$status,$userId);
            $newCount = $this->getPostLikeCount($postId);
            $data = [$newCount,$status];
            return $data;
        }     
    }
}
