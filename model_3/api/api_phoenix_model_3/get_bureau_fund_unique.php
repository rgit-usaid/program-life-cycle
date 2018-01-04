<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['operating_unit_id']))
{
    $operating_unit_id = trim($_REQUEST['operating_unit_id']);
    $data = array(); 
    $select_operating_unit = "select distinct concat(f.fund_id,
                            if(isnull(td.fund_beginning_fiscal_year), '', concat(' FY ',td.fund_beginning_fiscal_year)),  
                            if(isnull(td.fund_ending_fiscal_year),'', concat('-',td.fund_ending_fiscal_year)), concat(' ',tdb.ledger_type_id)) as gp_year,tdb.ledger_type_id,ou.operating_unit_abbreviation,ou.operating_unit_description,tdb.ledger_type_id,td.fund_beginning_fiscal_year,td.fund_ending_fiscal_year ,td.fund_id,td.program_element_id
                            from usaid_fund_transaction_detail tda
                            left join usaid_fund_transaction td on tda.transaction_id=td.id
                            left join usaid_fund_transaction_detail tdb on tdb.transaction_id=td.id
                            left join usaid_operating_unit as ou ON ou.operating_unit_id = tda.ledger_type_id  
                            left join usaid_fund f on f.id=td.fund_id
                            where tda.id!=tdb.id and tda.ledger_type_id='".$operating_unit_id."' and tda.ledger_type='Operating Unit' and tdb.ledger_type='Program Element'";

    $result_operating_unit = $mysqli->query($select_operating_unit) or die('Error '.$mysqli->error);
    $i=0; 
    while($fetch_operating_unit = $result_operating_unit->fetch_array())
    {
        $data[$i]['gp_year'] = $fetch_operating_unit['gp_year'];  
        $data[$i]['ledger_type_id'] = $fetch_operating_unit['ledger_type_id'];  
        $data[$i]['transaction_type'] = $fetch_operating_unit['transaction_type'];
        $data[$i]['fund_id'] = $fetch_operating_unit['fund_id']; 
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