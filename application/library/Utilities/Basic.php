<?php
class Utilities_Basic {
    public static function filterNoneUTF8($string){
        $regex = "/[\x{00a0}\x{fffe}\x{200b}]/u";
        return preg_replace($regex, "", $string);
    }
    public static function replaceSpecialToDash($str){
        return preg_replace(';[\'\"\\\/ ];', '_', $str);
    }
    public static function isValidPackageName($str){
	if( strlen($str) <= 3 ){
		return false;
	}
	if( strlen ($str) >1024){
		return false;
	}
        if( ! preg_match(';[^\w\.];', $str)){
		return $str;
	}
	return false;
    }
    public static function execCmd($command){
        $execResult = array('errorCode' => 0, 'stdOut' => '');
        try {
            $funReturn = exec($command, $execResult['stdOut'], $execResult['errorCode']);
            $execResult['execReturn'] = $funReturn;
            $execResult['command'] = $command;
            return $execResult;
        } catch (Exception $e) {
            throw new Utilities_Exception($e->getMessage());
        }
        return array();
    }
    protected static function addIncludePath(){
        return set_include_path(get_include_path() . ":" . APPLICATION_PATH . "application/library/RelyOnGears/");
    }
    public static function getBodyHash($apkPath){
        $cmd = "unzip -p {$apkPath} classes.dex | md5sum";
        exec($cmd, $output, $rs);
        if ($rs == 0) {
            $md5sum = substr($output[0], 0, 32);
            if (!empty($md5sum)) {
                return $md5sum;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public static function isMd5String($string){
        if (32 != strlen($string)) {
            return false;
        }
        if (!preg_match("/[^a-zA-Z\d]/", $string)) {
            return $string;
        }
        return false;
    }
    public static function isWord($string){
        if ( ! preg_match("/\W/", $string)) {
            return $string;
        }
        return false;
    }
    public static function getFileMIMEType($fileRealPath){
        if( ! file_exists($fileRealPath)){
            return false;
        }
        $finfo = finfo_open(FILEINFO_MIME_TYPE); 
        $mimeInfo = finfo_file($finfo, $fileRealPath); 
        finfo_close($finfo);
        return $mimeInfo;
    }
}
