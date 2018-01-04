<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
$data = array();
$select_data = "select * from usaid_requisition where status!='Remove'";
$result_data = $mysqli->query($select_data);
$i=0; 
while($fetch_data = $result_data->fetch_array())
{
     $data[$i]['requisition_id'] = $fetch_data['id'];  
     $data[$i]['requisition_number'] = $fetch_data['requisition_number'];
     $data[$i]['type'] = $fetch_data['type'];
     $data[$i]['status'] = $fetch_data['status'];
     $data[$i]['create_date'] = dateFormat($fetch_data['create_date']);
     $data[$i]['period_of_performance_start_date'] = dateFormat($fetch_data['period_of_performance_start_date']);  
     $data[$i]['period_of_performance_end_date'] = dateFormat($fetch_data['period_of_performance_end_date']);
     $i++; 
}
if(count($data)>0){
    deliverResponse(200,'Record Found',$data);
}
else{
   deliverResponse(200,'No Record Found',NULL);
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