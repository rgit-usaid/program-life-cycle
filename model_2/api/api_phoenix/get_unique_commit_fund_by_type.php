<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['ledger_type']))
{
    $data = array(); 
	$ledger_type = $_REQUEST['ledger_type']; 
	$ledger_type = str_replace('_',' ',$ledger_type);

    $select_data = "SELECT distinct distinct concat(f.fund_id,  
                            if(isnull(td.fund_beginning_fiscal_year), '', concat(' FY ',td.fund_beginning_fiscal_year)),  
                            if(isnull(td.fund_ending_fiscal_year),'', concat('-',td.fund_ending_fiscal_year)),concat(' ',td.program_element_id),concat(' -',tda.ledger_type_id)) as gp_year, tda.ledger_type_id, td.fund_beginning_fiscal_year, td.fund_ending_fiscal_year, td.program_element_id, td.transaction_type, td.program_element_id, 
							td.fund_id, tda.ledger_type
                            FROM usaid_fund_transaction_detail tda 
                            left join usaid_fund_transaction td on tda.transaction_id=td.id
                            left join usaid_fund_transaction_detail tdb on tdb.transaction_id=td.id
                            left join usaid_operating_unit ou on ou.operating_unit_id=tdb.ledger_type_id
                            left join usaid_fund f on f.id=td.fund_id
                            WHERE tda.id!=tdb.id and tda.ledger_type='".$ledger_type."' and tda.fund_status='Commit' and tdb.ledger_type='Operating Unit' and ou.type!='Bureau'";
    $result_data = $mysqli->query($select_data) or die('Error '.$mysqli->error);
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
        $data[$i]['ledger_type_id'] = $fetch_data['ledger_type_id'];
		$data[$i]['gp_year'] = $fetch_data['gp_year'];  
        $data[$i]['ledger_type'] = $fetch_data['ledger_type'];  
        $data[$i]['fund_beginning_fiscal_year'] = $fetch_data['fund_beginning_fiscal_year'];    
        $data[$i]['fund_ending_fiscal_year'] = $fetch_data['fund_ending_fiscal_year'];
		$data[$i]['fund_id'] = $fetch_data['fund_id'];    
        $data[$i]['program_element_id'] = $fetch_data['program_element_id'];  
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