<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['project_id']) && isset($_REQUEST['activity_id']))
{
    $data = array();
	$activity_id = $mysqli->real_escape_string(trim($_REQUEST['activity_id']));
    $project_id = $mysqli->real_escape_string(trim($_REQUEST['project_id']));
    $select_project = "select pa.*
                        from usaid_project_activity as pa where pa.project_id = '".$project_id."' and pa.activity_id = '".$activity_id."' ";
    $result_project = $mysqli->query($select_project);
    $fetch_project = $result_project->fetch_array();
    if($fetch_project['project_id']!='' && $fetch_project['activity_id']!='')
    {
        $data['activity_id'] = $fetch_project['activity_id']; 
		$data['project_id'] = $fetch_project['project_id']; 
        $data['title'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['title']));
        $data['activity_description'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['activity_description'])); 
		$data['activity_benefitting_country'] = $fetch_project['activity_benefitting_country']; 
		$data['activity_published'] = $fetch_project['activity_published']; 
        $data['planned_start_date'] = dateFormat($fetch_project['planned_start_date']); 
        $data['planned_end_date'] = dateFormat($fetch_project['planned_end_date']); 
        $data['actual_start_date'] = dateFormat($fetch_project['actual_start_date']); 
        $data['actual_end_date'] = dateFormat($fetch_project['actual_end_date']); 
        $data['employee_id'] = $fetch_project['employee_id'];      
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