<?php if ( ! defined('basepath')) exit('No direct script access allowed');
class maincont_controller extends orc_controller_base{
    function __construct() {
    	parent::__construct();
    }

    function maincont() {
      lastpage(false);
      $this->load->view('homepage');
    }

    function otherpage() {
      lastpage(false);
      $this->load->view('otherpage');
    }
}
