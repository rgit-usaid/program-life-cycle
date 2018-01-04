<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get vendor details============
if(isset($_REQUEST['archive_clin_id']) and $_REQUEST['archive_clin_id']!='')
{
    $data = array();
    $archive_clin_id =  trim($_REQUEST['archive_clin_id']);
    $select_data = "select * from usaid_archive_requisition_award_clin where requisition_award_clin_id = '".$archive_clin_id."' order by archive_on desc";
    $result_data = $mysqli->query($select_data);
	$i=0; 
	while($fetch_data = $result_data->fetch_array())
    {
		$data[$i]['id'] = $fetch_data['id'];
        $data[$i]['requisition_award_clin_id'] = $fetch_data['requisition_award_clin_id'];  
        $data[$i]['requisition_number'] = $fetch_data['requisition_number'];
        $data[$i]['clin_number'] = $fetch_data['clin_number'];
        $data[$i]['clin_name'] = $fetch_data['clin_name'];
        $data[$i]['clin_description'] = $fetch_data['clin_description'];
        $data[$i]['clin_amount'] = $fetch_data['clin_amount'];
        $data[$i]['start_performance_period'] = dateFormat($fetch_data['start_performance_period']);
        $data[$i]['end_performance_period'] = dateFormat($fetch_data['end_performance_period']);  
        $data[$i]['operating_unit_id'] = $fetch_data['operating_unit_id'];
        $data[$i]['employee_id'] = $fetch_data['employee_id'];
        $data[$i]['modification_number'] = $fetch_data['modification_number'];
        $data[$i]['modification_purpose'] = $fetch_data['modification_purpose'];
        $data[$i]['do_not_share'] = $fetch_data['do_not_share'];
		$data[$i]['award_number'] = $fetch_data['award_number'];
		$data[$i]['level'] = $fetch_data['level'];
		$data[$i]['status'] = $fetch_data['status'];
		$data[$i]['archive_on'] = $fetch_data['archive_on'];
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