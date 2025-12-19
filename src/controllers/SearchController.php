<?php

namespace Reddit\controllers;

use Reddit\models\Search;
use Reddit\services\SessionService;

class SearchController extends Search
{
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

        $results = $this->SearchAll($search);

        return json_encode($results);
    }
}