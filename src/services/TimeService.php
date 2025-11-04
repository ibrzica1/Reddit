<?php

namespace Reddit\services;

class TimeService
{
    private $time;

    public function __construct()
    {
        $this->time = date('m/d/Y h:i:s a', time());
    }

    public function calculateTime(string $timeStamp)
    {
        $diff = time() - strtotime($timeStamp);

        $minutes = floor($diff / 60);
        $hours   = floor($diff / 3600);
        $days    = floor($diff / 86400);
        $months  = floor($diff / 2592000);  
        $years   = floor($diff / 31536000); 

        if ($minutes < 1) {
            return "Less than 1 min ago";
        } elseif ($minutes < 60) {
            return "$minutes min ago";
        } elseif ($hours < 24) {
            return "$hours hours ago";
        } elseif ($days < 30) {
            return "$days days ago";
        } elseif ($months < 12) {
            return "$months months ago";
        } else {
            return "$years years ago";
        }
    }
}