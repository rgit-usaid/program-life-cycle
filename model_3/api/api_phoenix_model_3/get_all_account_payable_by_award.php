<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['data_award']) and $_REQUEST['data_award']!='')
{   
    $data_award = $str = str_replace('\\', '', $_REQUEST['data_award']);
    $award_arr = unserialize(urldecode($data_award));
    $ledger_type_id_str = '';
    for($k=0; $k<count($award_arr); $k++)
    {
        $ledger_type_id_str .= "'".$award_arr[$k]."',"; // all award       
    }
    $ledger_type_id_str = substr($ledger_type_id_str, 0,-1);
    $data = array();
    $ledger_type_id = trim($_REQUEST['ledger_type_id']); 

    $select_fund_transaction = "select * FROM usaid_account_payble WHERE award_instrument_no in(".$ledger_type_id_str.")";                    

    $result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error'. $mysqli->error);
    $i=0; 
    while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
    {
         ## get all account payble by  Award and Project ============
         $data[$i]['total_paid_amount'] = $fetch_fund_transaction['total_invoice_paid'];  
         $data[$i]['total_invoice_amt'] = $fetch_fund_transaction['total_invoice_amt'];
         $data[$i]['invoice_date'] = $fetch_fund_transaction['invoice_date']; 
         $i++; 
    }
    if(count($data)>0){
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
}
 
?>