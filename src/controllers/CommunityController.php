<?php

namespace Reddit\controllers;

use Reddit\models\Community;
use Reddit\models\Image;
use Reddit\services\SessionService;
use Reddit\services\TimeService;
use Reddit\repositories\CommunityRepository;

class CommunityController extends CommunityRepository
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

        if(!Community::nameLength($name))
        {
        $message = "Name cant be bigger then 21 letters and smaller then 3 letter";
        $session->setSession("message",$message);
        header("Location: view/createCommunity.php");
        exit();
        }

        if(!Community::descriptionLength($description))
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
            
        $newCommunity = new Community([
            'id' => NULL,
            'name' => $name,
            'description' => $description,
            'user_id' => $user_id,
            'time' => $time
        ]);

        $this->registerCommunity($newCommunity);
        $communityId = $this->connection->lastInsertId();
        
        $randomName = $image->generateRandomName('jpg');
        $imageFolder = "../images/community/";

        if(!is_dir($imageFolder))
        {
        mkdir($imageFolder, 0755, true);
        }

        $image->uploadCommunityImage($uploadedImages['tmp_name'],$randomName,$communityId,$user_id);

        header("Location: view/profile.php?tab=communities");
        exit();
    }

    public function deleteCommunityController(int $communityId): void
    {
        $session = new SessionService();
        $image = new Image();

        if(!isset($communityId))
        {
        $message = "You didnt send community id";
        $session->setSession("message",$message);
        header("Location: view/profile.php");
        exit();
        }

        $this->deleteCommunity($communityId);
        $commImg = $image->getCommunityImage($communityId);
        $fileName = $commImg['name'];
        $path = 'images/community/'. $fileName;

        if (file_exists($path)) {
            unlink($path);
        }

        $image->deleteImage("community_id",$communityId);

        header("Location: view/profile.php");
    }

    public function searchCommunityConntroller(string $search): string
    {
        $session = new SessionService();

        if(!isset($search))
        {
        $message = "You didnt send search content";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        $results = $this->searchCommunity("name",$search);

        return json_encode($results);
    }
}