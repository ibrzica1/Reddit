<?php

namespace Reddit\repositories;

use Reddit\models\Db;
use Reddit\models\Image;

class ImageRepository extends Db
{
    public function getUploadedImages(string $attribute,mixed $value): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM image WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $images = [];

        foreach($results as $result)
        {
            $image = new Image($result);
            array_push($images,$image);
        }
        return $images;
    }

    public function getCommunityImage(int $id): Image
    {
        $stmt = $this->connection->prepare("SELECT * FROM image WHERE community_id = :id");
        $stmt->bindParam(':id',$id);
        $stmt->execute();

        $result = $stmt->fetch();
        $image = new Image($result);
        return $image;
    }

    public function getPostImage(int $id): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM image WHERE post_id = :id");
        $stmt->bindParam(':id',$id);
        $stmt->execute();

        $results = $stmt->fetchAll();
        $images = [];

        foreach($results as $result)
        {
            $image = new Image($result);
            array_push($images,$image);
        }
        return $images;
    }

    public function uploadImage(string $tmpName, string $name, int $postId, int $userId): void
    {
        $finalPath = __DIR__ . "/../../images/uploaded/$name";

        move_uploaded_file($tmpName, $finalPath);
        $stmt = $this->connection->prepare("INSERT INTO image (name,post_id,user_id) 
        VALUES (:name,:post_id,:user_id)");
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':post_id',$postId);
        $stmt->bindParam(':user_id',$userId);
        $stmt->execute();
    }

    public function uploadCommunityImage(string $tmpName, string $name, int $communityId, int $userId): void
    {
        $finalPath = __DIR__ . "/../../images/community/$name";

        move_uploaded_file($tmpName, $finalPath);
        $stmt = $this->connection->prepare("INSERT INTO image (name,community_id,user_id) 
        VALUES (:name,:community_id,:user_id)");
        $stmt->bindParam(':name',$name);
        $stmt->bindParam(':community_id',$communityId);
        $stmt->bindParam(':user_id',$userId);
        $stmt->execute();
    }

    public function deleteImage(string $attribute, mixed $value): void
    {
        $stmt = $this->connection->prepare("DELETE FROM image WHERE $attribute = :value");
        $stmt->bindParam(':value',$value);

        $stmt->execute();
    }
}