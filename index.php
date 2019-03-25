<?php

require dirname(__FILE__) . '\vendor\autoload.php';

$router = new Core\Router();

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('save', ['controller' => 'Home', 'action' => 'save']);

$router->dispatch($_SERVER['QUERY_STRING']);