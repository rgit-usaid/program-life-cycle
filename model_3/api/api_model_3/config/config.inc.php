<?php
ini_set('display_errors', '0');
ini_set('register_globals','on');
session_start();
 
if($_SERVER['HTTP_HOST']=='localhost')
{
    $db_server = 'localhost';
    $db_name = "usaid_amp";
    $db_user = "root";
    $db_password = "";
	
	$db_pheonix_user = "";  
	$db_pheonix_password = "";  
	$db_pheonix = "rgdemode_phoenix3";
}
else
{
    $db_server = 'localhost';
    $db_name = "rgdemode_amp3";
    $db_user = "rgdemode_ampuser";
    $db_password = "amp@123"; 
	
	$db_pheonix = "rgdemode_phoenix3";
    $db_pheonix_user = "rgdemode_phoenix";  
	$db_pheonix_password = "phoenix@123";  
	
}
 
$mysqli = new mysqli($db_server, $db_user, $db_password, $db_name);
$mysqli_phx = new mysqli($db_server, $db_pheonix_user, $db_pheonix_password, $db_pheonix);
 
$_SESSION['mysqli']=$mysqli;
if ($mysqli->error || $mysqli_phx->error) 
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

define("HOST_URL","http://".$_SERVER['HTTP_HOST'].'/usaid/');
define('API_HOST_URL_PROJECT',HOST_URL.'api-amp3/');
define('API_HOST_URL_PROJECT2',HOST_URL.'api/');
define('API_HOST_URL_GLAAS',HOST_URL.'api-glaas3/');
define('API_HOST_URL_PHOENIX',HOST_URL.'api-phoenix3/');
?>