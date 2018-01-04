<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");

## request for get all project============
if(isset($_REQUEST['review_id']) && $_REQUEST['review_id']!="")
{
    $data = array();
    $review_id = $mysqli->real_escape_string(trim($_REQUEST['review_id']));
	
	$select_project_review_op = "select pm.*
		from usaid_project_monitoring_output_score as pmo
		left join usaid_project_monitoring as pmo ON pm.id = pmo.project_monitoring_id
		where pmo.project_monitoring_id = '".$review_id."'";
	
	$result_project_review_op = $mysqli->query($select_project_review_op);
	if($result_project_review_op->num_rows>0)
	{	
		$i=0;
		while($fetch_project_review_op = $result_project_review_op->fetch_array()){
			$data[$i]['project_monitoring_output_score_id'] = $fetch_project_review_op['id'];
			$data[$i]['project_monitoring_id'] = $fetch_project_review_op['project_monitoring_id']; 
			$data[$i]['project_output_score_description'] = $fetch_project_review_op['project_output_score_description'];
			$data[$i]['project_output_impact_weight'] = $fetch_project_review_op['project_output_impact_weight'];
			$data[$i]['project_output_performance'] = $fetch_project_review_op['project_output_performance'];
			$data[$i]['project_output_risk'] = $fetch_project_review_op['project_output_risk'];
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