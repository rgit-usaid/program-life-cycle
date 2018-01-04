<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
$data = array(); 
$select_operating_unit = "select t.*,tda.ledger_type_id as ledger_type_id_cr,tda.ledger_type,t.fund_id,f.fund_id as fund_code, concat(f.fund_id,  
                        if(isnull(t.fund_beginning_fiscal_year), '', concat(' FY ',t.fund_beginning_fiscal_year)),  
                        if(isnull(t.fund_ending_fiscal_year),'', concat('-',t.fund_ending_fiscal_year)),concat(' ',t.program_element_id)) as gp_year
                        from `usaid_fund_transaction` as t
                        left join usaid_fund_transaction_detail as tda ON tda.transaction_id = t.id                      
                        left join usaid_fund as f ON f.id = t.fund_id
                        WHERE tda.fund_status = 'Obligate' and tda.credit_amount is not null group by tda.ledger_type_id,t.fund_id,t.fund_beginning_fiscal_year,t.fund_ending_fiscal_year,t.program_element_id";
$result_operating_unit = $mysqli->query($select_operating_unit) or die('Error '.$mysqli->error);
$i=0; 
while($fetch_operating_unit = $result_operating_unit->fetch_array())
{
    $data[$i]['ledger_type_id_cr'] = $fetch_operating_unit['ledger_type_id_cr'];
    $data[$i]['ledger_type'] = $fetch_operating_unit['ledger_type'];
    $data[$i]['ledger_type_id_dr'] = $fetch_operating_unit['ledger_type_id_dr'];
    $data[$i]['fund_id'] = $fetch_operating_unit['fund_id'];
    $data[$i]['fund_code'] = $fetch_operating_unit['fund_code'];
    $data[$i]['gp_year'] = $fetch_operating_unit['gp_year'];    
    $data[$i]['program_element_id'] = $fetch_operating_unit['program_element_id'];  
    $data[$i]['fund_beginning_fiscal_year'] = $fetch_operating_unit['fund_beginning_fiscal_year'];    
    $data[$i]['fund_ending_fiscal_year'] = $fetch_operating_unit['fund_ending_fiscal_year'];  
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