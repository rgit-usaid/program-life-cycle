<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
$data = array();
if(isset($_REQUEST['project_id']))
{
    $project_id = trim($_REQUEST['project_id']);
    $select_project_activity = "select pa.* from usaid_project_activity as pa WHERE pa.status='Active' and pa.project_id =".$project_id;
    $result_project_activity = $mysqli->query($select_project_activity);
    $i=0; 
    while($fetch_project_activity = $result_project_activity->fetch_array())
    {
         $data[$i]['activity_id'] = $fetch_project_activity['activity_id']; 
    	 $data[$i]['project_id'] = $fetch_project_activity['project_id']; 
         $data[$i]['title'] = $fetch_project_activity['title'];
         $data[$i]['activity_benefitting_country'] = $fetch_project_activity['activity_benefitting_country'];
		 $data[$i]['planned_start_date'] = $fetch_project_activity['planned_start_date'];
		 $data[$i]['planned_end_date'] = $fetch_project_activity['planned_end_date'];
		 $data[$i]['actual_start_date'] = $fetch_project_activity['actual_start_date'];
		 $data[$i]['actual_end_date'] = $fetch_project_activity['actual_end_date'];
    	 $data[$i]['employee_id'] = $fetch_project_activity['employee_id'];
         $i++; 
    }
    if(count($data)>0){
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