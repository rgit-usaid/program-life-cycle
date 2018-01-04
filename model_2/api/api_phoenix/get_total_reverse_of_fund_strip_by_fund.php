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
  
    $select_fund_transaction = "select t.*,td.ledger_type_id,sum(td.debit_amount) as total_debit_amount ,op.origination_point_name
                        from usaid_fund_transaction as t
                        left join usaid_fund_transaction_detail as td ON td.transaction_id = t.id
                        left join usaid_fund_transaction_detail as td1 ON td1.transaction_id = td.transaction_id
                        left join usaid_origination_point as op ON op.id = td1.ledger_type_id
                        where td.ledger_type='Fund' and td1.ledger_type='Origination Point' and td.debit_amount is not null and td.ledger_type_id='".$ledger_type_id."' ".$cond."  group by td.ledger_type_id ";

    $result_fund_transaction = $mysqli->query($select_fund_transaction);
    $fetch_fund_transaction = $result_fund_transaction->fetch_array();
    
    if($fetch_fund_transaction['id']>0){
        $data['total_debit_amount'] = $fetch_fund_transaction['total_debit_amount']; 
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