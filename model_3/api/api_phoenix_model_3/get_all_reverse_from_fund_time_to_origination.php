<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
$data = array();
$select_fund_transaction = "select t.*,op.origination_point_name as credit_to,td1.ledger_type_id ,td1.credit_amount,td1.debit_amount,f.fund_name
                        from usaid_fund_transaction as t
                        left join usaid_fund_transaction_detail as td1 ON td1.transaction_id = t.id
                        left join usaid_fund_transaction_detail as td2 ON td2.transaction_id = td1.transaction_id
                        left join usaid_origination_point as op ON op.id = td1.ledger_type_id
                        left join usaid_fund as f ON f.fund_id = td2.ledger_type_id
                        where td1.ledger_type='Origination Point' and td2.ledger_type='Fund' and td1.credit_amount is not null group by t.id ";
 
$result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error'. $mysqli->error);
$i=0; 
while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
{
    $debit_from = '';
     $data[$i]['transaction_id'] = $fetch_fund_transaction['id'];  
     $data[$i]['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id'];
     $data[$i]['narration'] = $fetch_fund_transaction['narration'];
     $data[$i]['transaction_date'] = dateFormat($fetch_fund_transaction['transaction_date']);        
     $data[$i]['transaction_type'] = $fetch_fund_transaction['transaction_type'];  
     $data[$i]['debit_from'] = $fetch_fund_transaction['debit_from'];
     $data[$i]['fund_amount'] = $fetch_fund_transaction['fund_amount'];  
     $data[$i]['credit_amount'] = $fetch_fund_transaction['credit_amount'];
     $data[$i]['debit_amount'] = $fetch_fund_transaction['debit_amount'];
    $data[$i]['fund_name'] = $fetch_fund_transaction['fund_name'];
    $data[$i]['credit_to'] = $fetch_fund_transaction['credit_to'];
     if($fetch_fund_transaction['fund_beginning_fiscal_year']!='' and $fetch_fund_transaction['fund_beginning_fiscal_year']!=NULL)
     {
        $debit_from = $fetch_fund_transaction['debit_from'].' FY '.$fetch_fund_transaction['fund_beginning_fiscal_year'].'-'.$fetch_fund_transaction['fund_ending_fiscal_year'];
     }
     else
     {
         $debit_from = $fetch_fund_transaction['debit_from'];
     }

     $data[$i]['debit_from'] = $debit_from;
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