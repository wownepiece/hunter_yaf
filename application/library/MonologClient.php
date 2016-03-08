<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor;
//use Monolog\Processor\IntrospectionProcessor;

class MonologClient{
    public $logger;
    public $sysLogger;
    protected $streamLogPath = '/tmp/';
    protected $publisherChannel = '';
    public function __construct(){

        $configs = Yaf\Registry::get('config');
        $this->streamLogPath = $configs->logger->stream->path.date("YW") ? : $this->streamLogPath;
        $this->publisherChannel = $configs->logger->publisher->channel;
    }
    public function customLogger( $channel ){
        $this->logger = new Logger( $channel );
        $this->logger->pushHandler(new StreamHandler($this->streamLogPath, Logger::DEBUG));
        $this->logger = $this->redisPublisher($this->logger);
        return $this->logger;
    }
    public function logger(){
        $this->logger = new Logger('test');
        $this->logger->pushHandler(new StreamHandler($this->streamLogPath, Logger::DEBUG));
        $this->logger = $this->redisPublisher($this->logger);
        return $this->logger;
    }
    public function systemLogger(){
        $this->sysLogger = new Logger("sys");
        $this->sysLogger -> pushHandler( new StreamHandler($this->streamLogPath, Logger::DEBUG));
        //$this->sysLogger = $this->redisPublisher($this->sysLogger, $this->pubChannelName);
        return $this->sysLogger;
    }

    protected function redisPublisher( Logger $obj){
        if( ! $obj instanceof Logger ){
            return false;
        }
        $obj->pushProcessor(function ($record) {
            $configs = Yaf\Registry::get('config');
            $publisherChannel = $configs->logger->publisher->channel;
            $redisCli = Database\RedisClient::getInstance("main");
            $pushResult = $redisCli ->publish($publisherChannel, $record['message']);
            return $record;
        });
        return $obj;
    }
}
