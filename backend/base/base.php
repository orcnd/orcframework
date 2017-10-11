<?php if ( ! defined('basepath')) exit('No direct script access allowed');
/** class base for start */
class orc_base_class_for_base_stuff {
    var $load;
    function __construct() {
        $this->load=new orc_base_loader();
    }
}

/**
 * view loader for frontend
 */
class viewpage extends orc_base_class_for_base_stuff {
    /**
     * rendered value in variable for caching puposes
     *
     *  @var  string
     */
    var $rendered;
    /**
     * renders the view file with variables
     */
    function render($page,$vars) {
        extract($vars);

        ob_start();
        include($page);
        $this->rendered= ob_get_clean();
        return $this->rendered;
    }
}



/**
 * ci like loader class for models and views
 */
class orc_base_loader {
    /**
    * Load model into your class
    * @param  string $model model class name for loading
    * @return object  loaded class
    */
    function model($model) {
        $pg=backend .'/models/' . $model . '.php';
        include($pg);
        $vr=new $model();
        $this->$model=$vr;
        return $vr;
    }

    /**
    * Loads view for frontend
    *
    * @param  string  $view view name and Directory
    * @param  array  $vars variables will send to view file
    * @param  boolean $print  if it is true prints the contents of view, else returns as string
    * @return mixed it depends $print parameter
    */
    function view($view,$vars=array(),$print=true) {
        $pg=frontend . '/' .frontendViewDirectory .'/'.$view . '.php';
        $newpage=new viewpage();
        $vr=$newpage->render($pg,$vars);

        if (!$print) {
            return $vr;
        }else{
            echo $vr;
            return true;
        }
    }
}

/**
 *  Controller base model for building controllers
 *  <code>
 *  class example_controller extends orc_controller_base {
 *    function mybusiness() {
 *      $this->load->view("mypage");
 *    }
 *  }
 *  </code>

 */
class orc_controller_base extends orc_base_class_for_base_stuff {
    function __construct() {
        parent::__construct();
    }
}

/**
 *  model base model for building models
 *  <code>
 *  class example_model extends orc_controller_base {
 *    function mybusiness() {
 *      $db=Database::getInstance();
 *      $snc=$db->where('status','is good')->get('mybusiness')->result();
 *      return $snc;
 *    }
 *  }
 *  </code>
 */
class orc_model_base extends orc_base_class_for_base_stuff {
    function __construct() {
        parent::__construct();
    }
}
