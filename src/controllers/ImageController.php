<?php

namespace Reddit\controllers;

use Reddit\models\Image;
use Reddit\services\SessionService;

class ImageController extends Image
{
    public function communityImage($communityId)
    {
        $results = $this->getCommunityImage($communityId);

        return json_encode($results);
    }

    
}