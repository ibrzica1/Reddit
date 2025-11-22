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
    public $likes;

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

    public function registerTextPost($title, $text, $user_id, $community_id, $time, $likes)
    {
        $stmt = $this->connection->prepare("INSERT INTO post (title, text, user_id, community_id, time, likes)
        VALUES (:title, :text, :user_id, :community_id, :time, :likes)");
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':text',$text);
        $stmt->bindParam(':user_id',$user_id);
        $stmt->bindParam(':community_id',$community_id);
        $stmt->bindParam(':time',$time);
        $stmt->bindParam(':likes',$likes);

        $stmt->execute();
    }

    public function registerImagePost($title, $user_id, $community_id, $time, $likes)
    {
        $stmt = $this->connection->prepare("INSERT INTO post (title, user_id, community_id, time, likes)
        VALUES (:title, :user_id, :community_id, :time, :likes)");
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':user_id',$user_id);
        $stmt->bindParam(':community_id',$community_id);
        $stmt->bindParam(':time',$time);
        $stmt->bindParam(':likes',$likes);

        $stmt->execute();
    }
}