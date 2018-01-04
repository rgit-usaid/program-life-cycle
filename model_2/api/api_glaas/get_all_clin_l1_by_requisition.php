<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all Clin 1 by requisition number============
if(isset($_REQUEST['requisition_number']) and $_REQUEST['requisition_number']!='')
{   
    $data = array();
    $requisition_number = trim($_REQUEST['requisition_number']);
    $cond = " and status != 'Remove'";
    if(isset($_REQUEST['flag']))
    {
        $cond = '';
    }
    $select_data = "select * from usaid_requisition_clin where requisition_number='".$requisition_number."' and level=1 and parent_clin_number is null ".$cond."";
    $result_data = $mysqli->query($select_data);
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
         $data[$i]['clin_id'] = $fetch_data['id'];  
         $data[$i]['requisition_number'] = $fetch_data['requisition_number'];
         $data[$i]['clin_number'] = $fetch_data['clin_number'];
         $data[$i]['clin_name'] = $fetch_data['clin_name'];
         $data[$i]['clin_description'] = $fetch_data['clin_description'];
         $data[$i]['clin_amount'] = $fetch_data['clin_amount'];
         $data[$i]['start_performance_period'] = dateFormat($fetch_data['start_performance_period']);
         $data[$i]['end_performance_period'] = dateFormat($fetch_data['end_performance_period']);  
         $data[$i]['share'] = $fetch_data['share'];
         $i++; 
    }
    if(count($data)>0){
        deliverResponse(200,'Record Found',$data);
    }
    else{
       deliverResponse(200,'No Record Found',NULL);
    }
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