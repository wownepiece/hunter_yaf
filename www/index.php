<?php
date_default_timezone_set("Asia/Shanghai");
define('APPLICATION_PATH', dirname(dirname(__FILE__)) . '/');
define('TAB', "\t");
define('CODE_ENV', 'product');
//define('CODE_ENV', 'develop');
$application = new Yaf\Application( APPLICATION_PATH . "conf/application.ini", CODE_ENV);
$application->bootstrap()->run();
