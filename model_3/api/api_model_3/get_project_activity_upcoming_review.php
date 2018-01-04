<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");

## request for get all upcoming activity review============
if(isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!="" && isset($_REQUEST['review_type']) && $_REQUEST['review_type']!="")
{
    $data = array();
    $activity_id = trim($_REQUEST['activity_id']);
	$review_type = trim($_REQUEST['review_type']);
	$where_cond = '';
	
	##==find next annual_review_required date of activity 
	if($review_type == "annual_review"){
		$where_cond = "AND review_type='Annual Review'";
	}
	else if($review_type == "project_activity_review"){
		$where_cond = "AND review_type='Project Activity Review'";
	}
	
   $select_activity_review = "select * from usaid_project_activity_monitoring 
		                            where activity_id = '".$activity_id."'
		                AND review_due_date >= NOW() ".$where_cond."
		                 ORDER BY review_due_date LIMIT 0, 1";
	$result_activity_review = $mysqli->query($select_activity_review);
	$fetch_activity_review = $result_activity_review->fetch_array();
	if($fetch_activity_review['activity_id']!='')
	{
		$data['id'] = $fetch_activity_review['id'];
		$data['activity_id'] = $fetch_activity_review['activity_id']; 
		$data['review_type'] = $fetch_activity_review['review_type'];
		$data['review_required'] = $fetch_activity_review['review_required'];
		$data['review_due_date'] = $fetch_activity_review['review_due_date'];
		$data['review_prompt_date'] = $fetch_activity_review['review_prompt_date'];
		deliverResponse(200,'Record Found',$data); 
	}
	else{
		deliverResponse(200,'No Record Found',NULL);
	}	
}
else{
     deliverResponse(200,'Invalid Request',NULL);
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