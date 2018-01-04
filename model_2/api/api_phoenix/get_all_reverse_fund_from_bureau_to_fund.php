<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");

## this code for get all reverse fund according to fund strip id with FY === 
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
    $data = array();
    $select_fund_transaction = "select t.*,ou.operating_unit_abbreviation as debit_from,ou.operating_unit_description as debit_from_description,td1.ledger_type_id ,td1.credit_amount,td1.debit_amount,f.fund_name
                            from usaid_fund_transaction as t
                            left join usaid_fund_transaction_detail as td1 ON td1.transaction_id = t.id
                            left join usaid_fund_transaction_detail as td2 ON td2.transaction_id = td1.transaction_id
                            left join usaid_fund as f ON f.fund_id = td1.ledger_type_id
                            left join usaid_operating_unit as ou ON ou.operating_unit_id = td2.ledger_type_id
                            where td1.ledger_type='Fund' and td2.ledger_type='Operating Unit' and ou.type='Bureau' and td1.credit_amount is not null  and td1.ledger_type_id='".$ledger_type_id."' ".$cond." group by t.id ";
    $result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error'. $mysqli->error);
    $i=0; 
    while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
    {
        $data[$i]['transaction_id'] = $fetch_fund_transaction['id'];  
        $data[$i]['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id'];
        $data[$i]['narration'] = $fetch_fund_transaction['narration'];
        $data[$i]['transaction_date'] = dateFormat($fetch_fund_transaction['transaction_date']);        
        $data[$i]['transaction_type'] = $fetch_fund_transaction['transaction_type'];  
        $data[$i]['debit_from'] = $fetch_fund_transaction['debit_from'];
        $data[$i]['debit_from_description'] = $fetch_fund_transaction['debit_from_description'];
        $data[$i]['fund_amount'] = $fetch_fund_transaction['fund_amount'];  
        $data[$i]['credit_amount'] = $fetch_fund_transaction['credit_amount'];
        $data[$i]['fund_name'] = $fetch_fund_transaction['fund_name']; 
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