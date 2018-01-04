<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *"); 
## request for get all employee details============
if(isset($_REQUEST['role_id']) and $_REQUEST['role_id']!=''){
    $data = array();
    $role_id = $mysqli->real_escape_string(trim($_REQUEST['role_id'])); 
    $select_role = "select * from usaid_role where id='".$role_id."'";
    $result_role = $mysqli->query($select_role);
    $fetch_role = $result_role->fetch_array();
    if($fetch_role['id']!=''){
         $data['role_id'] = $fetch_role['role_id']; 
         $data['role'] = $fetch_role['role'];
         deliverResponse(200,'Record Found',$data); 
    } 
    else{
       deliverResponse(200,'No Record Found',NULL);
    }
}else{
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