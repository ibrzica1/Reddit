<?php

namespace Reddit\models;

class Community 
{
    public $id;
    public $name;
    public $description;
    public $user_id;
    public $time;

    public function __construct($array)
    {
        $this->id = $array['id'];
        $this->name = $array['name'];
        $this->description = $array['description'];
        $this->user_id = $array['user_id'];
        $this->time = $array['time'];
    }

    public static function nameLength($name)
    {
        return strlen($name) >= 3 && strlen($name) <= 21;
    }

    public static function descriptionLength($description)
    {
        return strlen($description) >= 3 && strlen($description) <= 500;
    }
    
}