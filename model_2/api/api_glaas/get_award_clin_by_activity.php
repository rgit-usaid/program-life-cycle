<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## get all award clin of a Activity ============

if(isset($_REQUEST['activity_id']) and $_REQUEST['activity_id']!='')
{   
    $data = array();
    $activity_id = trim($_REQUEST['activity_id']);
					
	$select_data = "select rac.id as clin_id, ra.vendor_id, rac.award_number, rac.clin_number, rac.clin_name, rac.clin_description, rac.clin_amount as amount, rcb.*, 
					v.id as vendorid, v.DUNS_number, v.name 
					from usaid_requisition_award_clin as rac 
					left join usaid_association_link as al ON al.associate_from_number = rac.clin_number 
					left join usaid_requisition_clin_budget as rcb ON rcb.budget_number = rac.clin_number 
					left join usaid_requisition_award as ra ON ra.award_number = rac.award_number 
					left join usaid_vendor as v ON v.id = ra.vendor_id 
					where al.link_to_id='".$activity_id."' and al.link_to_type='Project Activity' and al.associate_from_type='Clin' group by rcb.budget_number";
    				$result_data = $mysqli->query($select_data);
				
	
    $i=0; 
    while($fetch_data = $result_data->fetch_array())
    {
	
		 ## get all usid_requisition_award_clin by  Activity ============
         $data[$i]['clin_id'] = $fetch_data['clin_id'];  
         $data[$i]['award_number'] = $fetch_data['award_number'];
         $data[$i]['clin_number'] = $fetch_data['clin_number'];
         $data[$i]['clin_name'] = $fetch_data['clin_name'];
         $data[$i]['clin_description'] = $fetch_data['clin_description'];
         $data[$i]['amount'] = $fetch_data['amount']; 
		 
		## get all usid_requisition_clin_budget  by Activity  ============
		$data[$i]['budget_number'] = $fetch_data['budget_number']; 
		$data[$i]['code_description'] = $fetch_data['code_description']; 
		$data[$i]['budget_type'] = $fetch_data['budget_type']; 
		$data[$i]['status'] = $fetch_data['status']; 
		$data[$i]['added_on'] = $fetch_data['added_on'];
		
		## get all usid_vendor by project ============
		$data[$i]['vendor_id'] = $fetch_data['vendorid']; 
		$data[$i]['DUNS_number'] = $fetch_data['DUNS_number']; 
		$data[$i]['name'] = $fetch_data['name'];  
		
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
