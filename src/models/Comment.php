<?php

namespace Reddit\models;
use Reddit\models\Db;

class Comment extends Db
{
    public $text;
    public $user_id;
    public $post_id;
    public $time;

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