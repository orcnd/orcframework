<?php if ( ! defined('basepath')) exit('No direct script access allowed');
/**
 *  Controller class for navigating into code via uri
 */
class Controller {
    /**
     *  checks url for navigating calls necessary controller for work
     */
    static function init() {
        $uri=$_SERVER['REQUEST_URI'];
        $uri=str_replace('/'.basepath.'/','',$uri);
        if (trim($uri)=='') $uri=mainControllerName.'/'.mainControllerName;

        if (strpos($uri,'/')>-1) {
            $uri=explode('/',$uri);
        }else{
            $uri=array($uri,mainControllerName);
        }

        $fl=backend.'/controllers/'.$uri[0].'.php';

        if (file_exists($fl)) {
            include($fl);
            $cln=$uri[0].'_controller';

            if (!is_callable(array($cln,$uri[1]))) {
                if (!is_callable(array($cln,'ana'))) {
                    $cln=mainControllerName .'_controller';
                }
                $uri[1]=mainControllerName;
            }

            $a=new $cln();
            if (sizeof($uri)>2) {
                $params=$uri;
                unset($params[0]);
                unset($params[1]);

                call_user_func_array(array($a,$uri[1]),$params);
            }else{
                call_user_func(array($a,$uri[1]));
            }
        }else{
            header("HTTP/1.0 404 Not Found");
        }

    }
}
