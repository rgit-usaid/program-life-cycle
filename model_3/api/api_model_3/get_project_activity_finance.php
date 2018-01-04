<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *"); 
## request for get all employee details============
$data = array(); 
if(isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!="" && isset($_REQUEST['type']) && $_REQUEST['type']!=""){
	$activity_id = $_REQUEST['activity_id'];
	$type = $_REQUEST['type'];
	
	$select_finance = "SELECT finance_year, finance_month, (finance_value) as total_amount FROM usaid_project_activity_finance WHERE activity_id='".$activity_id."' and finance_type='".$type."'";
	$result_finance = $mysqli->query($select_finance);
	$i=0; 
	while($fetch_finance = $result_finance->fetch_array())
	{
		 $data[$i]['finance_year'] = $fetch_finance['finance_year']; 
		 $data[$i]['finance_month'] = $fetch_finance['finance_month'];
		 $data[$i]['total_amount'] = $fetch_finance['total_amount']; 
		 $i++; 
	}
}
if(count($data)>0){
    deliverResponse(200,'Record Found',$data);
}
else{
   deliverResponse(200,'No Record Found',NULL);
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