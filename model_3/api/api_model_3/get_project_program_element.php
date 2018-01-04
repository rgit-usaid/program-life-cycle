<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
 
## request for get all employee details============
$data = array(); 
if(isset($_REQUEST['project_id'])){
	$select_program_element = "select ppel.*, pel.program_element_code, pel.program_element_name  
	from usaid_project_program_element as ppel 
	LEFT JOIN usaid_program_element pel ON ppel.program_element_id = pel.id
	WHERE ppel.project_id ='".$_REQUEST['project_id']."'";
	$result_program_element = $mysqli->query($select_program_element);
	$i=0; 
	while($fetch_program_element = $result_program_element->fetch_array()){
		 $data[$i]['project_id'] = $fetch_program_element['project_id']; 
		 $data[$i]['program_element_id'] = $fetch_program_element['program_element_id'];
		 $data[$i]['program_element_code'] = $fetch_program_element['program_element_code'];
		 $data[$i]['program_element_name'] = $fetch_program_element['program_element_name'];
		 $data[$i]['percentage'] = $fetch_program_element['percentage']; 
		 $i++; 
	}
	if(count($data)>0){
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
function deliverResponse($status,$status_msg,$data){
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}

?>