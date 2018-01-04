<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
 
## request for get all employee details============
$data = array(); 
if(isset($_REQUEST['activity_id']))
{
	$activity_id = trim($_REQUEST['activity_id']);
	$select_archive_program_element = "select * from usaid_archive_activity_pe WHERE activity_id ='".$activity_id."' order by archive_on desc";
	$result_archive_program_element = $mysqli->query($select_archive_program_element);
	$i=0; 
	while($fetch_archive_program_element = $result_archive_program_element->fetch_array())
	{
		 $data[$i]['id'] = $fetch_archive_program_element['id']; 
		 $data[$i]['activity_id'] = $fetch_archive_program_element['activity_id'];
		 $data[$i]['archive_on'] = $fetch_archive_program_element['archive_on'];
		 $data[$i]['modified_by'] = $fetch_archive_program_element['modified_by'];
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