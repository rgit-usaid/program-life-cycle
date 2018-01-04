<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all funded operating unit============
 
$data = array();
$select_data = "select td.ledger_type_id,ou.operating_unit_id,ou.operating_unit_description,ou.operating_unit_abbreviation
            from usaid_fund_transaction_detail td 
            left join usaid_operating_unit as ou ON ou.operating_unit_id = td.ledger_type_id 
            where td.ledger_type='Operating Unit' and ou.type!='Bureau' and td.credit_amount is not null group by td.ledger_type_id"; 
$result_data = $mysqli->query($select_data) or die('Error '.$mysqli->error);
$i=0; 
while($fetch_data = $result_data->fetch_array())
{
    $data[$i]['ledger_type_id'] = $fetch_data['ledger_type_id'];
    $data[$i]['operating_unit_id'] = $fetch_data['operating_unit_id'];    
    $data[$i]['operating_unit_description'] = $fetch_data['operating_unit_description'];  
    $data[$i]['operating_unit_abbreviation'] = $fetch_data['operating_unit_abbreviation'];
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