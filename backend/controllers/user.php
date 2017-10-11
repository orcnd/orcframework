<?php if ( ! defined('basepath')) exit('No direct script access allowed');
class user_controller  extends orc_controller_base {

    function __construct() {
        parent::__construct();
    }

    function login() {
        if (isset($_POST['username']) && isset($_POST['password'])){
            $drm=User::login($_POST['username'],$_POST['password']);
            if ($drm===false) {
                $this->load->view('user/login',array('hata'=>'login info is wrong or something'));
            }else{
                lastpage(true);
            }
        }else{
            $this->load->view('user/login');
        }
    }

    function logout() {
        User::logout();
        redirect('');
    }
}
