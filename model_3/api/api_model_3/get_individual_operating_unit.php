<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
$data = array();
## request for operating unit============
if(isset($_REQUEST['operating_unit_id'])){
	$operating_unit_id = trim($_REQUEST['operating_unit_id']);
	
	$mysqli_phx->connect_error;
	$select_operating_unit = "select * FROM usaid_operating_unit WHERE operating_unit_id ='".$operating_unit_id."'";
	$result_operating_unit = $mysqli_phx->query($select_operating_unit);
	if($result_operating_unit->num_rows>0)
	{
		while($fetch_operating_unit = $result_operating_unit->fetch_array()){
			$data['id'] = $fetch_operating_unit['id']; 
			$data['operating_unit_id'] = $fetch_operating_unit['operating_unit_id']; 
			$data['L1'] = $fetch_operating_unit['L1']; 
			$data['L2'] = $fetch_operating_unit['L2']; 
			$data['L3'] = $fetch_operating_unit['L3'];
			$data['L4'] = $fetch_operating_unit['L4'];
			$data['L5'] = $fetch_operating_unit['L5'];  
			$data['L6'] = $fetch_operating_unit['L6'];
			$data['L7'] = $fetch_operating_unit['L7'];
			$data['L8'] = $fetch_operating_unit['L8'];
			$data['operating_unit_description'] = $fetch_operating_unit['operating_unit_description'];
			$data['operating_unit_abbreviation'] = $fetch_operating_unit['operating_unit_abbreviation'];
			$data['type'] = $fetch_operating_unit['type'];
			$data['parent_operating_unit_id'] = $fetch_operating_unit['parent_operating_unit_id'];
		}
		deliverResponse(200,'Record Found',$data); 
	}
	else{
		deliverResponse(200,'No Record Found',NULL);
	}

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