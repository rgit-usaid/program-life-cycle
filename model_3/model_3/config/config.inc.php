<?php 

define('SITE_PATH',"http://".$_SERVER['HTTP_HOST']."/"); // server name or host name
define("HOST_URL",SITE_PATH.'usaid/amp/3/');
 


ini_set('display_errors', '0');
ini_set('register_globals','on');
session_start();
 
if($_SERVER['HTTP_HOST']=='localhost')
{
	$db_server = 'localhost';
    $db_name = "usaid_amp3";
    $db_user = "root";
    $db_password = "";
}
else
{
	$db_server = 'localhost';
    $db_name = "rgdemode_amp3";
    $db_user = "rgdemode_ampuser";
    $db_password = "amp@123";  
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


 
define('DOCUMENT_LOC',$_SERVER['DOCUMENT_ROOT'].'/usaid/amp/3/artifacts/');
define('PICTURE_SERVER',SITE_PATH.'usaid/hr-connect/');
define('API_HOST_URL_PROJECT',SITE_PATH.'usaid/api-amp3/');
define('API_HOST_URL_PROJECT2',SITE_PATH.'usaid/api/');
define('API_HOST_URL_GLAAS',SITE_PATH.'usaid/api-glaas3/');
define('API_HOST_URL_PHOENIX',SITE_PATH.'usaid/api-phoenix3/');
define('API_HOST_URL_STRATEGY',SITE_PATH.'usaid/api-strategy3/');
define('API_MODEL_URL_STRATEGY',SITE_PATH.'usaid/strategy3/');
define('TITLE','USAID-3');

define('PLUS_EXTRA_SPEND_DAYS',25);
$performance_array = array("A"=>10,"B"=>7,"C"=>5,"D"=>4,"F"=>0);
?>