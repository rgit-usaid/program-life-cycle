<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['ledger_type_id']) and $_REQUEST['ledger_type_id']!='')
{
    $data = array();
    $ledger_type_id = trim($_REQUEST['ledger_type_id']);
	$fund_id = trim($_REQUEST['fund_id']);
    $ledger_type = $_REQUEST['ledger_type'];
    $ledger_type = str_replace('_',' ',$ledger_type);
    $pe_id = trim($_REQUEST['pe_id']);
    $cond = "";
    if(isset($_REQUEST['b_year']) and isset($_REQUEST['e_year']) and $_REQUEST['b_year']!='' and $_REQUEST['e_year']!='')
    {
        $b_year = trim($_REQUEST['b_year']);
        $e_year = trim($_REQUEST['e_year']);
        $cond .= " and t.fund_beginning_fiscal_year='".$b_year."' and t.fund_ending_fiscal_year='".$e_year."' ";
    }
    else{
        $cond .= " and t.fund_beginning_fiscal_year is null and t.fund_ending_fiscal_year is null ";
    }    
  
	$select_fund_transaction = "select t.*,td1.ledger_type_id,td1.ledger_type,td1.credit_amount,td1.debit_amount,td2.ledger_type_id as op_id,td2.ledger_type as ledger_type_dr
                        from usaid_fund_transaction as t
                        left join usaid_fund_transaction_detail as td1 ON td1.transaction_id = t.id
                        left join usaid_fund_transaction_detail as td2 ON td2.transaction_id = t.id
                        where td1.ledger_type='".$ledger_type."' and td1.ledger_type_id='".$ledger_type_id."' and t.fund_id='".$fund_id."' and t.program_element_id='".$pe_id."' and  td1.fund_status='Subobligate' and td2.fund_status='Obligate' ".$cond." group by t.id ";
		
    $result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error'. $mysqli->error);
	
    $i=0; 
    while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
    {
        $data[$i]['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id']; // for commit in type id
		$data[$i]['ledger_type'] = $fetch_fund_transaction['ledger_type'];
        $data[$i]['ledger_type_dr'] = $fetch_fund_transaction['ledger_type_dr'];  
		$data[$i]['op_id'] = $fetch_fund_transaction['op_id'];
        $data[$i]['transaction_date'] = dateFormat($fetch_fund_transaction['transaction_date']);        
        $data[$i]['credit_amount'] = $fetch_fund_transaction['credit_amount'];
        $data[$i]['debit_amount'] = $fetch_fund_transaction['debit_amount'];
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