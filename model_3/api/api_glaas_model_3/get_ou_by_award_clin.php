<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['ledger_type_id']))
{
    $data = array();
    $ledger_type_id = $mysqli->real_escape_string(trim($_REQUEST['ledger_type_id']));
    $select_data = "select ra.operating_unit_id
                        from usaid_requisition_award as ra
                        left join usaid_requisition_award_clin as rac ON rac.award_number = ra.award_number 
                        where (ra.award_number = '".$ledger_type_id."' or rac.clin_number='".$ledger_type_id."')";
    $result_data = $mysqli->query($select_data);
    $fetch_data = $result_data->fetch_array();
    if($fetch_data['operating_unit_id']!='')
    {
        $data['operating_unit_id'] = $fetch_data['operating_unit_id'];       
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