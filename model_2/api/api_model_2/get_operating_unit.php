<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for operating unit============
$data = array();
$mysqli_phx->connect_error;
$select_operating_unit = "select * FROM usaid_operating_unit";
$result_operating_unit = $mysqli_phx->query($select_operating_unit);
if($result_operating_unit->num_rows>0)
{
	$i=0;
	while($fetch_operating_unit = $result_operating_unit->fetch_array()){
		$data[$i]['id'] = $fetch_operating_unit['id']; 
		$data[$i]['operating_unit_id'] = $fetch_operating_unit['operating_unit_id']; 
		$data[$i]['L1'] = $fetch_operating_unit['L1']; 
		$data[$i]['L2'] = $fetch_operating_unit['L2']; 
		$data[$i]['L3'] = $fetch_operating_unit['L3'];
		$data[$i]['L4'] = $fetch_operating_unit['L4'];
		$data[$i]['L5'] = $fetch_operating_unit['L5'];  
		$data[$i]['L6'] = $fetch_operating_unit['L6'];
		$data[$i]['L7'] = $fetch_operating_unit['L7'];
		$data[$i]['L8'] = $fetch_operating_unit['L8'];
		$data[$i]['operating_unit_description'] = $fetch_operating_unit['operating_unit_description'];
		$data[$i]['operating_unit_abbreviation'] = $fetch_operating_unit['operating_unit_abbreviation'];
		$data[$i]['type'] = $fetch_operating_unit['type'];
		$data[$i]['parent_operating_unit_id'] = $fetch_operating_unit['parent_operating_unit_id'];
		$i++;
	}
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