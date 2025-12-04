<?php

namespace Reddit\models;
use Reddit\models\Db;

class Comment extends Db
{
    public $text;
    public $user_id;
    public $post_id;
    public $time;

    public function getComments($atribute,$value)
    {
        $stmt = $this->connection->prepare("SELECT * FROM comment
        WHERE $atribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getCommentCount($attribute,$value)
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

    public function registerComment($text,$user_id,$post_id,$time)
    {
        $stmt = $this->connection->prepare("INSERT INTO comment (text, user_id, post_id, time)
        VALUES (:text, :user_id, :post_id, :time)");
        $stmt->bindParam(':text',$text);
        $stmt->bindParam(':user_id',$user_id);
        $stmt->bindParam(':post_id',$post_id);
        $stmt->bindParam(':time',$time);

        $stmt->execute();
    }

    public function commentLength($text)
    {
        return strlen($text) < 500;
    }
}