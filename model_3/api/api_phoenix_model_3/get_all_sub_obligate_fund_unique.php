<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all Fund Strip by ledger id===========
if(isset($_REQUEST['ledger_type_id']) and $_REQUEST['ledger_type_id']!='')
{
	$data = array();
    $ledger_type_id = trim($_REQUEST['ledger_type_id']);
	$select_fund_transaction = "select t.*,td.ledger_type_id,td.ledger_type,td.fund_status
						from usaid_fund_transaction as t
                        left join usaid_fund_transaction_detail as td ON td.transaction_id = t.id 
                       where td.ledger_type_id='".$ledger_type_id."' and (td.fund_status='Subobligate' or td.fund_status='Obligate') and td.ledger_type='Award CLIN' group by td.ledger_type_id,t.fund_id,t.fund_beginning_fiscal_year,t.fund_ending_fiscal_year,t.program_element_id,td.fund_status";

	$result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error'. $mysqli->error);
    $i=0; 
    while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
    {
        $data[$i]['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id'];
    	$data[$i]['ledger_type'] = $fetch_fund_transaction['ledger_type'];
        $data[$i]['fund_status'] = $fetch_fund_transaction['fund_status'];    
    	$data[$i]['program_element_id'] = $fetch_fund_transaction['program_element_id'];       
        $data[$i]['fund_id'] = $fetch_fund_transaction['fund_id'];
    	$data[$i]['fund_beginning_fiscal_year'] = $fetch_fund_transaction['fund_beginning_fiscal_year'];    
        $data[$i]['fund_ending_fiscal_year'] = $fetch_fund_transaction['fund_ending_fiscal_year'];  
        $data[$i]['fund_fiscal_year_restriction'] = $fetch_fund_transaction['fund_fiscal_year_restriction'];  
        $data[$i]['narration'] = $fetch_fund_transaction['narration'];  
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
 