<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
$data = array();
 
$select_fund_transaction = "select ft.*,f.fund_name,td.ledger_type_id,td.credit_amount,td.debit_amount,op.origination_point_name
                        from usaid_fund_transaction as ft
                        left join usaid_fund_transaction_detail as td ON td.transaction_id = ft.id
                        left join usaid_fund_transaction_detail as td1 ON td1.transaction_id = td.transaction_id
                        left join usaid_origination_point as op ON op.id = td1.ledger_type_id
                        left join usaid_fund as f ON f.fund_id = td.ledger_type_id where td.ledger_type='Fund' and td.credit_amount is not null and td1.ledger_type='Origination Point' group by ft.id ";


$result_fund_transaction = $mysqli->query($select_fund_transaction);
$i=0; 
while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
{
     $data[$i]['transaction_id'] = $fetch_fund_transaction['id'];  
     $data[$i]['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id'];  
     $data[$i]['transaction_type'] = $fetch_fund_transaction['transaction_type'];  
     $data[$i]['fund_name'] = $fetch_fund_transaction['fund_name'];
     $data[$i]['transaction_date'] = dateFormat($fetch_fund_transaction['transaction_date']);        
     $fund_beginning_fiscal_year = 'No-FY';
     $fund_ending_fiscal_year = 'No-FY';
     if($fetch_fund_transaction['fund_beginning_fiscal_year']!='NULL' and $fetch_fund_transaction['fund_beginning_fiscal_year']!='') $fund_beginning_fiscal_year = $fetch_fund_transaction['fund_beginning_fiscal_year'];
     if($fetch_fund_transaction['fund_ending_fiscal_year']!='NULL' and $fetch_fund_transaction['fund_ending_fiscal_year']!='') $fund_ending_fiscal_year = $fetch_fund_transaction['fund_ending_fiscal_year'];
     $data[$i]['fund_beginning_fiscal_year'] = $fund_beginning_fiscal_year;    
     $data[$i]['fund_ending_fiscal_year'] = $fund_ending_fiscal_year;  
     $data[$i]['fund_fiscal_year_restriction'] = $fetch_fund_transaction['fund_fiscal_year_restriction'];  
     $data[$i]['fund_amount'] = $fetch_fund_transaction['fund_amount'];  
     $data[$i]['credit_amount'] = $fetch_fund_transaction['credit_amount'];
     $data[$i]['debit_amount'] = $fetch_fund_transaction['debit_amount'];
     $data[$i]['origination_point'] = $fetch_fund_transaction['origination_point_name'];
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
 
?>