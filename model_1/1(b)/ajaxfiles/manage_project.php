<?php
include("../config/config.inc.php");

##function for get date format to insert into db y-m-d ========
function dateFormat($date)
{
	$date = date('y-m-d',strtotime($date));
	return $date;
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
			
			$insert_project = "insert into usaid_project set project_id = '".$project_id."', title='".$project_title."', project_purpose='".$project_purpose."',design_record_create_date='".$design_record_create_date."',planned_start_date='".$planned_start_date."',planned_end_date='".$planned_end_date."',actual_start_date='".$actual_start_date."',actual_end_date='".$actual_end_date."',next_review_date='".$next_review_date."'";
			$result_project = $mysqli->query($insert_project);
			if($result_project)
			{
				$id = $mysqli->insert_id;
				updateProjectId($id);
				$data_msg['msg_type'] ="Success";
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
			$project_stage_id = trim($_REQUEST['project_stage_id']);
			$update_project = "update usaid_project set title='".$project_title."', project_purpose='".$project_purpose."',design_record_create_date='".$design_record_create_date."',planned_start_date='".$planned_start_date."',planned_end_date='".$planned_end_date."',actual_start_date='".$actual_start_date."',actual_end_date='".$actual_end_date."',next_review_date='".$next_review_date."',project_stage_id='".$project_stage_id."' where project_id='".$project_id."'";
			$result_project = $mysqli->query($update_project);
			if($result_project)
			{
				$data_msg['msg_type'] ="Success";
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