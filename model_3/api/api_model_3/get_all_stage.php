<?php
include("config/config.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
##request for all stage and return all stage    ==============
$data = array();
$select_stage = "select stage_id,stage_name,stage_percentage from usaid_project_stage ";
$result_stage = $mysqli->query($select_stage);
$i=0; 
while($fetch_stage = $result_stage->fetch_array())
{
     $data[$i]['stage_id'] = $fetch_stage['stage_id']; 
     $data[$i]['stage_name'] = $fetch_stage['stage_name'];
     $data[$i]['stage_percentage'] = $fetch_stage['stage_percentage'];  
     $i++;
}
if(count($data)>0){ 
    deliverResponse(200,'Record Found',$data);
}
else{
    deliverResponse(200,'No Record Found',NULL);
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