<?php

namespace Reddit\controllers;

use Reddit\models\Community;
use Reddit\models\Image;
use Reddit\services\SessionService;
use Reddit\services\TimeService;

class CommunityController extends Community
{
    public function createCommunity($name, $description, $files)
    {
        $session = new SessionService();
        $timeStamp = new TimeService();
        $image = new Image();

        $user_id = $session->getFromSession('user_id');
        $time = $timeStamp->time;

        if(!isset($name))
        {
        $message = "You didnt send name";
        $session->setSession("message",$message);
        header("Location: view/createCommunity.php");
        exit();
        }

        if(!isset($description))
        {
        $message = "You didnt send description";
        $session->setSession("message",$message);
        header("Location: view/createCommunity.php");
        exit();
        }

        if(!isset($files))
        {
        $message = "You didnt send files";
        $session->setSession("message",$message);
        header("Location: view/createCommunity.php");
        exit();
        }

        if(!$this->nameLength($name))
        {
        $message = "Name cant be bigger then 21 letters and smaller then 3 letter";
        $session->setSession("message",$message);
        header("Location: view/createCommunity.php");
        exit();
        }

        if(!$this->descriptionLength($description))
        {
        $message = "Description cant be bigger then 500 letters and smaller then 3 letter";
        $session->setSession("message",$message);
        header("Location: view/createCommunity.php");
        exit();
        }

        $uploadedImages = [
                'name' => $files['name'],
                'type' => $files['type'],
                'tmp_name' => $files['tmp_name'],
                'error' => $files['error'],
                'size' => $files['size']
            ];
        
            if(!$image->isValidSize($uploadedImages['size']))
            {
            $message = "Picture {$uploadedImages['name']} is to big";
            $session->setSession("message",$message);
            header("Location: view/createCommunity.php");
            exit();
            }

            if(!$image->isValidDimension($uploadedImages['tmp_name']))
            {
            $message = "Picture {$uploadedImages['name']} 
            cant be wider than 1920 or higher than 1024";
            $session->setSession("message",$message);
            header("Location: view/createCommunity.php");
            exit();
            }

            if(!$image->isValidExtension($uploadedImages['name']))
            {
            $message = "Picture {$uploadedImages['name']} 
            has invalid extension";
            $session->setSession("message",$message);
            header("Location: view/createCommunity.php");
            exit();
            }
        
        $this->registerCommunity($name,$description,$user_id,$time);
        $communityId = $this->connection->lastInsertId();
        
        $randomName = $image->generateRandomName('jpg');
        $imageFolder = "../images/community/";

        if(!is_dir($imageFolder))
        {
        mkdir($imageFolder, 0755, true);
        }

        $image->uploadCommunityImage($uploadedImages['tmp_name'],$randomName,$communityId,$user_id);
    }
}