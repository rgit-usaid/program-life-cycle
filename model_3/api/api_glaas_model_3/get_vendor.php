<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get vendor details============
if(isset($_REQUEST['vendor_id']) and $_REQUEST['vendor_id']!='')
{
    $data = array();
    $vendor_id =  trim($_REQUEST['vendor_id']);
    $select_data = "select * from usaid_vendor where id = '".$vendor_id."'";
    $result_data = $mysqli->query($select_data);
    $fetch_data = $result_data->fetch_array();
    if($fetch_data['id']!='')
    {
        $data['vendor_id'] = $fetch_data['id'];  
        $data['DUNS_number'] = $fetch_data['DUNS_number'];
        $data['name'] = $fetch_data['name']; 
        $data['address_street'] = $fetch_data['address_street'];
        $data['address_city'] = $fetch_data['address_city'];
        $data['address_state_province'] = $fetch_data['address_state_province'];
        $data['address_country'] = $fetch_data['address_country']; 
        $data['address_location_code'] = $fetch_data['address_location_code'];
        $data['contact_name'] = $fetch_data['contact_name'];
        $data['email_address'] = $fetch_data['email_address'];
        $data['phone_number'] = $fetch_data['phone_number'];
        $data['direct_deposit_number'] = $fetch_data['direct_deposit_number'];
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