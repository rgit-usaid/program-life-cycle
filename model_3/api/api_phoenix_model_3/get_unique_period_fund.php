<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
$data = array();

$select_fund_transaction = "select distinct concat(f.fund_id,  
                            if(isnull(t.fund_beginning_fiscal_year), '', concat(' FY ',t.fund_beginning_fiscal_year)),  
                            if(isnull(t.fund_ending_fiscal_year),'', concat('-',t.fund_ending_fiscal_year))) as gp_year,td.ledger_type_id,t.fund_beginning_fiscal_year,t.fund_ending_fiscal_year 
                            from usaid_fund_transaction_detail td
                            left join usaid_fund_transaction t on td.transaction_id=t.id
                            left join usaid_fund f on f.id=t.fund_id
                            where td.ledger_type='Fund'"; 
$result_fund_transaction = $mysqli->query($select_fund_transaction);
$i=0; 
while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
{
     $data[$i]['gp_year'] = $fetch_fund_transaction['gp_year'];  
     $data[$i]['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id'];  
     $data[$i]['transaction_type'] = $fetch_fund_transaction['transaction_type']; 
     $data[$i]['fund_beginning_fiscal_year'] = $fetch_fund_transaction['fund_beginning_fiscal_year'];    
     $data[$i]['fund_ending_fiscal_year'] = $fetch_fund_transaction['fund_ending_fiscal_year'];  
     $i++; 
}
if(count($data)>0){
    deliverResponse(200,'Record Found',$data);
}
else{
   deliverResponse(200,'No Record Found',NULL);
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