<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *"); 
## request for get all employee details============
$data = array(); 
$select_role = "select * from usaid_role";
$result_role = $mysqli->query($select_role);
$i=0; 
while($fetch_role = $result_role->fetch_array())
{
     $data[$i]['role_id'] = $fetch_role['id']; 
     $data[$i]['role'] = $fetch_role['role'];
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