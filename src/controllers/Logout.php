<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Reddit\services\SessionService;

$session = new SessionService();

$session->logout();

header('Location: ../../index.php');