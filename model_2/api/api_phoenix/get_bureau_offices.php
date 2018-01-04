<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['bureau_id']))
{  
    $data = array();  
    $bureau_id = trim($_REQUEST['bureau_id']);
    $bureau_arr = explode('-',$bureau_id);
    $cond = " where L1='".$bureau_arr[0]."' and L3='".$bureau_arr[1]."' and operating_unit_id !='".$bureau_id."' order by operating_unit_id";

   $select_operating_unit = "select * from usaid_operating_unit ".$cond;
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