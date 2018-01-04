<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['goal_id']))
{ 
    $data = array();
    $goal_id = trim($_REQUEST['goal_id']);
    $select_data = "select * from usaid_goal where id='".$goal_id."'";
    $result_data = $mysqli->query($select_data);
    $fetch_data = $result_data->fetch_array();
    if($fetch_data['id']>0)
    {
        $data['goal_id'] = $fetch_data['id'];  
        $data['goal_description'] = $fetch_data['goal_description']; 
        $data['goal_approval_date'] = dateFormat($fetch_data['goal_approval_date']);
        $data['operating_unit_id'] = $fetch_data['operating_unit_id'];
        $data['program_element_id'] = $fetch_data['program_element_id'];
        deliverResponse(200,'Record Found',$data);
    }
    else{
       deliverResponse(200,'No Record Found',NULL);
    } 
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