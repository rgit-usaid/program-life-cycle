<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
$data = array();
$select_fund_transaction = "select t.*,ou.operating_unit_abbreviation,ou.operating_unit_description,td1.ledger_type_id ,td1.credit_amount,td1.debit_amount,ou1.operating_unit_abbreviation as origination_point
                        from usaid_fund_transaction as t
                        left join usaid_fund_transaction_detail as td1 ON td1.transaction_id = t.id
                        left join usaid_fund_transaction_detail as td2 ON td2.transaction_id = td1.transaction_id
                        left join usaid_operating_unit as ou ON ou.operating_unit_id = td1.ledger_type_id
                        left join usaid_operating_unit as ou1 ON ou1.operating_unit_id = td2.ledger_type_id  
                        where td1.ledger_type='Operating Unit' and td2.ledger_type='Operating Unit' and ou.type!='Bureau' and ou1.type='Bureau' and td1.credit_amount is not null and related_transaction is null group by t.id ";
 
$result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error'. $mysqli->error);
$i=0; 
while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
{
     $data[$i]['transaction_id'] = $fetch_fund_transaction['id'];  
     $data[$i]['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id'];
     $data[$i]['narration'] = $fetch_fund_transaction['narration'];
     $data[$i]['transaction_date'] = dateFormat($fetch_fund_transaction['transaction_date']);        
     $data[$i]['transaction_type'] = $fetch_fund_transaction['transaction_type'];  
     $data[$i]['operating_unit_abbreviation'] = $fetch_fund_transaction['operating_unit_abbreviation'];
     $data[$i]['operating_unit_description'] = $fetch_fund_transaction['operating_unit_description'];
     $data[$i]['fund_amount'] = $fetch_fund_transaction['fund_amount'];  
     $data[$i]['credit_amount'] = $fetch_fund_transaction['credit_amount'];
     $data[$i]['debit_amount'] = $fetch_fund_transaction['debit_amount'];
     $data[$i]['origination_point'] = $fetch_fund_transaction['origination_point'];
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