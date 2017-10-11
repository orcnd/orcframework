<?php
//please encypt(misspelled for sure) this file

define('basepath','orcframework'); // base path for backend
define('baseurl','orcframework'); //base url for frontend
define('backend','backend');//backend directory
define('frontend','frontend'); //frontend directory
define('frontendViewDirectory','views'); //fronend view directory
define('mainControllerName','maincont'); // name for main controllers this is what controller class run for default
define('databaseConnection1','q2asdlkj2sadksaud'); //database connection 1 variable name
define('defaultDatabaseConnection',databaseConnection1); //default database connection

/**
 *  database connection settings you can make more
 *  type means database driver
 *  prefix works for database table
 */
${databaseConnection1}=(object)array(
  'type'        =>  'mysql/pdo',
  'prefix'      =>  'orcfrm_',
  'connection'  =>  new PDO('mysql:host=localhost;dbname=orcframeworktest','root','')
);

require("autoload.php"); //launches autoload process
