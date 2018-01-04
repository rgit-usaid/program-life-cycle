<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
$data = array();
$select_operating_unit = "select ou.*
                        from usaid_fund_transaction_detail as td
                        left join usaid_operating_unit as ou ON ou.operating_unit_id = td.ledger_type_id
                        where ou.type='Bureau' group by td.ledger_type_id"; 
$result_operating_unit = $mysqli->query($select_operating_unit) or die('Error '.$mysqli->error);
$i=0; 
while($fetch_operating_unit = $result_operating_unit->fetch_array())
{
    $data[$i]['id'] = $fetch_operating_unit['id'];  
    $data[$i]['operating_unit_id'] = $fetch_operating_unit['operating_unit_id'];  
    $data[$i]['operating_unit_description'] = $fetch_operating_unit['operating_unit_description'];  
    $data[$i]['operating_unit_abbreviation'] = $fetch_operating_unit['operating_unit_abbreviation'];
    $data[$i]['parent_operating_unit_id'] = $fetch_operating_unit['parent_operating_unit_id'];    
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