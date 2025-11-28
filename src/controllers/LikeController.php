<?php

namespace Reddit\controllers;

use Reddit\models\Like;

class LikeController extends Like
{
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
        else 
        {
            if($likeItem['status'] == "liked")
            {
                $status = "neutral";
                $this->updateLikePost($postId,$status,$userId);
                $newCount = $this->getPostLikeCount($postId);
                $data = [$newCount,$status];
                return $data;
            }
            if($likeItem['status'] == "neutral")
            {
                $status = "liked";
                $this->updateLikePost($postId,$status,$userId);
                $newCount = $this->getPostLikeCount($postId);
                $data = [$newCount,$status];
                return $data;
            }
        }
    }
}