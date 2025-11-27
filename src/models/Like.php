<?php

namespace Reddit\models;
use Reddit\models\Db;

class Like extends Db
{
    protected $userId;
    public $postId;
    public $commentId;
    public $status;

    public function __construct($idUser)
    {
        $this->userId = $idUser;
    }

    public function getLike($attribute,$value)
    {
        $stmt = $this->connection->prepare("SELECT * FROM like WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);
        
        $stmt->execute();
    }

    public function addLikePost($postId,$status)
    {
        $stmt = $this->connection->prepare("INSERT INTO like (user_id,post_id,status)
        VALUES :user_id, :post_id, :status");
        $stmt->bindParam(':user_id', $this->userId);
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':status', $status);

        $stmt->execute();
    }
}