<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get fund time period ===========
if(isset($_REQUEST['transaction_id']) and $_REQUEST['transaction_id']!='')
{
    $data = array();
    $select_fund_transaction = "select ft.*,f.fund_name,ftd.ledger_type_id 
                            from usaid_fund_transaction as ft
                            left join usaid_fund_transaction_detail as ftd ON ftd.transaction_id = ft.id
                            left join usaid_fund as f ON f.fund_id = ftd.ledger_type_id where ftd.ledger_type='Fund' and ft.id='".$_REQUEST['transaction_id']."'";
    $result_fund_transaction = $mysqli->query($select_fund_transaction);
    $fetch_fund_transaction = $result_fund_transaction->fetch_array();
    
     $data['transaction_id'] = $fetch_fund_transaction['id'];  
     $data['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id'];  
     $data['transaction_type'] = $fetch_fund_transaction['transaction_type'];  
     $data['fund_name'] = $fetch_fund_transaction['fund_name'];
     $fund_beginning_fiscal_year = 'No-FY';
     $fund_ending_fiscal_year = 'No-FY';
     if($fetch_fund_transaction['fund_beginning_fiscal_year']!='NULL' and $fetch_fund_transaction['fund_beginning_fiscal_year']!='') $fund_beginning_fiscal_year = $fetch_fund_transaction['fund_beginning_fiscal_year'];
     if($fetch_fund_transaction['fund_ending_fiscal_year']!='NULL' and $fetch_fund_transaction['fund_ending_fiscal_year']!='') $fund_ending_fiscal_year = $fetch_fund_transaction['fund_ending_fiscal_year'];
     $data['fund_beginning_fiscal_year'] = $fund_beginning_fiscal_year;    
     $data['fund_ending_fiscal_year'] = $fund_ending_fiscal_year;  
     $data['fund_fiscal_year_restriction'] = $fetch_fund_transaction['fund_fiscal_year_restriction'];  
     $data['fund_amount'] = $fetch_fund_transaction['fund_amount'];  
     $data['credit_amount'] = $fetch_fund_transaction['credit_amount'];
     $data['origination_point'] = getOriginationPoint($fetch_fund_transaction['id']);
     $data['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id'];
    
    if($data['transaction_id']!=''){
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

function getOriginationPoint($transaction_id)
{
    global $mysqli;
    $select_fund_transaction = "select ledger_type_id from usaid_fund_transaction_detail where ledger_type='Origination Point' and transaction_id='". $transaction_id."'";
    $result_fund_transaction = $mysqli->query($select_fund_transaction);
    $fetch_fund_transaction = $result_fund_transaction->fetch_array();
    return $fetch_fund_transaction['ledger_type_id']; 
}
?>