<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## get all award of a project ============

if(isset($_REQUEST['project_id']) and $_REQUEST['project_id']!='')
{   
    $data = array();
    $project_id = trim($_REQUEST['project_id']);
		 $select_data = "select ra.*, ra.id as award_id, ra.amount as amount, v.id as vendor_id, v.DUNS_number, v.name, rcb.*
					from usaid_requisition_award as ra 
					left join usaid_association_link as al ON al.associate_from_number = ra.award_number 
					left join usaid_requisition_clin_budget as rcb ON rcb.budget_number = ra.award_number 
					left join usaid_vendor as v ON v.id = ra.vendor_id 
					where al.link_to_id='".$project_id."' and al.associate_from_type='Award' and al.link_to_type='Project' group by rcb.budget_number";
    				$result_data = $mysqli->query($select_data);
	
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
	
		 ## get all usidrequisition_award by  project ============
         $data[$i]['award_id'] = $fetch_data['award_id'];  
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
		 
		## get all usid_vendor by project ============
		$data[$i]['vendor_id'] = $fetch_data['vendor_id']; 
		$data[$i]['DUNS_number'] = $fetch_data['DUNS_number']; 
		$data[$i]['name'] = $fetch_data['name']; 
		
		## get all usid_requisition_clin_budget by project  ============
		$data[$i]['budget_number'] = $fetch_data['budget_number']; 
		$data[$i]['code_description'] = $fetch_data['code_description']; 
		$data[$i]['budget_type'] = $fetch_data['budget_type']; 
		$data[$i]['status'] = $fetch_data['status']; 
		$data[$i]['added_on'] = $fetch_data['added_on']; 
		
		
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
