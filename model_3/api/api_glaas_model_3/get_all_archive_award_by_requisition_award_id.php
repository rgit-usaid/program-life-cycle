<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## get all archive award by requistion ============
if(isset($_REQUEST['requisition_award_id']) and $_REQUEST['requisition_award_id']!='')
{   
    $data = array();
    $requisition_award_id = trim($_REQUEST['requisition_award_id']);
    $select_data = "select ara.*, v.name from usaid_archive_requisition_award as ara
	left join usaid_vendor as v ON v.id = ara.vendor_id
	where requisition_award_id='".$requisition_award_id."' order by archive_on desc";
    $result_data = $mysqli->query($select_data);
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
         $data[$i]['archive_award_id'] = $fetch_data['id']; 
		 $data[$i]['requisition_award_id'] = $fetch_data['requisition_award_id'];  
         $data[$i]['requisition_number'] = $fetch_data['requisition_number'];
         $data[$i]['award_number'] = $fetch_data['award_number'];
         $data[$i]['award_name'] = $fetch_data['award_name'];
         $data[$i]['award_description'] = $fetch_data['award_description'];
		 $data[$i]['amount'] = $fetch_data['amount'];
         $data[$i]['award_date'] = dateFormat($fetch_data['award_date']); 
         $data[$i]['start_performance_period'] = dateFormat($fetch_data['start_performance_period']);
         $data[$i]['end_performance_period'] = dateFormat($fetch_data['end_performance_period']);  
         $data[$i]['do_not_share'] = $fetch_data['do_not_share'];
		 $data[$i]['implementing_mechanism_type'] = $fetch_data['implementing_mechanism_type'];
		 $data[$i]['operating_unit_id'] = $fetch_data['operating_unit_id'];
		 $data[$i]['vendor_id'] = $fetch_data['vendor_id'];
		 $data[$i]['employee_id'] = $fetch_data['employee_id'];
         $data[$i]['added_on'] = dateFormat($fetch_data['added_on']);
		 $data[$i]['archive_on'] = $fetch_data['archive_on'];
		 $data[$i]['vendor_name'] = $fetch_data['name'];
		 
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