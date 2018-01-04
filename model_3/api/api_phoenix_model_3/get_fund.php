<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['fund_id']) and $_REQUEST['fund_id']!='')
{
    $data = array();
    $fund_id = $mysqli->real_escape_string(trim($_REQUEST['fund_id']));
    $select_fund = "select * from usaid_fund where fund_id = '".$fund_id."'";
    $result_fund = $mysqli->query($select_fund);
    $fetch_fund = $result_fund->fetch_array();
    if($fetch_fund['fund_id']!='')
    {
        $data['id'] = $fetch_fund['id'];
        $data['fund_id'] = $fetch_fund['fund_id'];
        $data['fund_name'] = $fetch_fund['fund_name']; 
        $data['fund_category'] = $fetch_fund['fund_category'];
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