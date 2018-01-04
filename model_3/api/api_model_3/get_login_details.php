<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
 
## request for login details============
$data = array(); 
if(isset($_REQUEST['user_email'])){
	$user_email = '';
	if(strpos($_REQUEST['user_email'],'@')===false){
		$user_email = $_REQUEST['user_email'].'@usaid.gov';
	}
	else{
		$user_email = $_REQUEST['user_email'];
	}
	
	$select_employee = "select employee_id, USAID_email from rgdemode_amp.usaid_employee WHERE USAID_email ='".$user_email."'";
	$result_employee = $mysqli->query($select_employee);
	$fetch_employee = $result_employee->fetch_array();
	if($fetch_employee['USAID_email'] == $user_email){
		$data['employee_id'] = $fetch_employee['employee_id']; 
		$data['USAID_email'] = $fetch_employee['USAID_email']; 			
	
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
function deliverResponse($status,$status_msg,$data){
    $response['status'] = $status;
    $response['status_msg'] = $status_msg;
    $response['data'] = $data;

    $json_response = json_encode($response);
    echo $json_response;
}

?>