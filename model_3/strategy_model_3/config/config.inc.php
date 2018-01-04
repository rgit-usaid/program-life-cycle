<?php 
define("SITE_PATH","http://".$_SERVER['HTTP_HOST'].'/usaid/');
define("HOST_URL",SITE_PATH."strategy3/");
define("API_HOST_URL",SITE_PATH."api-strategy3/"); // main API
define("AMP_API_HOST_URL",SITE_PATH."api-amp3/");
define("PHOENIX_API_HOST_URL",SITE_PATH."api-phoenix3/");
define('API_HOST_URL_PROJECT',SITE_PATH."api-amp3/");
ini_set('display_errors', '0');
ini_set('register_globals','on');
session_start();
if($_SERVER['HTTP_HOST']=='localhost')
{
	$db_server = 'localhost';
    $db_name = "usaid_strategy3";
    $db_user = "root";
    $db_password = "";
}
else
{
	$db_server = 'localhost';
    $db_name = "rgdemode_strategy3";
    $db_user = "rgdemode_strgy";
    $db_password = "strategy@123";  
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