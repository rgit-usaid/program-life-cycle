<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get requisition details============
if(isset($_REQUEST['requisition_number']) and $_REQUEST['requisition_number']!='')
{
    $data = array();
    $requisition_number =  trim($_REQUEST['requisition_number']);
    $select_data = "select * from usaid_requisition where requisition_number = '".$requisition_number."'";
    $result_data = $mysqli->query($select_data);
    $fetch_data = $result_data->fetch_array();
    if($fetch_data['id']!='')
    {
        $data['requisition_id'] = $fetch_data['id'];  
        $data['requisition_number'] = $fetch_data['requisition_number'];
        $data['type'] = $fetch_data['type'];
        $data['status'] = $fetch_data['status'];
        $data['create_date'] = dateFormat($fetch_data['create_date']);
        $data['period_of_performance_start_date'] = dateFormat($fetch_data['period_of_performance_start_date']);  
        $data['period_of_performance_end_date'] = dateFormat($fetch_data['period_of_performance_end_date']);
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
function deliverResponse($status,$status_msg,$data)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}?>