<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get OU by ID============
if(isset($_REQUEST['operating_unit_id']))
{ 
    $data = array();
    $operating_unit_id = $_REQUEST['operating_unit_id']; 
    $select_operating_unit = "select * from usaid_operating_unit where  operating_unit_id = '".$operating_unit_id."' order by id"; 
    $result_operating_unit = $mysqli->query($select_operating_unit) or die('Error '.$mysqli->error);
    $fetch_operating_unit = $result_operating_unit->fetch_array(); 
    if($fetch_operating_unit['id']>0)
    {
        $data['id'] = $fetch_operating_unit['id'];  
        $data['operating_unit_id'] = $fetch_operating_unit['operating_unit_id'];  
        $data['operating_unit_description'] = $fetch_operating_unit['operating_unit_description'];  
        $data['operating_unit_abbreviation'] = $fetch_operating_unit['operating_unit_abbreviation'];
        $data['type'] = $fetch_operating_unit['type'];
        $data['parent_operating_unit_id'] = $fetch_operating_unit['parent_operating_unit_id']; 
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