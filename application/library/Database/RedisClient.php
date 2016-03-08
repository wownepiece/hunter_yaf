<?php
namespace Database;
use Yaf;
use Redis;
class RedisClient{
    final private function __construct(){}
    final private function __clone(){}
    protected static $clients  = array();

    public static function getInstance( $configSection ){
        $logger = Yaf\Registry::get('sysLogger');
        if( ! isset( self::$clients[$configSection])){
            self::$clients[$configSection] = '';
        }

        if( self::$clients[$configSection] instanceof Redis ){
            return self::$clients[$configSection];
        }

        $config = Yaf\Registry::get('config')->redis->$configSection;

        try {
            self::$clients[$configSection] = new Redis();
            self::$clients[$configSection] -> connect($config['host'], $config['port'], 0);
            self::$clients[$configSection]->auth($config['password']);
            $logger->addInfo(__METHOD__.TAB."Redis connected" );
            return self::$clients[$configSection];
        }catch ( Exception $e ){
            $logger->addCritical(__METHOD__."\tRedis Connection Failed: \t". $e->getMessage(), $config);
            //self::log('redis_connect_error.log', __FILE__."\tConnection Failed: \t". $e->getMessage(), FILE_APPEND);
            return false;
        }
    }

    protected static function log(  $data ,$fileName='error.log' ) {
        $logPath = '/tmp/'.basename(__FILE__,'.php').'/';
        if ( !file_exists( $logPath ) ) {
            mkdir( $logPath );
        }
        $data = '['.date( 'Y-m-d H:i:s' ).']-->'.$data."\n";
        file_put_contents( $logPath.$fileName, $data, FILE_APPEND );
    }
}
