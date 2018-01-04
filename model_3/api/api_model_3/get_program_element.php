<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
 
## request for get program element detail by id============
if(isset($_REQUEST['program_element_id']))
{
    $data = array(); 
    $program_element_id = $_REQUEST['program_element_id'];   
    $select_employee = "select * from usaid_program_element where id='".$program_element_id."'";
    $result_employee = $mysqli->query($select_employee);
    $fetch_employee = $result_employee->fetch_array(); 
    if($fetch_employee['id']>0)
    {
        $data['id'] = $fetch_employee['id']; 
        $data['program_element_code'] = $fetch_employee['program_element_code'];
        $data['program_element_name'] = $fetch_employee['program_element_name']; 
        deliverResponse(200,'Record Found',$data);
    }
    else{
       deliverResponse(200,'No Record Found',NULL);
    }
}
else
{
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