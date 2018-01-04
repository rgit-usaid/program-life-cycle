<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## Get all Unique Fund strip and Program Element============

$data = array(); 
$select_program_element ="select distinct concat(f.fund_id,
                        if(isnull(td.fund_beginning_fiscal_year), '', concat(' FY ',td.fund_beginning_fiscal_year)),  
                        if(isnull(td.fund_ending_fiscal_year),'', concat('-',td.fund_ending_fiscal_year)),concat(' '),tda.ledger_type_id) as gp_year,tdb.ledger_type_id,tda.ledger_type_id as ledger_type_id,td.fund_beginning_fiscal_year,td.fund_ending_fiscal_year ,td.fund_id,td.program_element_id
                        from usaid_fund_transaction_detail tda
                        left join usaid_fund_transaction td on tda.transaction_id=td.id
                        left join usaid_fund_transaction_detail tdb on tdb.transaction_id=td.id
            left join usaid_fund f on f.id=td.fund_id
                        where tda.id!=tdb.id and tda.ledger_type='Program Element' and tdb.ledger_type='Fund'"; 

$result_program_element = $mysqli->query($select_program_element) or die('Error '.$mysqli->error);
$i=0; 
while($fetch_program_element = $result_program_element->fetch_array())
{
    $data[$i]['gp_year'] = $fetch_program_element['gp_year'];  
    $data[$i]['ledger_type_id'] = $fetch_program_element['ledger_type_id'];  
     $data[$i]['fund_id'] = $fetch_program_element['fund_id'];
    $data[$i]['fund_beginning_fiscal_year'] = $fetch_program_element['fund_beginning_fiscal_year'];    
    $data[$i]['fund_ending_fiscal_year'] = $fetch_program_element['fund_ending_fiscal_year'];  
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