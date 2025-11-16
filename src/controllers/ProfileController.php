<?php

namespace Reddit\controllers;
use Reddit\models\Community;
use Reddit\services\SessionService;

class ProfileController
{
    public function showProfile($filter)
    {
        $community = new Community();
        $session = new SessionService();
        $userId = $session->getFromSession('user_id');
        if($filter == "community")
        {
            $content = $community->getCommunity($userId);
            include "../view/profile.php";
        }
    }
}