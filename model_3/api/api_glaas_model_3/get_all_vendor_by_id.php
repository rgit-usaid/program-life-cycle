<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all vendor name============
if(isset($_REQUEST['vendor_id']) and $_REQUEST['vendor_id']!='')
{
$vendor_id = trim($_REQUEST['vendor_id']);
$data = array();
$select_data = "select * from usaid_vendor where id='".$vendor_id."' ";
$result_data = $mysqli->query($select_data);
$i=0; 
while($fetch_data = $result_data->fetch_array())
{
     $data[$i]['vendor_id'] = $fetch_data['id'];  
     $data[$i]['DUNS_number'] = $fetch_data['DUNS_number'];
     $data[$i]['name'] = $fetch_data['name']; 
     $data[$i]['address_street'] = $fetch_data['address_street'];
     $data[$i]['address_city'] = $fetch_data['address_city'];
     $data[$i]['address_state_province'] = $fetch_data['address_state_province'];
     $data[$i]['address_country'] = $fetch_data['address_country']; 
     $data[$i]['address_location_code'] = $fetch_data['address_location_code'];
     $data[$i]['contact_name'] = $fetch_data['contact_name'];
     $data[$i]['email_address'] = $fetch_data['email_address'];
     $data[$i]['phone_number'] = $fetch_data['phone_number'];
     $data[$i]['direct_deposit_number'] = $fetch_data['direct_deposit_number'];
     $i++; 
}
if(count($data)>0){
    deliverResponse(200,'Record Found',$data);
}
else
{
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