<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
## request for get all archive project monitoring review ============
if(isset($_REQUEST['project_monitoring_id']))
{
    $data = array();
    $project_monitoring_id = trim($_REQUEST['project_monitoring_id']);
    $select_archive_project_monitoring = "select * from usaid_archive_project_monitoring where project_monitoring_id = '".$project_monitoring_id."' order by archive_on desc";
    $result_archive_project_monitoring = $mysqli->query($select_archive_project_monitoring);
	$i=0; 
	while($fetch_archive_monitoring = $result_archive_project_monitoring->fetch_array()){
			$data[$i]['id'] = $fetch_archive_monitoring['id'];
			$data[$i]['project_monitoring_id'] = $fetch_archive_monitoring['project_monitoring_id']; 
			$data[$i]['project_id'] = $fetch_archive_monitoring['project_id']; 
			$data[$i]['review_type'] = $fetch_archive_monitoring['review_type'];
			$data[$i]['review_due_date'] = dateFormat($fetch_archive_monitoring['review_due_date']);
			$data[$i]['review_prompt_date'] = dateFormat($fetch_archive_monitoring['review_prompt_date']);
			$data[$i]['actual_review_date'] = dateFormat($fetch_archive_monitoring['actual_review_date']);
			$data[$i]['overall_score'] = $fetch_archive_monitoring['overall_score'];
			$data[$i]['annual_review_submission_comments'] = $fetch_archive_monitoring['annual_review_submission_comments'];
			$data[$i]['annual_review_approval'] = $fetch_archive_monitoring['annual_review_approval'];
			$data[$i]['annual_review_approver'] = $fetch_archive_monitoring['annual_review_approver'];
			$data[$i]['annual_review_approver_comments'] = $fetch_archive_monitoring['annual_review_approver_comments'];
			$data[$i]['added_on'] = $fetch_archive_monitoring['added_on'];
			$data[$i]['archive_on'] = $fetch_archive_monitoring['archive_on'];
			$data[$i]['modified_by'] = $fetch_archive_monitoring['modified_by'];
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