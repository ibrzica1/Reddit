<?php

namespace Reddit\models;
use Reddit\models\Db;

class Post
{
    public $title;
    public $text;
    public $image;
    public $user_id;
    public $likes;

    public function titleLength($title)
    {
        return strlen($title) >= 2 && strlen($title) <= 300;
    }

    public function textLength($text)
    {
        return strlen($text) >= 2 && strlen($text) <= 1000;
    }

    
}