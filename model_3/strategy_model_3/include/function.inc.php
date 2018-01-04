<?php
function requestByCURL($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);                               
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);
	$data_arr = json_decode($output,true);
	return $data_arr;
}

##function for get date format to insert into db y-m-d ========
function dateFormat($date)
{
	$date_formated = '';
   	if($date!='')
   	{
     	$date_formated = date('Y-m-d',strtotime($date));
   	}
   	return $date_formated; 
}
## function for fetch date formate to view data in usaid date formet
function dateFormatView($date)
{
   $date_formated = '---';
   if($date!='0000-00-00' and $date!='')
   {
     $date_formated = date('m/d/Y',strtotime($date)); 
   }
   return $date_formated; 
}
?>