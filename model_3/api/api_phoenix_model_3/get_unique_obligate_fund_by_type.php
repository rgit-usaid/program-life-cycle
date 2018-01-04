<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['ledger_type_id']))
{
    $data = array(); 
	$ledger_type_id = $_REQUEST['ledger_type_id'];
	$ledger_type = $_REQUEST['ledger_type'];
	$ledger_type = str_replace('_',' ',$ledger_type);
	
	
    $select_operating_unit = "SELECT t.*,f.fund_id as fund_code 
                            FROM usaid_fund_transaction t 
                            left join usaid_fund_transaction_detail td on td.transaction_id=t.id
                            left join usaid_fund as f ON f.id = t.fund_id 
                            WHERE td.ledger_type_id='".$ledger_type_id."' and td.ledger_type='".$ledger_type."' and td.fund_status='Obligate' and td.credit_amount is not null 
                            group by td.ledger_type_id,t.fund_id,t.fund_beginning_fiscal_year,t.fund_ending_fiscal_year,t.program_element_id";
    $result_operating_unit = $mysqli->query($select_operating_unit) or die('Error '.$mysqli->error);
    $i=0; 
    while($fetch_operating_unit = $result_operating_unit->fetch_array())
    {
        $data[$i]['ledger_type_id'] = $fetch_operating_unit['ledger_type_id'];
		$data[$i]['ledger_type'] = $fetch_operating_unit['ledger_type'];  
        $data[$i]['fund_beginning_fiscal_year'] = $fetch_operating_unit['fund_beginning_fiscal_year'];    
        $data[$i]['fund_ending_fiscal_year'] = $fetch_operating_unit['fund_ending_fiscal_year'];
		$data[$i]['fund_id'] = $fetch_operating_unit['fund_id'];
		$data[$i]['fund_code'] = $fetch_operating_unit['fund_code'];    
        $data[$i]['program_element_id'] = $fetch_operating_unit['program_element_id'];  
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