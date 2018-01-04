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
    $op_id = $_REQUEST['op_id'];
    $cond = "";
    if(isset($_REQUEST['b_year']) and isset($_REQUEST['e_year']) and $_REQUEST['b_year']!='' and $_REQUEST['e_year']!='')
    {
        $b_year = trim($_REQUEST['b_year']);
        $e_year = trim($_REQUEST['e_year']);
        $cond .= " and ft.fund_beginning_fiscal_year='".$b_year."' and ft.fund_ending_fiscal_year='".$e_year."' ";
    }
    else{
        $cond .= " and ft.fund_beginning_fiscal_year is null and ft.fund_ending_fiscal_year is null ";
    }   
  
    $data = array();
    $select_fund_transaction = "select ft.*,f.fund_name,td.ledger_type_id,sum(td.credit_amount) as total_amount ,td.debit_amount,op.origination_point_name,td1.ledger_type_id as op_id
                            from usaid_fund_transaction as ft
                            left join usaid_fund_transaction_detail as td ON td.transaction_id = ft.id
                            left join usaid_fund_transaction_detail as td1 ON td1.transaction_id = td.transaction_id
                            left join usaid_origination_point as op ON op.id = td1.ledger_type_id
                            left join usaid_fund as f ON f.fund_id = td.ledger_type_id where td.ledger_type='Fund' and td.credit_amount is not null and td1.ledger_type='Origination Point' and td.ledger_type_id='".$ledger_type_id."' ".$cond."   group by td1.ledger_type_id,ft.fund_beginning_fiscal_year,ft.fund_ending_fiscal_year order by td.ledger_type_id";
    $result_fund_transaction = $mysqli->query($select_fund_transaction);
    $i=0; 
    while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
    {
         $data[$i]['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id'];  
         $data[$i]['transaction_type'] = $fetch_fund_transaction['transaction_type'];  
         
         $data[$i]['fund_name'] = $fetch_fund_transaction['fund_name'];       
         $data[$i]['fund_beginning_fiscal_year'] = $fetch_fund_transaction['fund_beginning_fiscal_year'];    
         $data[$i]['fund_ending_fiscal_year'] = $fetch_fund_transaction['fund_ending_fiscal_year'];  
         $data[$i]['fund_fiscal_year_restriction'] = $fetch_fund_transaction['fund_fiscal_year_restriction'];  
         $data[$i]['total_amount'] = $fetch_fund_transaction['total_amount'];
         $data[$i]['origination_point'] = $fetch_fund_transaction['origination_point_name'];
         $data[$i]['op_id'] = $fetch_fund_transaction['op_id'];
         $i++; 
    }
    if(count($data)>0){
        deliverResponse(200,'Record Found',$data);
    }
    else{
       deliverResponse(200,'No Record Found',NULL);
    }
}
else
{
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