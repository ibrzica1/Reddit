<?php

namespace Reddit\repositories;

use Reddit\models\Db;
use Reddit\models\Like;

class LikeRepository extends Db
{
    public function getPostLikes(int $postId): ?Array
    {
        $stmt = $this->connection->prepare("SELECT * FROM likes WHERE post_id = :post_id");
        $stmt->bindParam(':post_id',$postId);
        $stmt->execute();

        $results = $stmt->fetchAll();
        if(!$results){
            return null;
        } 
        $postLikes = [];
        foreach($results as $result)
        {
            $postLike = new Like($result);
            array_push($postLikes,$postLike);
        }
        return $postLikes;
    } 

    public function getCommentLikes(int $commentId): ?Array
    {
        $stmt = $this->connection->prepare("SELECT * FROM likes WHERE comment_id = :comment_id");
        $stmt->bindParam(':comment_id',$commentId);
        $stmt->execute();

        $results = $stmt->fetchAll();
        if(!$results){
            return null;
        } 
        $commentLikes = [];
        foreach($results as $result)
        {
            $commentLike = new Like($result);
            array_push($commentLikes,$commentLike);
        }
        return $commentLikes;
    } 
    
    public function getLike(string $attribute,mixed $value,int $userId): ?Like
    {
        $stmt = $this->connection->prepare("SELECT * FROM likes WHERE $attribute = :value
        AND user_id = :user_id");
        $stmt->bindParam(':value',$value);
        $stmt->bindParam(':user_id',$userId);
        $stmt->execute();

        $result = $stmt->fetch();
        if(!$result){
            return null;
        } 
        $like = new Like($result);
        return $like;
    }

    public function addLikeComment(Like $like): void
    {
        $stmt = $this->connection->prepare("INSERT INTO likes (user_id,comment_id,status)
        VALUES (:user_id, :comment_id, :status)");
        $stmt->bindValue(':user_id', $like->getUser_id(), \PDO::PARAM_INT);
        $stmt->bindValue(':comment_id', $like->getComment_id(), \PDO::PARAM_INT);
        $stmt->bindValue(':status', $like->getStatus(), \PDO::PARAM_STR);

        $stmt->execute();
    }

    public function addLikePost(Like $like): void
    {
        $stmt = $this->connection->prepare("INSERT INTO likes (user_id,post_id,status)
        VALUES (:user_id, :post_id, :status)");
        $stmt->bindValue(':user_id', $like->getUser_id(), \PDO::PARAM_INT);
        $stmt->bindValue(':post_id', $like->getPost_id(), \PDO::PARAM_INT);
        $stmt->bindValue(':status', $like->getStatus(), \PDO::PARAM_STR);

        $stmt->execute();
    }

    public function updateLike($attribute,$value,$status,$userId)
    {
        $stmt = $this->connection->prepare("UPDATE likes 
        SET status = :status
        WHERE user_id = :user_id
        AND $attribute = :value");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':status', $status);

        $stmt->execute();
    }

    public function getLikeCount($attribute,$value)
    {
        $stmt = $this->connection->prepare("SELECT * FROM likes WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();
        
        $likes = $stmt->fetchAll();

        if(empty($likes))
        {
            $count = 0;
            return $count;
        }    
        $positive = 0;
        $negative = 0;
        foreach($likes as $like)
        {
            if($like["status"] == "liked")
            {
                $positive++;
            }
            if($like["status"] == "disliked")
            {
                $negative++;
            }
        }
        $count = $positive - $negative;
        $count = $count < 0 ? 0 : $count;
        return $count;
    }

    public function deleteLike($likeId)
    {
        $stmt = $this->connection->prepare("DELETE FROM likes WHERE id = :id");
        $stmt->bindParam(':id',$likeId);
        $stmt->execute();
    }
}