<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");

## request for get all project============
if(isset($_REQUEST['project_id']) && $_REQUEST['project_id']!="" && isset($_REQUEST['review_id']) && $_REQUEST['review_id']!="")
{
    $data = array();
    $project_id = $mysqli->real_escape_string(trim($_REQUEST['project_id']));
	$review_id = $mysqli->real_escape_string(trim($_REQUEST['review_id']));
	
	$select_project_review = "select pm.*, p.project_id
		from usaid_project_monitoring as pm
		left join usaid_project as p ON pm.project_id = p.project_id 
		where p.project_id = '".$project_id."' AND pm.id ='".$review_id."'
		ORDER BY pm.review_due_date";
		
	$result_project_review = $mysqli->query($select_project_review);
	if($result_project_review->num_rows>0)
	{	
		while($fetch_project_review = $result_project_review->fetch_array()){
			$data['review_id'] = $fetch_project_review['id'];
			$data['project_id'] = $fetch_project_review['project_id']; 
			$data['review_type'] = $fetch_project_review['review_type'];
			$data['review_due_date'] = $fetch_project_review['review_due_date'];
			$data['review_prompt_date'] = $fetch_project_review['review_prompt_date'];
			$data['actual_review_date'] = $fetch_project_review['actual_review_date'];
			$data['overall_score'] = $fetch_project_review['overall_score'];
			$data['annual_review_submission_comments'] = $fetch_project_review['annual_review_submission_comments'];
			$data['annual_review_approval'] = $fetch_project_review['annual_review_approval'];
			$data['annual_review_approver'] = $fetch_project_review['annual_review_approver'];
			$data['annual_review_approver_comments'] = $fetch_project_review['annual_review_approver_comments'];
		}
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