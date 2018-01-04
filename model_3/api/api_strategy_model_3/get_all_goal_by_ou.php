<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['operating_unit_id']))
{ 
    $data = array();
    $operating_unit_id = trim($_REQUEST['operating_unit_id']);
    $select_data = "select * from usaid_goal where operating_unit_id='".$operating_unit_id."'";
    $result_data = $mysqli->query($select_data);
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
         $data[$i]['goal_id'] = $fetch_data['id'];  
         $data[$i]['goal_description'] = $fetch_data['goal_description']; 
         $data[$i]['goal_approval_date'] = dateFormat($fetch_data['goal_approval_date']);
         $data[$i]['operating_unit_id'] = $fetch_data['operating_unit_id'];
         $data[$i]['program_element_id'] = $fetch_data['program_element_id'];    
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
###function for deliver reponse on request===================
function deliverResponse($status,$status_msg,$data)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}?>