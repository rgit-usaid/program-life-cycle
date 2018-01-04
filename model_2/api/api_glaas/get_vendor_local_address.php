<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all project============
if(isset($_REQUEST['vendor_id']) and $_REQUEST['vendor_id']!='')
{ 
    $data = array();
    $vendor_id =  trim($_REQUEST['vendor_id']);   
    $select_data = "select * from usaid_vendor_local_address where vendor_id='".$vendor_id."'";
    $result_data = $mysqli->query($select_data);
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
        $data[$i]['local_address_id'] = $fetch_data['id'];  
        $data[$i]['vendor_id'] = $fetch_data['vendor_id'];  
        $data[$i]['local_contact_name'] = $fetch_data['local_contact_name']; 
        $data[$i]['local_contact_email'] = $fetch_data['local_contact_email'];
        $data[$i]['local_phone_number'] = $fetch_data['local_phone_number']; 
        $data[$i]['local_address_street'] = $fetch_data['local_address_street'];
        $data[$i]['local_address_city'] = $fetch_data['local_address_city'];
        $data[$i]['local_address_state_province'] = $fetch_data['local_address_state_province'];
        $data[$i]['local_address_country'] = $fetch_data['local_address_country'];
        $data[$i]['local_address_location_code'] = $fetch_data['local_address_location_code'];
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
}?>