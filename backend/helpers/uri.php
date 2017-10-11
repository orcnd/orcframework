<?php if ( ! defined('basepath')) exit('No direct script access allowed');

/**
 *  gives full url with specified uri, burl stands for base url similar to ci
 *
 *  @param  string  $uri  addon uri for url
 *  @param  string  $return returns result as a string if it is true, prints it if it is false
 *  @return mixed   depends of $return parameter
 */
function burl($uri='',$print=false){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }else{
        $protocol = 'http';
    }
    $dt= $protocol . "://" . $_SERVER['HTTP_HOST']. '/' .baseurl;
    if (substr($dt,-1)=='/') $dt=substr($dt,0,strlen($dt)-1);
    if ($print) echo $dt . '/' . $uri;
    return $dt . '/' . $uri;
}

/**
 * redirects to specified uri
 */
function redirect($uri='') {
    $uri=str_replace('//','/',$uri);
    $adr=burl('');
    if ($uri!='') $adr.='/' . $uri;

    $uri=str_replace('//','/',$uri);
    header("location: " . $adr);
}

/**
 *  redirects to last page or saves last page to session
 *
 *  @param  boolean  $redirect redirects if it is true, records current page to session if it is false
 */
function lastpage($redirect=true) {
    @session_start();
    if ($redirect) {
        $url=burl('');
        if (isset($_SESSION['lastpage'])) $url=$_SESSION['lastpage'];
        header('location: ' . $url);
    }else{
        $url= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $_SESSION['lastpage']=$url;
    }
}
