<?php

namespace Reddit\controllers;

use Reddit\models\Post;
use Reddit\models\Image;
use Reddit\services\SessionService;
use Reddit\services\TimeService;

class PostController extends Post
{
    public function textPost($title, $text, $communityId)
    {
        $session = new SessionService();
        $timeStamp = new TimeService();

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

        if(!isset($communityId))
        {
        $message = "You didnt send community Id";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!$this->titleLength($title))
        {
        $message = "Title cant be bigger then 300 letters and smaller then 2 letter";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        if(!$this->textLength($text))
        {
        $message = "Text cant be bigger then 1000 letters and smaller then 2 letter";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        $this->registerTextPost($title,$text,$user_id,$communityId,$time,$likes);

        header('Location: view/community.php?comm_id=<?=$communityId?>');
    }

    public function imagePost($title, $files,$communityId)
    {
        $session = new SessionService();
        $image = new Image();
        $timeStamp = new TimeService();

        $userId = $session->getFromSession('user_id');
        $time = $timeStamp->time;
        $likes = 0;

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

        if(!$this->titleLength($title))
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

        $this->registerImagePost($title,$userId,$communityId,$time,$likes);

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
        
    }
}