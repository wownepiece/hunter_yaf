<?php
namespace Database;
use Yaf; // for Yaf_Registery
use PDO; //why doesn't new \PDO work??
class Mysql {

    final private function __construct(){}
    final private function __clone(){}
    //final private $paramList = array(
    //    'daily',
    //    'mprotect',
    //);

    //单例模式数组, 因为 数据库连接 不止在一个host上,故此; 数组众的 key 即为 配置文件中的 区块
    protected static $pdo = array();

    public static function getInstance( $param ) {

        if ( ! isset( self::$pdo[$param] ) ) {
            //是否需要声明
            self::$pdo[$param] = '';
        }

        //检查是否是 pdo对象
        if ( self::$pdo[$param] instanceof PDO ) {
            return self::$pdo[$param];
        }

        //YAF 解析配置文件
        $config = Yaf\Registry::get( 'config' )->mysql->$param;

        //构造pdo链接
        $dsn = "mysql:host=" . $config['host'] . ";port=" . $config['port'] . ";dbname=" . $config['dbname'];

        try {
            // return self::$pdo[$param] = DB::factory( $config );PDO::ATTR_PERSISTENT=>true,
            self::$pdo[$param] = new \PDO( $dsn, $config['username'], $config['password'], array(  PDO::MYSQL_ATTR_LOCAL_INFILE=>true ) );

            self::$pdo[$param] ->query( "use {$config['dbname']};" );
            self::$pdo[$param] ->query( "set names utf8" );

            return self::$pdo[$param];

        } catch ( PDOException $e ) {
            self::log( 'error.log', __FILE__.'Connection failed: ' . $e->getMessage() );
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
