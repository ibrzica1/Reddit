<?php

namespace Reddit\models;
use Reddit\models\Db;

class Like extends Db
{
    public $userId;
    public $postId;
    public $commentId;
    public $status;


    public function getLike($attribute,$value,$userId)
    {
        $stmt = $this->connection->prepare("SELECT * FROM likes WHERE $attribute = :value
        AND user_id = :user_id");
        $stmt->bindParam(':value',$value);
        $stmt->bindParam(':user_id',$userId);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function addLikePost($postId,$status,$userId)
    {
        $stmt = $this->connection->prepare("INSERT INTO like (user_id,post_id,status)
        VALUES :user_id, :post_id, :status");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':status', $status);

        $stmt->execute();
    }

    public function updateLikePost($postId,$status,$userId)
    {
        $stmt = $this->connection->prepare("UPDATE likes 
        SET status = :status
        WHERE user_id = :user_id
        AND post_id = :post_id)");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':status', $status);

        $stmt->execute();
    }

    
}