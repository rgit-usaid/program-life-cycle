<?php 
define("HOST_URL","http://".$_SERVER['HTTP_HOST'].'/usaid/');
define("API_HOST_URL",HOST_URL."api_glaas3/");
ini_set('display_errors', '0');
ini_set('register_globals','on');
session_start();
 
if($_SERVER['HTTP_HOST']=='localhost')
{
    $db_server = 'localhost';
    $db_name = "usaid_glaas3";
    $db_user = "root";
    $db_password = "";
}
else
{
    $db_server = 'localhost';
    $db_name = "rgdemode_glaas3";
    $db_user = "rgdemode_glaas";
    $db_password = "glaas@123";  
}
 
$mysqli = new mysqli($db_server, $db_user, $db_password, $db_name);
 
$_SESSION['mysqli']=$mysqli;
if ($mysqli->error) 
{
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
//echo memory_get_usage();
function get_memory_usage() { 
        $mem_usage = memory_get_usage(true); 
        
        if ($mem_usage < 1024) 
            return $mem_usage; 
        elseif ($mem_usage < 1048576) 
            return round($mem_usage/1024,2); 
        else 
            return round($mem_usage/1048576,2); 
    } 
if(ini_get("memory_limit"))
{
    ini_set('memory_limit','257M');
}

?>