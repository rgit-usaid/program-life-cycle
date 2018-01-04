<?php
include("config/config.inc.php");
include("includes/function.inc.php");
header('Content-type: application/json');
header("access-control-allow-origin: *");
## request for get all archive project============
if(isset($_REQUEST['project_id']))
{
	$cond ='';
	if(isset($_REQUEST['archive_id']))
	{
		$cond = " and ap.id='".$_REQUEST['archive_id']."'";
	}
    $data = array();
    $project_id = $mysqli->real_escape_string(trim($_REQUEST['project_id']));
    $select_project = "select ap.*,ps.stage_name ,ps.stage_percentage
                        from usaid_archive_project as ap
                        left join usaid_project_stage as ps ON ps.stage_id = ap.project_stage_id where ap.project_id = '".$project_id."' ".$cond." ";
    $result_project = $mysqli->query($select_project);
	$i=0; 
    //$fetch_project = $result_project->fetch_array();
    while($fetch_project = $result_project->fetch_array())
    {
        $data[$i]['project_id'] = $fetch_project['project_id'];
        $data[$i]['title'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['title']));
		$data[$i]['project_purpose'] = preg_replace('/\\\\+/','',stripslashes($fetch_project['project_purpose']));
        $data[$i]['project_published'] = $fetch_project['project_published']; 
        $data[$i]['project_stage_id'] = $fetch_project['project_stage_id']; 
        $data[$i]['stage_name'] = $fetch_project['stage_name']; 
        $data[$i]['stage_percentage'] = $fetch_project['stage_percentage']; 
        $data[$i]['design_record_create_date'] = dateFormat($fetch_project['design_record_create_date']);
        $data[$i]['planned_start_date'] = dateFormat($fetch_project['planned_start_date']); 
        $data[$i]['planned_end_date'] = dateFormat($fetch_project['planned_end_date']); 
        $data[$i]['actual_start_date'] = dateFormat($fetch_project['actual_start_date']); 
        $data[$i]['actual_end_date'] = dateFormat($fetch_project['actual_end_date']); 
        $data[$i]['next_review_date'] =  dateFormat($fetch_project['next_review_date']);
        $data[$i]['environmental_threshold'] = $fetch_project['environmental_threshold'];          
        $data[$i]['gender_threshold'] = $fetch_project['gender_threshold'];
        $data[$i]['team_marker'] = $fetch_project['team_marker'];
        $data[$i]['employee_id'] = $fetch_project['employee_id'];
		$data[$i]['operating_unit_id'] = $fetch_project['operating_unit_id'];
		$data[$i]['smart_sheet_hyperlink'] = $fetch_project['smart_sheet_hyperlink'];
		$data[$i]['added_on'] = dateFormat($fetch_project['added_on']);  
		$data[$i]['modified_on'] = dateFormat($fetch_project['modified_on']);  
		$data[$i]['modified_by'] = $fetch_project['modified_by']; 
		$data[$i]['stage_name'] = $fetch_project['stage_name'];  
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
