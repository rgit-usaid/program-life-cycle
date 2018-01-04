<?php 
include("config.inc.php");
##==change dateFormat===
function dateFormat($date)
{
	$date = date('Y-m-d',strtotime($date));
	return $date;
}

##==change dateTimeFormat===
function dateTimeFormat($datetime)
{
	$datetime = date('m/d/Y h:i',strtotime($datetime));
	return $datetime;
}
##==validate user===
function validate_user(){
	if(!isset($_SESSION['user'])){
		header('Location: login');
	}
}

##==validate user===
function requestByCURL($url){  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output,true);
}

##==change dateFormat in specific format===
function dateSpecificFormat($date,$format)
{
	$date = date($format,strtotime($date));
	return $date;
}


##==change priceFormat in specific format===
function priceFormat($price)
{
	if($price>0){
		return '$'.number_format($price);
	}
	else{
		return "0";
	}	
}


function get_quarter($month){
	$quarter = 0;
	switch($month){
		case 10:
		case 11:
		case 12:			
					$quarter = 1;
					break;
		case 1:
		case 2:
		case 3:
					$quarter = 2;
					break;	
		case 4:
		case 5:
		case 6:
					$quarter = 3;
					break;	
		case 7:
		case 8:
		case 9: 
					$quarter = 4;
				 	break;
	}
	return $quarter; 
}
?>