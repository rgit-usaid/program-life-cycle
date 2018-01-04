<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get vendor details============
if(isset($_REQUEST['clin_number']) and $_REQUEST['clin_number']!='')
{
    $data = array();
    $clin_number =  trim($_REQUEST['clin_number']);
    $select_data = "select * from usaid_requisition_award_clin where clin_number = '".$clin_number."'";
    $result_data = $mysqli->query($select_data);
    $fetch_data = $result_data->fetch_array();
    if($fetch_data['id']!='')
    {
        $data['clin_id'] = $fetch_data['id'];  
        $data['requisition_number'] = $fetch_data['requisition_number'];
        $data['clin_number'] = $fetch_data['clin_number'];
        $data['clin_name'] = $fetch_data['clin_name'];
        $data['clin_description'] = $fetch_data['clin_description'];
        $data['clin_amount'] = $fetch_data['clin_amount'];
        $data['start_performance_period'] = dateFormat($fetch_data['start_performance_period']);
        $data['end_performance_period'] = dateFormat($fetch_data['end_performance_period']);  
        $data['operating_unit_id'] = $fetch_data['operating_unit_id'];
        $data['employee_id'] = $fetch_data['employee_id'];
        $data['modification_number'] = $fetch_data['modification_number'];
        $data['modification_purpose'] = $fetch_data['modification_purpose'];
        $data['do_not_share'] = $fetch_data['do_not_share'];
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