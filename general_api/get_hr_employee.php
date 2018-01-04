<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *"); 
## request for get all employee details============
if(isset($_REQUEST['employee_id']) and $_REQUEST['employee_id']!=''){
    $data = array();
    $employee_id = $mysqli->real_escape_string(trim($_REQUEST['employee_id'])); 
    $select_employee = "select e.*,r.role as USAID_role 
                            from usaid_employee as e 
                            left join usaid_role as r ON r.id = e.USAID_role_id 
                            where e.employee_id='".$employee_id."'";
    $result_employee = $mysqli->query($select_employee);
    $fetch_employee = $result_employee->fetch_array();
    if($fetch_employee['employee_id']!=''){
         $data['employee_id'] = $fetch_employee['employee_id']; 
         $data['first_name'] = $fetch_employee['first_name'];
         $data['second_name'] = $fetch_employee['second_name']; 
         $data['last_name'] = $fetch_employee['last_name']; 
         $data['date_of_birth'] = dateFormat($fetch_employee['date_of_birth']); 
         $data['gender'] = $fetch_employee['gender']; 
         $data['picture'] = $fetch_employee['picture'];
         $data['USAID_email'] = $fetch_employee['USAID_email']; 
         $data['USAID_cell_phone_number'] = $fetch_employee['USAID_cell_phone_number']; 
         $data['USAID_desk_phone_number'] = $fetch_employee['USAID_desk_phone_number'];  
         $data['permanent_residence_address_country'] = $fetch_employee['permanent_residence_address_country']; 
         $data['permanent_residence_address_state'] = $fetch_employee['permanent_residence_address_state']; 
         $data['permanent_residence_address_city'] = $fetch_employee['permanent_residence_address_city']; 
         $data['permanent_residence_street_address'] = $fetch_employee['permanent_residence_street_address']; 
         $data['emergency_contact_name'] = $fetch_employee['emergency_contact_name'];
         $data['emergency_contact_phone_number'] = $fetch_employee['emergency_contact_phone_number'];
         $data['emergency_contact_email'] = $fetch_employee['emergency_contact_email'];
         $data['type_direct_hire'] = $fetch_employee['type_direct_hire'];
         $data['type_non_direct_hire'] = $fetch_employee['type_non_direct_hire'];
         $data['USAID_role_id'] = $fetch_employee['USAID_role_id'];
         $data['USAID_role'] = $fetch_employee['USAID_role'];
         $data['foreign_service_employee_grade'] = $fetch_employee['foreign_service_employee_grade'];
         $data['foreign_service_employee_step'] = $fetch_employee['foreign_service_employee_step'];
         $data['general_service_employee_grade'] = $fetch_employee['general_service_employee_grade'];
         $data['general_service_employee_step'] = $fetch_employee['general_service_employee_step'];
         $data['USAID_position_title'] = $fetch_employee['USAID_position_title'];
         $data['qualified_COR_AOR'] = $fetch_employee['qualified_COR_AOR'];
         $data['COR_AOR_certification_expiration_date'] = dateFormat($fetch_employee['COR_AOR_certification_expiration_date']);
         $data['qualified_project_manager'] = $fetch_employee['qualified_project_manager'];
         $data['project_manager_certification_expiration_date'] = dateFormat($fetch_employee['project_manager_certification_expiration_date']);
         $data['USAID_supervisor_employee_id'] = $fetch_employee['USAID_supervisor_employee_id'];
         $data['USAID_operating_unit_id_assi'] = $fetch_employee['USAID_operating_unit_id_assi'];
         $data['date_assi_operating_unit'] = dateFormat($fetch_employee['date_assi_operating_unit']);
         $data['assi_work_location_office_name'] = $fetch_employee['assi_work_location_office_name'];
         $data['assi_work_location_country'] = $fetch_employee['assi_work_location_country'];
         $data['assi_work_location_city'] = $fetch_employee['assi_work_location_city'];
         $data['assi_work_location_street_address'] = $fetch_employee['assi_work_location_street_address'];
         $data['assi_work_location_postal_location_code'] = $fetch_employee['assi_work_location_postal_location_code'];
         $data['added_on'] = $fetch_employee['added_on']; 
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