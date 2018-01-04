
<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");

## request for get activity review output score============
if(isset($_REQUEST['review_id']) && $_REQUEST['review_id']!="")
{
    $data = array();
    $review_id = trim($_REQUEST['review_id']);
	
	$select_activity_review_op = "select *
		from usaid_project_activity_monitoring_output_score where activity_monitoring_id = '".$review_id."'";
	
	$result_activity_review_op = $mysqli->query($select_activity_review_op);
	if($result_activity_review_op->num_rows>0)
	{	
		$i=0;
		while($fetch_activity_review_op = $result_activity_review_op->fetch_array()){
			$data[$i]['project_monitoring_output_score_id'] = $fetch_activity_review_op['id'];
			$data[$i]['project_monitoring_id'] = $fetch_activity_review_op['activity_monitoring_id'];
			$data[$i]['project_output_score_description'] = $fetch_activity_review_op['project_output_score_description'];
			$data[$i]['project_output_impact_weight'] = $fetch_activity_review_op['project_output_impact_weight'];
			$data[$i]['project_output_performance'] = $fetch_activity_review_op['project_output_performance'];
			$data[$i]['project_output_risk'] = $fetch_activity_review_op['project_output_risk'];
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