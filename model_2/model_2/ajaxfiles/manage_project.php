<?php
include("../config/functions.inc.php");
$employee_id = $_SESSION['user'];


function insertProjectArchiveData($project_id)
{
	global $mysqli;
	if($project_id!='')
	{
		$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
		$project_arr = requestByCURL($url);
		//echo "<pre>";
		//print_r($project_arr); 
		$insert_archive_project = "insert into usaid_archive_project set
			 project_id='".$project_arr['data']['project_id']."',
			 design_record_create_date='".dateFormat($project_arr['data']['design_record_create_date'])."',
			 title='".$project_arr['data']['title']."', 
			 statement_problem='".$project_arr['data']['statement_problem']."',
			 project_purpose='".$project_arr['data']['project_purpose']."',
			 estimated_start_date='".dateFormat($project_arr['data']['estimated_start_date'])."',
			 project_published='".$project_arr['data']['project_published']."',
			 planned_start_date='".dateFormat($project_arr['data']['planned_start_date'])."',
			 actual_start_date='".dateFormat($project_arr['data']['actual_start_date'])."',
			 planned_end_date='".dateFormat($project_arr['data']['planned_end_date'])."',
			 actual_end_date='".dateFormat($project_arr['data']['actual_end_date'])."',
			 next_review_date='".dateFormat($project_arr['data']['next_review_date'])."',
			 project_stage_id='".$project_arr['data']['project_stage_id']."',
			 environmental_threshold='".$project_arr['data']['environmental_threshold']."',
			 gender_threshold='".$project_arr['data']['gender_threshold']."',
			 team_marker='".$project_arr['data']['team_marker']."',
			 employee_id='".$project_arr['data']['employee_id']."',
			 operating_unit_id='".$project_arr['data']['operating_unit_id']."',
			 smart_sheet_hyperlink='".$project_arr['data']['smart_sheet_hyperlink']."',
			 added_on='".$project_arr['data']['added_on']."',
			 modified_on=now(),
			 modified_by='".$_SESSION['first_last_name']."'"; 
		$result_archive_project = $mysqli->query($insert_archive_project);
	}
}

##function for generate project id ==================
function updateProjectId($id)
{
	global $mysqli;
	$num_str = sprintf("%06d", $id);
	$update_val = "update usaid_project set project_id = '".$num_str."' where id = '".$id."'";
	$result_update = $mysqli->query($update_val);
	if($result_update)return true; else return false;
}

$data_msg = array(); 
###insert project ========
if(isset($_REQUEST['add_project']))
{
	$project_id = trim($_REQUEST['project_id']);
	$project_title = $mysqli->real_escape_string(trim($_REQUEST['title']));
	$project_purpose = $mysqli->real_escape_string(trim($_REQUEST['project_purpose']));
	$design_record_create_date = dateFormat(trim($_REQUEST['design_record_create_date']));
	$planned_start_date = dateFormat(trim($_REQUEST['planned_start_date']));
	$planned_end_date = dateFormat(trim($_REQUEST['planned_end_date']));
	$actual_start_date = dateFormat(trim($_REQUEST['actual_start_date']));
	$actual_end_date = '';
	$next_review_date = dateFormat(trim($_REQUEST['next_review_date']));
	$environmental_threshold = $mysqli->real_escape_string(trim($_REQUEST['environmental_threshold']));
	$gender_threshold = $mysqli->real_escape_string(trim($_REQUEST['gender_threshold']));
	$operating_unit_id = "operating_unit_id=NULL";
	$smart_sheet_hyperlink = $mysqli->real_escape_string(trim($_REQUEST['smart_sheet_hyperlink']));
	
	if($_REQUEST['project_operating_unit']!=""){
		$operating_unit_id = "operating_unit_id='".$_REQUEST['project_operating_unit']."'";
	}
	if($project_title=='')
	{
	 	$data_msg['msg_type'] ="Error";
		$data_msg['msg'] = "Please fill project title";
	}
	elseif($project_purpose=='')
	{
		$data_msg['msg_type'] ="Error";
		$data_msg['msg'] = "Please fill project description";
	}
	else{
		if($project_id=='')
		{
			
			 $insert_project = "insert into usaid_project set project_id = '".$project_id."', title='".$project_title."', project_purpose='".$project_purpose."',design_record_create_date='".$design_record_create_date."',planned_start_date='".$planned_start_date."',planned_end_date='".$planned_end_date."',actual_start_date='".$actual_start_date."',actual_end_date='".$actual_end_date."',next_review_date='".$next_review_date."',environmental_threshold='".$environmental_threshold."',gender_threshold='".$gender_threshold."',employee_id='".$employee_id."',".$operating_unit_id.", smart_sheet_hyperlink='".$smart_sheet_hyperlink."'";
			$result_project = $mysqli->query($insert_project);
			if($result_project)
			{
				$id = $mysqli->insert_id;
				updateProjectId($id);
				$data_msg['msg_type'] ="Success";
				$data_msg['mode'] ="Insert";
				$data_msg['msg'] = "Congratulation your project has added successfully";
			}
			else
			{
				$data_msg['msg_type'] ="Error";
				$data_msg['msg'] = "Error";
			}
		}
		else
		{
			insertProjectArchiveData($project_id); // call function for previous data insert
			$project_stage_id = trim($_REQUEST['project_stage_id']);
			$update_project = "update usaid_project set title='".$project_title."', project_purpose='".$project_purpose."',design_record_create_date='".$design_record_create_date."',planned_start_date='".$planned_start_date."',planned_end_date='".$planned_end_date."',actual_start_date='".$actual_start_date."',actual_end_date='".$actual_end_date."',next_review_date='".$next_review_date."',project_stage_id='".$project_stage_id."',environmental_threshold='".$environmental_threshold."',gender_threshold='".$gender_threshold."',".$operating_unit_id.", smart_sheet_hyperlink='".$smart_sheet_hyperlink."' where project_id='".$project_id."'";
			$result_project = $mysqli->query($update_project);
			if($result_project)
			{
				$data_msg['msg_type'] ="Success";
				$data_msg['mode'] ="Update";
				$data_msg['msg'] = "Congratulation your project has updated successfully";
			}
			else
			{
				$data_msg['msg_type'] ="Error";
				$data_msg['msg'] = "Some error has been found please try again";
			}
		}
	}
	
	echo json_encode($data_msg);
}
?>