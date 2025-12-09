<?php

namespace Reddit\models;
use Reddit\models\Db;

class Post extends Db
{
    public $title;
    public $text;
    public $image;
    public $user_id;
    public $community_id;
    public $time;

    public function getPost(string $attribute, mixed $value): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM post WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function titleLength($title)
    {
        return strlen($title) >= 2 && strlen($title) <= 300;
    }

    public function textLength($text)
    {
        return strlen($text) >= 2 && strlen($text) <= 1000;
    }

    public function registerTextPost($title, $text, $user_id, $community_id, $time)
    {
        $stmt = $this->connection->prepare("INSERT INTO post (title, text, user_id, community_id, time)
        VALUES (:title, :text, :user_id, :community_id, :time)");
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':text',$text);
        $stmt->bindParam(':user_id',$user_id);
        $stmt->bindParam(':community_id',$community_id);
        $stmt->bindParam(':time',$time);

        $stmt->execute();

        $lastId = $this->connection->lastInsertId();
        return $lastId;
    }

    public function registerImagePost($title, $user_id, $community_id, $time)
    {
        $stmt = $this->connection->prepare("INSERT INTO post (title, user_id, community_id, time)
        VALUES (:title, :user_id, :community_id, :time)");
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':user_id',$user_id);
        $stmt->bindParam(':community_id',$community_id);
        $stmt->bindParam(':time',$time);

        $stmt->execute();
    }

    public function deletePost($postId)
    {
        $stmt = $this->connection->prepare("DELETE FROM post WHERE id = :id");
        $stmt->bindParam(':id',$postId);

        $stmt->execute();
    }
}