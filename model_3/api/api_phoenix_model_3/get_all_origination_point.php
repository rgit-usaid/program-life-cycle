<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
$data = array();
$select_data = "select * from usaid_origination_point order by origination_point_name";
$result_data = $mysqli->query($select_data);
$i=0; 
while($fetch_data = $result_data->fetch_array())
{
     $data[$i]['id'] = $fetch_data['id'];
     $data[$i]['origination_point_name'] = $fetch_data['origination_point_name'];
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