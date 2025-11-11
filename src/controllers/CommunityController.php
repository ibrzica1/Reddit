<?php

namespace Reddit\controllers;

use Reddit\models\Community;
use Reddit\services\SessionService;
use Reddit\services\TimeService;

class CommunityController extends Community
{
    public function createCommunity($name, $description)
    {
        $session = new SessionService();
        $timeStamp = new TimeService();

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

        $this->registerCommunity($name,$description,$user_id,$time);
    }
}