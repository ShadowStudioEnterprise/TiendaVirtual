<?php
use ProyectoWeb\core\App;
use ProyectoWeb\database\Connection;

$config = require_once __DIR__ . '/../app/config.php';
App::bind('config', $config);
App::bind('connection', Connection::make(App::get('config')['database']));

