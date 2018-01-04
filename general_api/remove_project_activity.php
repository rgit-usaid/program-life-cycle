<?php
include("config/config.inc.php");
header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");

##request for delete activity==============
if(isset($_REQUEST['project_id']) and $_REQUEST['project_id']!='' and isset($_REQUEST['activity_id']) and $_REQUEST['activity_id']!='')
{
    $project_id = trim($_REQUEST['project_id']);
	$activity_id = trim($_REQUEST['activity_id']);	
    if($project_id!='' && $activity_id!='')
	{
		removeProjectActivity($project_id,$activity_id);
    }
    else
    { 
        deliverResponse(400,'Invalid Request',NULL);
    }
}



## create by : rachit

## delete function of activity ================
function removeProjectActivity($project_id,$activity_id)
{
    global $mysqli;
    $remove_project_activity = "UPDATE usaid_project_activity set status='Remove' WHERE project_id='".$project_id."' and activity_id='".$activity_id."'";

    $result_remove = $mysqli->query($remove_project_activity);
    if($result_remove)
    {
        deliverResponse(200,'Remove Successfuly');
    }
    else
    {
        deliverResponse(400,'Invalid Request',NULL);
    } 
}



###function for deliver reponse on request===================

function deliverResponse($status,$status_msg)
{
    $response['status'] = $status;

    $response['status_msg'] = $status_msg;

    $json_response = json_encode($response);

    echo $json_response;

}



?>