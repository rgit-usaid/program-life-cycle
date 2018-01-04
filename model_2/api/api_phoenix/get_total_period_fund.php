<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get amount by fund id , be year and ending year============
if(isset($_REQUEST['ledger_type_id']) and $_REQUEST['ledger_type_id']!='')
{
     $cond = '';
    if(isset($_REQUEST['b_year']) and isset($_REQUEST['e_year']) and $_REQUEST['b_year']!='' and $_REQUEST['e_year']!='')
    {
        $b_year = trim($_REQUEST['b_year']);
        $e_year = trim($_REQUEST['e_year']);
        $cond = " and t.fund_beginning_fiscal_year='".$b_year."' and t.fund_ending_fiscal_year='".$e_year."' ";
    }
    else{
        $cond = " and t.fund_beginning_fiscal_year is null and t.fund_ending_fiscal_year is null ";
    }    
    $ledger_type_id = trim($_REQUEST['ledger_type_id']);

    $data = array();
    $select_fund_transaction = "select td.closing_balance,td.transaction_id,td.opening_balance
                                from usaid_fund_transaction_detail td
                                left join usaid_fund_transaction t
                                on td.transaction_id=t.id
                                where td.ledger_type_id='".$ledger_type_id."'
                                ".$cond."
                                order by td.id desc
                                limit 1"; 
    $result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error '.$mysqli->error);
    $fetch_fund_transaction = $result_fund_transaction->fetch_array();
    $data['transaction_id'] =  $fetch_fund_transaction['transaction_id'];
    $data['opening_balance'] =  $fetch_fund_transaction['opening_balance'];
    $data['closing_balance'] =  $fetch_fund_transaction['closing_balance'];
    if($fetch_fund_transaction['transaction_id']>0){
        deliverResponse(200,'Record Found',$data);
    }
    else{
       deliverResponse(200,'No Record Found',NULL);
    }
}else deliverResponse(200,'Invalid Request',NULL);    
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