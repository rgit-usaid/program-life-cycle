<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all award instrument number============

if(isset($_REQUEST['ou_id']))
{
	$ou_id = trim($_REQUEST['ou_id']);
	$data = array();
	$select_awd = "select award_name, award_number from usaid_requisition_award where operating_unit_id='".$ou_id."' and status='Active'";
	$result_awd = $mysqli->query($select_awd);
	
	$i=0; 
	while($fetch_awd = $result_awd->fetch_array())
	{
		 $data[$i]['component_name'] = $fetch_awd['award_name'];
		 $data[$i]['component_number'] = $fetch_awd['award_number']; 
		 
		 $i++; 
		 //$select_clin = "select clin_name, clin_number from usaid_requisition_award_clin where award_number='".$fetch_awd['award_number']."'";
		 $select_clin = "select cl.clin_name, cl.clin_number 
		 from usaid_requisition_award_clin cl 
		 where cl.operating_unit_id='".$ou_id."' and cl.award_number='".$fetch_awd['award_number']."' and cl.status='Active'";
		 $result_clin = $mysqli->query($select_clin);
		 while($fetch_clin = $result_clin->fetch_array()){
		 	$data[$i]['component_name'] = $fetch_clin['clin_name'];
			$data[$i]['component_number'] = $fetch_clin['clin_number']; 
		 	$i++; 
		 }
	}
	
	$select_clin = "select cl.clin_name, cl.clin_number 
	from usaid_requisition_award_clin cl
 	left join usaid_requisition_award ua ON cl.award_number = ua.award_number
	where cl.operating_unit_id='".$ou_id."' and (cl.operating_unit_id <> ua.operating_unit_id || ua.operating_unit_id IS NULL) and cl.status='Active'";
	$result_clin = $mysqli->query($select_clin);
	while($fetch_clin = $result_clin->fetch_array()){
		$data[$i]['component_name'] = $fetch_clin['clin_name'];
		$data[$i]['component_number'] = $fetch_clin['clin_number']; 
		$i++; 
	}
		 
	if(count($data)>0){
		deliverResponse(200,'Record Found',$data);
	}
	else{
	   deliverResponse(200,'No Record Found',NULL);
	} 
}  
###function for deliver reponse on request===================
function deliverResponse($status,$status_msg,$data)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}
?>