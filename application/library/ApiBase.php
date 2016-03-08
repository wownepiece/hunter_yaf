<?php
class ApiBase extends Yaf\Controller_Abstract{
    protected $logger = '';
    protected $errorCode = 0;
    protected $errorMsg = '';
    protected $userInfo = array();
    protected $QID = false;
    protected function init(){
        //TODO redis session 
        header('Content-Type: application/json; charset=utf-8');
        if ('/api/login/account' != parent::getRequest()->getRequestUri()){
            if( ! $userInfo = Login::checkToken()){
                $errorCode = Consts_Error::CODE_6000;
                $errorMsg = Consts_Error::getErrorMsg($errorCode);
                $this->retrieveJson($errorCode, $errorMsg, array());
                exit();
            }
            $this->QID = $userInfo['qid'];
        }
        $this->getLogger();
        $this->logger->addInfo(__METHOD__.TAB.$_SERVER['REQUEST_URI'], $_REQUEST);
        $this->initController();
    }
    protected function initController(){
    }
    protected function getLogger(){
        return $this->logger = Yaf\Registry::get('logger');
    }
    protected function getQID(){
        return Login::getQID();
    }
    public function retrieveJson($errorCode, $errorMsg='', array $data = array() ){
        header('Content-Type: application/json; charset=utf-8');
        $msgBody = array(
            "errcode"=>$errorCode, 
            "errmsg"=>$errorMsg, 
            "data"=>$data,
        );
        echo json_encode($msgBody);
    }
}
