<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");

## request for get all project============
if(isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!="")
{
    $data = array();
    ### if we want to get details to a evalution of an activity=====
    $evalution_cond = '';
    if(isset($_REQUEST['evaluation_id']))
    {
    	if($_REQUEST['evaluation_id']!='')
    	{
    		$evalution_cond = " and id ='".$_REQUEST['evaluation_id']."'";	
    	}
    }

    $activity_id = trim($_REQUEST['activity_id']);
	$select_evalution = "select * from usaid_project_activity_evaluation where activity_id='".$activity_id."' ".$evalution_cond."";
	$result_evalution = $mysqli->query($select_evalution); 
	$i=0;
	while($fetch_project_activity_evaluation = $result_evalution->fetch_array()) 
	{  
		$data[$i]['evaluation_id'] = $fetch_project_activity_evaluation['id'];
		$data[$i]['project_id'] = $fetch_project_activity_evaluation['project_id'];
		$data[$i]['activity_id'] = $fetch_project_activity_evaluation['activity_id'];  
		$data[$i]['type'] = $fetch_project_activity_evaluation['type'];
		$data[$i]['evaluation_type_description_other'] = $fetch_project_activity_evaluation['evaluation_type_description_other'];
		$data[$i]['management_type'] = $fetch_project_activity_evaluation['management_type'];
		$data[$i]['estimated_cost'] = $fetch_project_activity_evaluation['estimated_cost'];
		$data[$i]['start_date'] = $fetch_project_activity_evaluation['start_date'];
		$data[$i]['end_date'] = $fetch_project_activity_evaluation['end_date'];
		$data[$i]['additional_comment'] = $fetch_project_activity_evaluation['additional_comment']; 
		$i++;
	} 
 
	if(count($data)>0)
	{
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