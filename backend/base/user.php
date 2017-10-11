<?php if ( ! defined('basepath')) exit('No direct script access allowed');
/**
 * User Class defined as static for global usage
 */
class User {
    private static $db;
    public static $user=false;
    private static $databasetable='users';
    private static $logintimeout=10800; //3 saat ederindeki saniye
    private static $initdrm=false;

    private function __clone() { }

    /**
     * initializing session and stuff
     */
    static function init() {
        @session_start();
        self::$db=new Database;
        self::$initdrm=true;
        self::loginCheck();
    }

    /**
     * login function
     *
     *  @param  string  $username users name
     *  @param  string  $password users password
     *  @return boolean if logged returns true and registers it to session
     */
    static function login($username,$password) {
        if (self::$user===false) {

            $snc=self::$db->where('username',$username)->get(self::$databasetable)->row();

            if (isset($snc->password)) {

                $pv=self::validatePassword($password,$snc->password);
                if ($pv) {

                    $logintime=time();

                    self::$user=(object)array('username'=>$username,'role'=>$snc->role);
                    $smphs=self::generateHash($snc->username,$snc->role,$logintime);

                    $_SESSION['login']=$username;
                    $_SESSION['loginhash']=$smphs;

                    self::$db->where('username',$username)->update(self::$databasetable,array('lasthash'=>$smphs));
                    self::$db->where('username',$username)->update(self::$databasetable,array('lastlogin'=>$logintime));
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * generate hash for session purposes
     *
     *  @param  string  $username username
     *  @param  int $role role
     *  @param  int $logintime  login time in linux time format
     *
     *  @return string hashed data
     */
    private static function generateHash($username,$role,$logintime) {
        $timeout=$logintime+self::$logintimeout;
        $dt=(object)array('username'=>$username,'role'=>$role,'timeout'=>$timeout);
        return md5('gecici' . json_encode($dt) . 'bir tuz');
    }

    /**
     * checks login status
     *
     *  @return boolean return true if user logged in
     */
    private static function loginCheck() {
        if (isset($_SESSION['login']) && isset($_SESSION['loginhash'])) {

            $r=self::$db->where('username',$_SESSION['login'])->get(self::$databasetable)->row();

            if (isset($r->lasthash) && isset($r->lastlogin)) {

                $th=self::generateHash($r->username,$r->role,$r->lastlogin);

                if ($th==$_SESSION['loginhash']) {
                    $logintime=time();
                    self::$user=(object)array('username'=>$r->username,'role'=>$r->role);
                    $smphs=self::generateHash($r->username,$r->role,$logintime);
                    $_SESSION['loginhash']=$smphs;
                    self::$db->where('username',$r->username)->update(self::$databasetable,array('lasthash'=>$smphs));
                    self::$db->where('username',$r->username)->update(self::$databasetable,array('lastlogin'=>$logintime));
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * logs out user deletes session
     *
     *  @return boolean always true
     */
    static function logout() {
        if (self::$user!==false) {
            self::$db->where('username',self::$user->username)->update(self::$databasetable,array('lasthash'=>''));
            if (isset($_SESSION['login']) && isset($_SESSION['loginhash'])) {
                $_SESSION['login']='';
                $_SESSION['loginhash']='';
            }
        }
        self::$user=false;
        return true;
    }

    /**
     * enycrpts password in a certain way
     *
     *  @param  string  $ps password
     *  @param  string  $randomsalt random salt for hash
     *  @param  string  $saltsr salt start position which is gonna be hidden in password
     *
     *  @return string  encyrpted password
     */
    static function generatePassword($ps,$randomsalt='',$saltsr=-1) {
        $ters=array();
        for($i=0;$i<strlen($ps);$i++) {
            $ters[strlen($ps)-$i-1]=substr($ps,$i,1);
        }

        $a=$ters[0];
        $ters[0]=$ters[sizeof($ters)-1];
        $ters[sizeof($ters)-1]=$a;
        unset($a);

        $tersstr='';
        for($i=0;$i<sizeof($ters);$i++) {
            $tersstr.=$ters[$i];
        }
        if ($randomsalt=='') $randomsalt=substr(md5(rand(0,123).time()),0,8);
        $hashed=md5($tersstr.$randomsalt);

        if ($saltsr==-1) $saltsr=rand(5,7);
        $hashed_salted=substr($hashed,0,$saltsr).$randomsalt.substr($hashed,$saltsr,strlen($hashed)-$saltsr);
        return $hashed_salted;
    }

    /**
     * compares form input password to encyrpted password in user class
     *
     *  @param  string  $passwd clean password
     *  @param  string  $hashed encypted password
     *
     *  @return boolean if password matches with encyrpted one returns true
     */
   static function validatePassword($passwd,$hashed) {
        $tuzlar=array();
        $tuzlar[5]=substr($hashed,5,8);
        $tuzlar[6]=substr($hashed,6,8);
        $tuzlar[7]=substr($hashed,7,8);
        $ppas=array();
        $ppas[5]=self::generatePassword($passwd,$tuzlar[5],5);
        $ppas[6]=self::generatePassword($passwd,$tuzlar[6],6);
        $ppas[7]=self::generatePassword($passwd,$tuzlar[7],7);

        foreach ($ppas as $k=>$ps) {
            if ($ps==$hashed) {
                return true;
            }
        }

        return false;
    }

    /**
     *  creates user this need improvments
     *
     *  @param  string  $username username
     *  @param  string  $password password
     *  @param  int $role role
     *
     *  @return int user id which is created
     */
    static function createUser($username,$password,$role) {
        $ps=self::generatePassword($password);
        $id=self::$db->insert('users',array(
            'username'  =>  $username,
            'password'  =>  $ps,
            'role'      =>  $role
        ));
        return $id;
    }
}
