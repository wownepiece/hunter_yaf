<?php

/**
 * @name Bootstrap
 * @author druid
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf\Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf\Bootstrap_Abstract
{

    public function _initConfig()
    {
        //把配置保存起来
        $configs = Yaf\Application::app()->getConfig();
        Yaf\Registry::set('config', $configs);
        Yaf\Dispatcher::getInstance()->autoRender($configs->views);

        if ($configs->debug) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL & ~E_NOTICE);
        } else {
            ini_set('display_errors', 0);
        }
    }

    public function _initDefineConst()
    {
        $config = Yaf\Registry::get('config')->toArray();
        define("GEARS_PATH", $config['gearsPath']);
        define("TMP_PATH", $config['tmpPath']);
        define("BASE_URL", $config['resourceBase']);
        define("IS_CLI", substr(PHP_SAPI_NAME(),0,3) === 'cli');
        define("TAB", "\t");
    }

    public function _initLogger()
    {
        $obj = new MonologClient();
        Yaf\Registry::set('logger', $obj->logger());
        Yaf\Registry::set('sysLogger', $obj->systemLogger());
    }

    public function _initPlugin(Yaf\Dispatcher $dispatcher)
    {
        //注册一个插件
        //$objSamplePlugin = new SamplePlugin();
        //$dispatcher->registerPlugin($objSamplePlugin);
    }

    public function _initRoute(Yaf\Dispatcher $dispatcher)
    {
        //在这里注册自己的路由协议,默认使用简单路由
        $config = array(
            "download" => array(
                "type" => "rewrite", 
                "match" => "/api/download", 
                "route" => array(
                    'controller' => "download", 
                    'action' => "encryptdown", 
                    'module' => "api"
                ),
            ),

            "downloadmulti" => array(
                "type" => "rewrite", 
                "match" => "/api/downloadmulti", 
                "route" => array(
                    'controller' => "download", 
                    'action' => "multidown", 
                    'module' => "api"
                ),
            ),
        );

        Yaf\Dispatcher::getInstance()->getRouter()->addConfig(new Yaf\Config\Simple($config));

    }

    public function _initView(Yaf\Dispatcher $dispatcher)
    {
        //在这里注册自己的view控制器，例如smarty,firekylin
    }
}
