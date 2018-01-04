<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## get all budget of a clin============
if(isset($_REQUEST['clin_number']) and $_REQUEST['clin_number']!='')
{   
    $data = array();
    $clin_number = trim($_REQUEST['clin_number']);
    $select_data = "select * from usaid_requisition_clin_budget where budget_number='".$clin_number."'";
    $result_data = $mysqli->query($select_data);
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
         $data[$i]['budget_id'] = $fetch_data['id'];  
         $data[$i]['clin_number'] = $fetch_data['clin_number'];
         $data[$i]['cost_code'] = $fetch_data['cost_code'];
         $data[$i]['code_description'] = $fetch_data['code_description'];
         $data[$i]['budget_amount'] = $fetch_data['budget_amount'];
         $data[$i]['budget_type'] = $fetch_data['budget_type']; 
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