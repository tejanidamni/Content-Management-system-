<?php

  ob_start(); // output buffering is turned on
  session_start(); //turn on sessions
  //assign file paths to PHP constants
  //__FILE__ returns current path to this file
  // dirname() returns the path to the parent directory

  // echo dirname(__FILE__);   /Users/damnitejani/Sites/globe_bank/private


  define("PRIVATE_PATH",dirname(__FILE__) );
  define("PROJECT_PATH",dirname(PRIVATE_PATH));
  define("PUBLIC_PATH",PROJECT_PATH.'/public');
  define("SHARED_PATH",PRIVATE_PATH.'/shared');

  //Assign the root URL to a PHP constant
  // * Do not need to include the domain
  // * use same document root as web server
  // * can set a hardcoded value:
  // define("WWW_ROOT",/~damnitejani/globe_bank/public);
  // define("WWW_ROOT",'');
  // * can dynamically find everything in URL up to '/public'


  // echo $_SERVER['SCRIPT_NAME'];  /~damnitejani/globe_bank/public/staff/index.php
  $public_end = strpos($_SERVER['SCRIPT_NAME'],'/public') + 7;
  $doc_root = substr($_SERVER['SCRIPT_NAME'],0,$public_end);
  define("WWW_ROOT",$doc_root);

  require_once('functions.php');
  require_once('database.php');
  require_once('query_functions.php');
  require_once('validation_functions.php');
  require_once('auth_functions.php');

  $db = db_connect();
  $errors = [];


?>
