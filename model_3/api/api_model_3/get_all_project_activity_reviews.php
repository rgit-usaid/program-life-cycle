<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");

## request for get all activity reviews============
if(isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!="")
{
    $data = array();
    $activity_id = trim($_REQUEST['activity_id']);
    $where_cond = '';
    if(isset($_REQUEST['review_id']) && $_REQUEST['review_id']!="")
    {
      $where_cond=" AND id= '".$_REQUEST['review_id']."'"."";
    }
	
	 $select_project_activity_review = "select * from
	                   usaid_project_activity_monitoring where activity_id ='".$activity_id."'".$where_cond;	
	$result_project_activity_review = $mysqli->query($select_project_activity_review);
	if($result_project_activity_review->num_rows>0)
	{	
		$i=0;
		while($fetch_activity_review = $result_project_activity_review->fetch_array()){
			$data[$i]['review_id'] = $fetch_activity_review['id'];
			$data[$i]['activity_id'] = $fetch_activity_review['activity_id']; 
			$data[$i]['review_type'] = $fetch_activity_review['review_type'];
			$data[$i]['review_due_date'] = $fetch_activity_review['review_due_date'];
			$data[$i]['review_prompt_date'] = $fetch_activity_review['review_prompt_date'];
			$data[$i]['actual_review_date'] = $fetch_activity_review['actual_review_date'];
			$data[$i]['overall_score'] = $fetch_activity_review['overall_score'];
			$data[$i]['annual_review_submission_comments'] = $fetch_activity_review['annual_review_submission_comments'];
			$data[$i]['annual_review_approval'] = $fetch_activity_review['annual_review_approval'];
			$data[$i]['annual_review_approver'] = $fetch_activity_review['annual_review_approver'];
			$data[$i]['annual_review_approver_comments'] = $fetch_activity_review['annual_review_approver_comments'];
			$i++;
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