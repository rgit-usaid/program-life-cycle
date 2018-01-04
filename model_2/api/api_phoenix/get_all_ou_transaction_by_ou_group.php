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
    $fund_id = $_REQUEST['fund_id'];
    $pe_id = $_REQUEST['program_element_id'];
    $cond = " and t.program_element_id='".$pe_id."' and t.fund_id='".$fund_id."' ";
    if(isset($_REQUEST['b_year']) and isset($_REQUEST['e_year']) and $_REQUEST['b_year']!='' and $_REQUEST['e_year']!='')
    {
        $b_year = trim($_REQUEST['b_year']);
        $e_year = trim($_REQUEST['e_year']);
        $cond .= " and t.fund_beginning_fiscal_year='".$b_year."' and t.fund_ending_fiscal_year='".$e_year."' ";
    }
    else{
        $cond .= " and t.fund_beginning_fiscal_year is null and t.fund_ending_fiscal_year is null ";
    }    
    $select_fund_transaction = "select t.*,ou.operating_unit_abbreviation,ou.operating_unit_description,td1.ledger_type_id ,sum(td1.debit_amount) as total_debit_amount,ou1.operating_unit_abbreviation as origination_point
                        from usaid_fund_transaction as t
                        left join usaid_fund_transaction_detail as td1 ON td1.transaction_id = t.id
                        left join usaid_fund_transaction_detail as td2 ON td2.transaction_id = t.id
                        left join usaid_operating_unit as ou ON ou.operating_unit_id = td1.ledger_type_id
                        left join usaid_operating_unit as ou1 ON ou1.operating_unit_id = td2.ledger_type_id  
                        where td1.ledger_type='Operating Unit' and td2.ledger_type='Operating Unit' and ou.type!='Bureau' and ou1.type='Bureau' and td1.debit_amount is not null and td1.ledger_type_id='".$ledger_type_id."' ".$cond."";                
 
    $result_fund_transaction = $mysqli->query($select_fund_transaction) or die('Error'. $mysqli->error);
    $i=0; 
    while($fetch_fund_transaction = $result_fund_transaction->fetch_array())
    {
         $data[$i]['ledger_type_id'] = $fetch_fund_transaction['ledger_type_id'];
         $data[$i]['transaction_date'] = dateFormat($fetch_fund_transaction['transaction_date']);        
         $data[$i]['transaction_type'] = $fetch_fund_transaction['transaction_type'];  
         $data[$i]['operating_unit_abbreviation'] = $fetch_fund_transaction['operating_unit_abbreviation'];
         $data[$i]['operating_unit_description'] = $fetch_fund_transaction['operating_unit_description'];
         $data[$i]['total_debit_amount'] = $fetch_fund_transaction['total_debit_amount'];
         $data[$i]['origination_point'] = $fetch_fund_transaction['origination_point'];
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