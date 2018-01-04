<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
 
## request for get all employee details============
$data = array(); 
$select_employee = "select e.* from usaid_program_element e";
$result_employee = $mysqli->query($select_employee);
$i=0; 
while($fetch_employee = $result_employee->fetch_array()){
     $data[$i]['id'] = $fetch_employee['id']; 
     $data[$i]['program_element_code'] = $fetch_employee['program_element_code'];
     $data[$i]['program_element_name'] = $fetch_employee['program_element_name']; 
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