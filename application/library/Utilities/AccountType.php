<?php
class Utilities_AccountType{

    public static function isEmail( $account ){
        $length = strlen($account);
        if( $length < 5 || $length > 100 ){
            return false;
        }
        $pattern = '/^([\w\-\.]+)@(([0-9a-zA-Z\-]+\.)+[a-zA-Z]{2,4})$/';
        if( preg_match($pattern, $account) ){
            return true;
        }
        return false;
    }

    public static function isUserName( $account ){
        if( extension_loaded('mbstring')){
            $length = mb_strlen($account, 'utf-8');
        }else{
            $length = strlen($account);
        }
        if( $length < 2 || $length > 14){
            return false;
        }
        $pattern = '/^[\w\.\x{4e00}-\x{9fa5}]+$/u';
        if( preg_match($pattern, $account) ){
            return true;
        }
        return false;
    }
    public static function isCellNumber( $account ){
        $length = strlen($account);
        if( 11 != $length){
            return false;
        }
        $pattern = '/^(13|14|15|18)[0-9]{9}$/';
        if( preg_match($pattern, $account) ){
            return true;
        }
        return false;
    }
  
}
