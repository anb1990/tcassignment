<?php

if (session_id() === "") {
    session_start();
}



define('ROOT', dirname(dirname(__FILE__)));

// the routing url, we need to use original 'QUERY_STRING' from server paramater because php has parsed the url if we use $_GET
$_route = isset($_GET['_route']) ? preg_replace('/^_route=(.*)/', '$1', $_SERVER['QUERY_STRING']) : '';


require_once (ROOT . '/library/system/router.php');





/*
require_once("../library/system/DbAdapter.php");
$testDB = new DbAdapter('localhost','root','VumilaatqDB@20156','test');
$data = $testDB->close();
print_r($data);
