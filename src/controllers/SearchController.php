<?php

namespace Reddit\controllers;

use Reddit\models\Search;
use Reddit\services\SessionService;

class SearchController extends Search
{
    public function profileSearch($search,$userId)
    {
        $session = new SessionService();

        if(!isset($search))
        {
        $message = "You didnt send search content";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        $results = $this->searchProfile($search,$userId);

        return $results;
    }

    public function allSearch($search)
    {
        $session = new SessionService();

        if(!isset($search))
        {
        $message = "You didnt send search content";
        $session->setSession("message",$message);
        header("Location: view/createPost.php");
        exit();
        }

        $results = $this->searchAll($search);

        return $results;
    }
}