<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *"); 
## request for get all employee details============
if(isset($_REQUEST['name']) and $_REQUEST['name']!=''){
    $data = array();
    $name = $mysqli->real_escape_string(trim($_REQUEST['name']));
    $whereVal = " where first_name like '%".$name."%' or second_name like '%".$name."%' or last_name like '%".$name."%'"; 
    $select_employee = "select * from usaid_employee ".$whereVal;
    $result_employee = $mysqli->query($select_employee);
    $i=0; 
    while($fetch_employee = $result_employee->fetch_array()){
         $data[$i]['employee_id'] = $fetch_employee['employee_id']; 
         $data[$i]['first_name'] = $fetch_employee['first_name'];
         $data[$i]['second_name'] = $fetch_employee['second_name']; 
         $data[$i]['last_name'] = $fetch_employee['last_name']; 
         $data[$i]['date_of_birth'] = dateFormat($fetch_employee['date_of_birth']); 
         $data[$i]['gender'] = $fetch_employee['gender']; 
         $data[$i]['picture'] = $fetch_employee['picture'];
         $data[$i]['USAID_email'] = $fetch_employee['USAID_email']; 
         $data[$i]['USAID_cell_phone_number'] = $fetch_employee['USAID_cell_phone_number']; 
         $data[$i]['USAID_desk_phone_number'] = $fetch_employee['USAID_desk_phone_number'];  
         $data[$i]['permanent_residence_address_country'] = $fetch_employee['permanent_residence_address_country']; 
         $data[$i]['permanent_residence_address_state'] = $fetch_employee['permanent_residence_address_state']; 
         $data[$i]['permanent_residence_address_city'] = $fetch_employee['permanent_residence_address_city']; 
         $data[$i]['permanent_residence_street_address'] = $fetch_employee['permanent_residence_street_address']; 
         $data[$i]['emergency_contact_name'] = $fetch_employee['emergency_contact_name'];
         $data[$i]['emergency_contact_phone_number'] = $fetch_employee['emergency_contact_phone_number'];
         $data[$i]['emergency_contact_email'] = $fetch_employee['emergency_contact_email'];
         $data[$i]['added_on'] = $fetch_employee['added_on']; 
         $i++; 
    }
    if(count($data)>0){
        deliverResponse(200,'Record Found',$data);
    }
    else{
       deliverResponse(200,'No Record Found',NULL);
    }
}else{
   deliverResponse(200,'Invalid Request',NULL); 
}  
 
###function for deliver reponse on request===================
function deliverResponse($status,$status_msg,$data){
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}

?>