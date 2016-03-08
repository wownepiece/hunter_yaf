<?php
namespace Database;
use Yaf;
use MongoClient;
class MongoDBClient{
    final private function __construct(){}
    final private function __clone(){}
    protected static $clients  = array();

    public static function getInstance( $configSection ){
        $logger = Yaf\Registry::get('sysLogger');
        if( ! isset( self::$clients[$configSection])){
            self::$clients[$configSection] = '';
        }

        if( self::$clients[$configSection] instanceof MongoClient ){
            return self::$clients[$configSection];
        }

        $constructOptions = array();

        $config = Yaf\Registry::get('config')->mongodb->$configSection->toArray();
        $config = array_filter($config);

        if( isset($config['username'])){
            $constructOptions = array(
                'username'=>$config['username'], 
                'password'=>$config['password'],
            );
        }

        try {
            self::$clients[$configSection] = new MongoClient("mongodb://" . $config["host"] . ":" . $config["port"] . "/" .$config['database'], $constructOptions );
            self::$clients[$configSection] = self::$clients[$configSection]->selectDB($config['database']);
            $logger->addInfo(__METHOD__.TAB."mongo connected" );
            return self::$clients[$configSection];
        }catch ( MongoConnectionException $e ){
            $logger->addCritical(__METHOD__."\tConnection Failed: \t". $e->getMessage(), $config );
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
