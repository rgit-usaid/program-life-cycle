<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['ledger_type_id']) and $_REQUEST['ledger_type_id']!='' && isset($_REQUEST['fund_status']) and $_REQUEST['fund_status']!='')
{
    $data = array();
    $ledger_type_id = trim($_REQUEST['ledger_type_id']);
	$fund_status = trim($_REQUEST['fund_status']);
	
    $select_fund_transaction = "select td.transaction_year, sum(td.credit_amount) as credit_amount
                        from usaid_fund_transaction_detail as td
                        left join usaid_fund_transaction as t ON td.transaction_id = t.id
                        where td.ledger_type='Project Activity' and td.fund_status='".$fund_status."' and td.credit_amount is not null and td.ledger_type_id='".$ledger_type_id."' and t.transaction_type='Allocate' group by td.ledger_type_id, td.transaction_year, td.fund_status";

    $result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error'. $mysqli->error);
    $i=0; 
    while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
    {
         $data[$i]['credit_amount'] = $fetch_fund_transaction['credit_amount'];
		 $data[$i]['transaction_year'] = $fetch_fund_transaction['transaction_year'];	
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