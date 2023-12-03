<?php

use Config\Config;
use Core\Container\Container;

require_once __DIR__ . '/../vendor/autoload.php';


return new Container(
        require_once __DIR__ . '/../config/services.php',

    Config::getInstance()->addConfigs(
        require_once __DIR__ . '/../config/configs.php',
    )
);