<?php
include("config/config.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
##request for stage==============
if(isset($_REQUEST['stage_id']) and $_REQUEST['stage_id']!=''){
    $data = array();
    $stage_id = $mysqli->real_escape_string(trim($_REQUEST['stage_id']));
    
    $select_stage = "select stage_id,stage_name,stage_percentage from usaid_project_stage where stage_id='".$stage_id."'";
    $result_stage = $mysqli->query($select_stage);
    $fetch_stage = $result_stage->fetch_array();
    if($fetch_stage['stage_id']!=''){
        $data['stage_id'] = $fetch_stage['stage_id']; 
        $data['stage_name'] = $fetch_stage['stage_name'];
        $data['stage_percentage'] = $fetch_stage['stage_percentage'];
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
function deliverResponse($status,$status_msg,$data){
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}

?>