<?php

namespace Reddit\repositories;

use Reddit\models\Db;
use Reddit\models\Post;

class PostRepository extends Db
{
    public function getAllPosts(int $limit): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM post ORDER BY time DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $posts = [];

        foreach($results as $result)
        {
            $post = new Post($result);
            array_push($posts,$post);
        }
        return $posts;
    }

    public function countPosts()
    {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM post");
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getPost(string $attribute, mixed $value): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM post WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $posts = [];

        foreach($results as $result)
        {
            $post = new Post($result);
            array_push($posts,$post);
        }
        return $posts;
    }

    public function getPostById(int $id): ?Post
    {
        $stmt = $this->connection->prepare("SELECT * FROM post WHERE id = :value");
        $stmt->bindParam(':value',$id);
        $stmt->execute();

        $result = $stmt->fetch();
        $post = new Post($result);
         
        return $post;
    }

    public function registerTextPost(Post $post): void
    {
        $stmt = $this->connection->prepare("INSERT INTO post (title, text, user_id, community_id, time)
        VALUES (:title, :text, :user_id, :community_id, :time)");
        $stmt->bindParam(':title',$post->getTitle());
        $stmt->bindParam(':text',$post->getText());
        $stmt->bindParam(':user_id',$post->getUser_id());
        $stmt->bindParam(':community_id',$post->getCommunity_id());
        $stmt->bindParam(':time',$post->getTime());

        $stmt->execute();

       
    }

    public function registerImagePost(Post $post): void
    {
        $stmt = $this->connection->prepare("INSERT INTO post (title, user_id, community_id, time)
        VALUES (:title, :user_id, :community_id, :time)");
        $stmt->bindParam(':title',$post->getTitle());
        $stmt->bindParam(':user_id',$post->getUser_id());
        $stmt->bindParam(':community_id',$post->getCommunity_id());
        $stmt->bindParam(':time',$post->getTime());

        $stmt->execute();

    }

    public function deletePost($postId)
    {
        $stmt = $this->connection->prepare("DELETE FROM post WHERE id = :id");
        $stmt->bindParam(':id',$postId);

        $stmt->execute();
    }
}