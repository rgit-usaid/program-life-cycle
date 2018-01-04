<?php
include("config/config.inc.php");
header('Content-type: application/json');

##request for delete project==============
if(isset($_REQUEST['project_id']) and $_REQUEST['project_id']!='')
{
    $project_id = trim($_REQUEST['project_id']);
    if($project_id!='')
    {
        removeProject($project_id);
    }
    else
    { 
        deliverResponse(400,'Invalid Request',NULL);
    }
}

## create by : rachit
## function for use to get all stage of a project ================
function removeProject($project_id)
{
    global $mysqli;
    
    $remove_project = "delete from usaid_project where project_id='".$project_id."'";
    $result_remove = $mysqli->query($remove_project);
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