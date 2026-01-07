<?php

namespace Reddit\repositories;

use Reddit\models\Db;
use Reddit\models\Comment;

class CommentRepository extends Db
{ 
    public function getComments(string $atribute, mixed $value): Array
    {
        $stmt = $this->connection->prepare("SELECT * FROM comment
        WHERE $atribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $comments = [];

        foreach($results as $result)
        {
            $comment = new Comment($result);
            array_push($comments,$comment);
        }
        return $comments;
    }

    public function getComment(string $atribute, mixed $value): Comment
    {
        $stmt = $this->connection->prepare("SELECT * FROM comment
        WHERE $atribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();

        $result = $stmt->fetch();
        $comment = new Comment($result);
        return $comment;
    }

    public function getCommentCount(string $attribute,mixed $value): int
    {
        $stmt = $this->connection->prepare("SELECT * FROM comment
        WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();

        if(empty($stmt))
        {
            $count = 0;
            return $count;
        }

        $count = $stmt->rowCount();
        return $count;
    }

    public function registerReply(Comment $comment): void
    {
        $stmt = $this->connection->prepare("INSERT INTO comment (text, user_id, post_id, comment_id, time)
        VALUES (:text, :user_id, :post_id, :comment_id, :time)");
        $stmt->bindParam(':text',$comment->getText());
        $stmt->bindParam(':user_id',$comment->getUser_id());
        $stmt->bindParam(':post_id',$comment->getPost_id());
        $stmt->bindParam(':comment_id',$comment->getComment_id());
        $stmt->bindParam(':time',$comment->getTime());

        $stmt->execute();
    }

    public function registerComment(Comment $comment): void
    {
        $stmt = $this->connection->prepare("INSERT INTO comment (text, user_id, post_id, time)
        VALUES (:text, :user_id, :post_id, :time)");
        $stmt->bindParam(':text',$comment->getText());
        $stmt->bindParam(':user_id',$comment->getUser_id());
        $stmt->bindParam(':post_id',$comment->getPost_id());
        $stmt->bindParam(':time',$comment->getTime());

        $stmt->execute();
    }
}