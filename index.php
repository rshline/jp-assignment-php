<?php

require_once 'Model.php';
require_once 'Controller.php';

$model = new UnitConversion();
$controller = new UnitConversionController($model);

$router = new Router();
$router->addRoute('/convert', 'POST', [$controller, 'convert']);
$router->addRoute('/units', 'GET', [$controller, 'getUnitList']);

$router->handle();
