<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
## request for get all archive project evalution============
if(isset($_REQUEST['archive_evaluation_id']))
{
    $data = array();
    $archive_evaluation_id = trim($_REQUEST['archive_evaluation_id']);
    $select_archive_project_evalution = "select * from usaid_archive_project_evaluation where archive_id = '".$archive_evaluation_id."'";
    $result_archive_project_evalution = $mysqli->query($select_archive_project_evalution);
	$i=0; 
	while($fetch_archive_evaluation = $result_archive_project_evalution->fetch_array()){
			$data[$i]['id'] = $fetch_archive_evaluation['id'];
			$data[$i]['archive_id'] = $fetch_archive_evaluation['archive_id'];
			$data[$i]['project_id'] = $fetch_archive_evaluation['project_id']; 
			$data[$i]['type'] = $fetch_archive_evaluation['type'];
			$data[$i]['evaluation_type_description_other'] = $fetch_archive_evaluation['evaluation_type_description_other'];
			$data[$i]['management_type'] = $fetch_archive_evaluation['management_type'];
			$data[$i]['estimated_cost'] = $fetch_archive_evaluation['estimated_cost'];
			$data[$i]['start_date'] = dateFormat($fetch_archive_evaluation['start_date']);
			$data[$i]['end_date'] = dateFormat($fetch_archive_evaluation['end_date']);
			$data[$i]['additional_comment'] = $fetch_archive_evaluation['additional_comment'];
			$data[$i]['added_on'] = $fetch_archive_evaluation['added_on'];
			$data[$i]['archive_on'] = $fetch_archive_evaluation['archive_on'];
			$data[$i]['modified_by'] = $fetch_archive_evaluation['modified_by'];
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