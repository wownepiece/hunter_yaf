<?php
define('APPLICATION_PATH', dirname(__FILE__));

$application = new Yaf\Application( APPLICATION_PATH . "/conf/application.ini", 'develop');

$application->bootstrap()->run();
