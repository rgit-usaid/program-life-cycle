<?php
## function for get date format========
function dateFormat($date)
{
   $date_formated = '';
   if($date!='0000-00-00' and $date!='')
   {
     $date_formated = date('m/d/Y',strtotime($date)); 
   }
   return $date_formated; 
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