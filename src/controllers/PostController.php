<?php

namespace Reddit\controllers;

use Reddit\models\Post;
use Reddit\models\Image;
use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\controllers\NotificationController;
use Reddit\repositories\PostRepository;

class PostController extends PostRepository
{
    public function textPost(string $title,string $text,int $communityId): void
    {
        $session = new SessionService();
        $timeStamp = new TimeService();
        $notificationController = new NotificationController();

        $user_id = $session->getFromSession('user_id');
        $time = $timeStamp->time;
        $likes = 0;
    
        if(!isset($title))
        {
        $message = "You didnt send title";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!isset($text))
        {
        $message = "You didnt send text";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!isset($communityId) || empty($communityId))
        {
        $message = "You didnt send community Id";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!Post::titleLength($title))
        {
        $message = "Title cant be bigger then 300 letters and smaller then 2 letter";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!Post::textLength($text))
        {
        $message = "Text cant be bigger then 1000 letters and smaller then 2 letter";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        $newPost = new Post([
            'id' => NULL,
            'title' => $title,
            'text' => $text,
            'user_id' => $user_id,
            'community_id' => $communityId,
            'time' => $time
        ]);

        $this->registerTextPost($newPost);
        $postId = $this->connection->lastInsertId();
        $notificationController->postNotification($user_id,$postId,$communityId,$time);

        header("Location: view/community.php?comm_id=$communityId");
        
    }

    public function imagePost($title, $files,$communityId)
    {
        $session = new SessionService();
        $image = new Image();
        $timeStamp = new TimeService();
        $notificationController = new NotificationController();

        $userId = $session->getFromSession('user_id');
        $time = $timeStamp->time;

        if(!isset($title))
        {
        $message = "You didnt send title";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!isset($files))
        {
        $message = "You didnt send files";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!isset($communityId))
        {
        $message = "You didnt send community Id";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!Post::titleLength($title))
        {
        $message = "Title cant be bigger then 300 letters and smaller then 2 letter";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        foreach($files['name'] as $key => $file)
        {
            $uploadedImages = [
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            ];

            if(!$image->isValidSize($uploadedImages['size']))
            {
            $message = "Picture {$uploadedImages['name']} is to big";
            $session->setSession("message",$message);
            header("Location: view/createPost.php");
            exit();
            }

            if(!$image->isValidDimension($uploadedImages['tmp_name']))
            {
            $message = "Picture {$uploadedImages['name']} 
            cant be wider than 1920 or higher than 1024";
            $session->setSession("message",$message);
            header("Location: view/createPost.php");
            exit();
            }

            if(!$image->isValidExtension($uploadedImages['name']))
            {
            $message = "Picture {$uploadedImages['name']} 
            has invalid extension";
            $session->setSession("message",$message);
            header("Location: view/createPost.php");
            exit();
            }
        }

        $newPost = new Post([
            'id' => NULL,
            'title' => $title,
            'user_id' => $userId,
            'community_id' => $communityId,
            'time' => $time
        ]);

        $this->registerImagePost($newPost);
        $postId = $this->connection->lastInsertId();

        foreach($files['name'] as $key => $file)
        {
            $uploadedImages = [
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            ];

            $randomName = $image->generateRandomName('jpg');
            $imageFolder = "../images/uploaded/";

            if(!is_dir($imageFolder))
            {
            mkdir($imageFolder, 0755, true);
            }

            $image->uploadImage($uploadedImages['tmp_name'],$randomName,$postId,$userId);
        }
        
        $notificationController->postNotification($userId,$postId,$communityId,$time);
        header("Location: view/comment.php?post_id=$postId");
        exit();
    }

    public function deletePostController($postId,$location)
    {
        $session = new SessionService();
        $image = new Image();

        if(!isset($postId))
        {
        $message = "You didnt send post Id";
        $session->setSession("message",$message);
        header("Location: view/$location.php");
        exit();
        }

        if(!isset($location))
        {
        $message = "You didnt send location";
        $session->setSession("message",$message);
        header("Location: view/index.php");
        exit();
        }

        $this->deletePost($postId);
        $postImages = $image->getPostImage($postId);
        foreach($postImages as $postImage)
        {
            $fileName = $postImage['name'];
            $path = 'images/uploaded/'. $fileName;

            if (file_exists($path)) {
                unlink($path);
            }

            $image->deleteImage("post_id",$postId);
        }

        header("Location: view/$location.php");
        exit();
        
    }
}