<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");

## request for get all project============
if(isset($_REQUEST['project_id']) && $_REQUEST['project_id']!="" && isset($_REQUEST['review_type']) && $_REQUEST['review_type']!="")
{
    $data = array();
    $project_id = $mysqli->real_escape_string(trim($_REQUEST['project_id']));
	
	$where_cond = '';
	
	##==find next annual_review_required date of project 
	if(isset($_REQUEST['review_type']) && $_REQUEST['review_type']=="annual_review"){
		$where_cond = "AND review_type='Annual Review'";
	}
	else if(isset($_REQUEST['review_type']) && $_REQUEST['review_type']=="project_activity_review"){
		$where_cond = "AND review_type='Project Activity Review'";
	}
	
	$select_project_review = "select pm.id, pm.review_type, pm.review_due_date, pm.review_prompt_date, p.project_id
		from usaid_project as p
		left join usaid_project_monitoring as pm ON pm.project_id = p.project_id 
		where p.project_id = '".$project_id."'
		AND pm.review_due_date >= NOW() ".$where_cond."
		ORDER BY pm.review_due_date LIMIT 0, 1";
		
	$result_project_review = $mysqli->query($select_project_review);
	$fetch_project_review = $result_project_review->fetch_array();
	if($fetch_project_review['project_id']!='')
	{
		$data['id'] = $fetch_project_review['id'];
		$data['project_id'] = $fetch_project_review['project_id']; 
		$data['review_type'] = $fetch_project_review['review_type'];
		$data['review_required'] = $fetch_project_review['review_required'];
		$data['review_due_date'] = $fetch_project_review['review_due_date'];
		$data['review_prompt_date'] = $fetch_project_review['review_prompt_date'];
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