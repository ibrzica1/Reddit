<?php

namespace Reddit\controllers;

use Reddit\repositories\ImageRepository;

class ImageController extends ImageRepository
{
    public function communityImage($communityId)
    {
        $results = $this->getCommunityImage($communityId);

        if (!$results) {
            return null;
        }

        return $results;
    }

    
}