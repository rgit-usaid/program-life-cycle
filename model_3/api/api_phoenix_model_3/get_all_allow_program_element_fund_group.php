<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## code for get top list of allot to program element in group============
$data = array();
$select_fund_transaction = "select t.*,f.fund_name,f.fund_id as fund_code,td.ledger_type_id ,sum(td.credit_amount) as total_amount
                        from usaid_fund_transaction as t
                        left join usaid_fund_transaction_detail as td ON td.transaction_id = t.id
                        left join usaid_fund as f ON f.id = t.fund_id
                        where td.ledger_type='Program Element' and td.credit_amount is not null group by td.ledger_type_id,t.fund_beginning_fiscal_year,t.fund_ending_fiscal_year,t.fund_id,t.program_element_id ";

$result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error'. $mysqli->error);
$i=0; 
while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
{
     $data[$i]['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id']; // program element id cr in 
     $data[$i]['total_amount'] = $fetch_fund_transaction['total_amount']; 
     $data[$i]['narration'] = $fetch_fund_transaction['narration']; 
     $data[$i]['fund_id'] = $fetch_fund_transaction['fund_id']; //fund id from transaction table as 1,2,3    
     $data[$i]['fund_name'] = $fetch_fund_transaction['fund_name'];
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

 
?>