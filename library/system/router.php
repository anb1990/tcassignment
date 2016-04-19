<?php 
if(session_id() === ""){
    session_start();
}

$path = ROOT . '/' . 'config' . '/' . 'config.php';
require_once ($path);

// Autoload any classes that are required
spl_autoload_register(function($className) {

    $rootPath = ROOT . '/';
    $valid = false;
   
    // check root directory of library/classes
    $valid = file_exists($classFile = $rootPath . 'library/system/classes/' . $className . '.class.php');
    
    // if no any, find library/mvc
    if(!$valid){
        $valid = file_exists($classFile = $rootPath . 'library/system/mvc/' . $className . '.class.php');
    }     
    // if no any, find application/controllers
    if(!$valid){
        $valid = file_exists($classFile = $rootPath . 'application/controllers/' . $className . '.php');
    } 
    // if no any, find application/models
    if(!$valid){
        $valid = file_exists($classFile = $rootPath . 'application/models/' . $className . '.php');
    }  
  

    if($valid){
       require_once($classFile); 
    } 
});



MyHelpers::removeMagicQuotes();


MyHelpers::unregisterGlobals();


$router = new Router($_route);


$router->dispatch();


session_write_close();