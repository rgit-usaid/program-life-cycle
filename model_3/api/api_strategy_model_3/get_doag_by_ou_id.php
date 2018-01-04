<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## Request for get all DOAG of a Operating Unit ============
if(isset($_REQUEST['ou_id']))
{ 
    $data = array();
    $ou_id = trim($_REQUEST['ou_id']);
    $select_data = "select * from usaid_objective_agreement where operating_unit_id='".$ou_id."'";
    $result_data = $mysqli->query($select_data);
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
         $data[$i]['id'] = $fetch_data['id'];  
         $data[$i]['operating_unit_id'] = $fetch_data['operating_unit_id']; 
         $data[$i]['objective_agreement_type'] = $fetch_data['objective_agreement_type'];
         $data[$i]['name'] = $fetch_data['name'];
         $data[$i]['description'] = $fetch_data['description'];        
         $data[$i]['funding_estimate'] = $fetch_data['funding_estimate'];        
         $data[$i]['status'] = $fetch_data['status'];        
         $data[$i]['approved_date'] = $fetch_data['approved_date'];
         $i++; 
    }
    if(count($data)>0)
    {
        deliverResponse(200,'Record Found',$data);
    }
    else{
       deliverResponse(200,'No Record Found',NULL);
    } 
}  
###function for deliver reponse on request===================
function deliverResponse($status,$status_msg,$data)
{
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;
    $json_response = json_encode($response);
    echo $json_response;
}?>