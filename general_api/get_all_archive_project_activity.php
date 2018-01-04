<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all archive project activity ============
if(isset($_REQUEST['project_id']) && isset($_REQUEST['activity_id']))
{
    $data = array();
	$activity_id = $mysqli->real_escape_string(trim($_REQUEST['activity_id']));
    $project_id = $mysqli->real_escape_string(trim($_REQUEST['project_id']));
    $select_archive_project_activity = "select pa.*
                        from usaid_archive_project_activity as pa where pa.project_id = '".$project_id."' and pa.activity_id = '".$activity_id."' ";
    $result_archive_project_activity = $mysqli->query($select_archive_project_activity);
	$i=0; 
     while($fetch_archive_project_activity = $result_archive_project_activity->fetch_array())
   	 {
        $data[$i]['activity_id'] = $fetch_archive_project_activity['activity_id']; 
		$data[$i]['project_id'] = $fetch_archive_project_activity['project_id']; 
        $data[$i]['title'] = preg_replace('/\\\\+/','',stripslashes($fetch_archive_project_activity['title']));
        $data[$i]['activity_description'] = preg_replace('/\\\\+/','',stripslashes($fetch_archive_project_activity['activity_description'])); 
		$data[$i]['activity_benefitting_country'] = $fetch_archive_project_activity['activity_benefitting_country']; 
		$data[$i]['activity_published'] = $fetch_archive_project_activity['activity_published']; 
        $data[$i]['planned_start_date'] = dateFormat($fetch_archive_project_activity['planned_start_date']); 
        $data[$i]['planned_end_date'] = dateFormat($fetch_archive_project_activity['planned_end_date']); 
        $data[$i]['actual_start_date'] = dateFormat($fetch_archive_project_activity['actual_start_date']); 
        $data[$i]['actual_end_date'] = dateFormat($fetch_archive_project_activity['actual_end_date']); 
        $data[$i]['employee_id'] = $fetch_archive_project_activity['employee_id'];
		$data[$i]['added_on'] = dateFormat($fetch_archive_project_activity['added_on']);   
		$data[$i]['status'] = $fetch_archive_project_activity['status'];   
		$data[$i]['modified_on'] = dateFormat($fetch_archive_project_activity['modified_on']);  
		$data[$i]['modified_by'] = $fetch_archive_project_activity['modified_by'];    
 		$i++;         
	}
		if(count($data)>0){
			deliverResponse(200,'Record Found',$data);
		}
		else{
		   deliverResponse(200,'No Record Found',NULL);
		}
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
