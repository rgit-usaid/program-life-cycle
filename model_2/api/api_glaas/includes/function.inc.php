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
?>