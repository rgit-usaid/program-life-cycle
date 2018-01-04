<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
 
$data = array();
$i=0;	
$sel_indicator = "SELECT * FROM usaid_standard_indicator st";
$exe_indicator = $mysqli->query($sel_indicator);
while($res_indicator = $exe_indicator->fetch_array()){			
	$data[$i]['id'] = $res_indicator['id'];  
	$data[$i]['indicator_id'] = $res_indicator['indicator_id']; 
	$data[$i]['indicator_title'] = $res_indicator['indicator_title']; 

	$i++; 
}
if(count($data)>0)
{
	deliverResponse(200,'Record Found',$data);
}
else{
   deliverResponse(200,'No Record Found',NULL);
} 
  
###function for deliver reponse on request===================
function deliverResponse($status,$status_msg,$data)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}?>