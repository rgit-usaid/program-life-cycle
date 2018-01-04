<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all Clin 1 by award number============
if(isset($_REQUEST['award_number']) and $_REQUEST['award_number']!='')
{   
    $data = array();
    $award_number = trim($_REQUEST['award_number']);
    $cond = " and status != 'Remove'";
    if(isset($_REQUEST['flag']))
    {
        $cond = '';
    }
    $select_data = "select * from usaid_requisition_award_clin where award_number='".$award_number."' and level=1 and parent_clin_number is null ".$cond."";
    $result_data = $mysqli->query($select_data);
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
         $data[$i]['clin_id'] = $fetch_data['id'];  
         $data[$i]['award_number'] = $fetch_data['award_number'];
         $data[$i]['clin_number'] = $fetch_data['clin_number'];
         $data[$i]['clin_name'] = $fetch_data['clin_name'];
         $data[$i]['clin_description'] = $fetch_data['clin_description'];
         $data[$i]['clin_amount'] = $fetch_data['clin_amount'];
         $data[$i]['start_performance_period'] = dateFormat($fetch_data['start_performance_period']);
         $data[$i]['end_performance_period'] = dateFormat($fetch_data['end_performance_period']);  
         $data[$i]['operating_unit_id'] = $fetch_data['operating_unit_id'];
         $data[$i]['employee_id'] = $fetch_data['employee_id'];
         $data[$i]['modification_number'] = $fetch_data['modification_number'];
         $data[$i]['modification_purpose'] = $fetch_data['modification_purpose'];
         $data[$i]['do_not_share'] = $fetch_data['do_not_share'];
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