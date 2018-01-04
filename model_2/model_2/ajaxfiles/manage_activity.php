<?php
include("../config/functions.inc.php");
$employee_id = $_SESSION['user'];

## insert date Activity archive data from activity table============
function insertActivityArchiveData($project_id, $activity_id)
{
	global $mysqli;
	if($project_id && $activity_id!='')
	{
	$url = API_HOST_URL_PROJECT."get_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id;  
    $archive_project_activity_arr = requestByCURL($url);
	
		$insert_archive_activity = "insert into usaid_archive_project_activity set 
			activity_id = '".$archive_project_activity_arr['data']['activity_id']."',
			project_id = '".$archive_project_activity_arr['data']['project_id']."', 
			title='".$archive_project_activity_arr['data']['title']."', 
			activity_description='".$archive_project_activity_arr['data']['activity_description']."',
			activity_benefitting_country='".$archive_project_activity_arr['data']['activity_benefitting_country']."',
			activity_published='".$archive_project_activity_arr['data']['activity_published']."',
			planned_start_date='".dateFormat($archive_project_activity_arr['data']['planned_start_date'])."',
			actual_start_date='".dateFormat($archive_project_activity_arr['data']['actual_start_date'])."',
			planned_end_date='".dateFormat($archive_project_activity_arr['data']['planned_end_date'])."',			
			actual_end_date='".dateFormat($archive_project_activity_arr['data']['actual_end_date'])."',
			employee_id='".$archive_project_activity_arr['data']['employee_id']."',
			status='".$archive_project_activity_arr['data']['status']."',
			added_on='".dateFormat($archive_project_activity_arr['data']['added_on'])."',
			modified_on=now(),
			modified_by='".$_SESSION['first_last_name']."'";
		$result_archive_activity = $mysqli->query($insert_archive_activity); 
	}
}
##function for generate project id ==================
function updateActivityId($id,$project_id)
{
	global $mysqli;
	$sel_proj_activity = "SELECT activity_id FROM usaid_project_activity WHERE project_id ='".$project_id."'";
	$exe_proj_activity = $mysqli->query($sel_proj_activity);
	
	$exe_proj_activity->num_rows;
	$temp_id = $exe_proj_activity->num_rows;
	$num_str = $project_id.'-'.sprintf("%03d", $temp_id);
	
	$update_val = "update usaid_project_activity set activity_id = '".$num_str."' where id = '".$id."'";
	$result_update = $mysqli->query($update_val);
	if($result_update)return true; else return false;
}

$data_msg = array(); 
###insert project ========
if(isset($_REQUEST['add_activity']))
{
	$activity_id = trim($_REQUEST['activity_id']);
	$project_id = trim($_REQUEST['project_id']);
	$activity_title = $mysqli->real_escape_string(trim($_REQUEST['title']));
	$activity_benefitting_country = $mysqli->real_escape_string(trim($_REQUEST['activity_benefitting_country']));
	$activity_description = $mysqli->real_escape_string(trim($_REQUEST['activity_description']));
	$planned_start_date = dateFormat(trim($_REQUEST['planned_start_date']));
	$planned_end_date = dateFormat(trim($_REQUEST['planned_end_date']));
	$actual_start_date = dateFormat(trim($_REQUEST['actual_start_date']));
	$actual_end_date = dateFormat(trim($_REQUEST['actual_end_date']));
	
	if($activity_title=='')
	{
	 	$data_msg['msg_type'] ="Error";
		$data_msg['msg'] = "Please fill activity title";
	}
	elseif($activity_description=='')
	{
		$data_msg['msg_type'] ="Error";
		$data_msg['msg'] = "Please fill activity description";
	}
	else{
		if($activity_id=='')
		{
			
			$insert_activity = "insert into usaid_project_activity set activity_id = '".$activity_id."', project_id = '".$project_id."', title='".$activity_title."', activity_description='".$activity_description."',activity_benefitting_country ='".$activity_benefitting_country."',planned_start_date='".$planned_start_date."',planned_end_date='".$planned_end_date."',actual_start_date='".$actual_start_date."',actual_end_date='".$actual_end_date."',employee_id='".$employee_id."'";
			$result_activity = $mysqli->query($insert_activity);
			if($result_activity)
			{
				$id = $mysqli->insert_id;
				updateActivityId($id,$project_id);
				$data_msg['msg_type'] ="Success";
				$data_msg['mode'] ="Insert";
				$data_msg['msg'] = "Congratulation your project activity has added successfully";
			}
			else
			{
				$data_msg['msg_type'] ="Error";
				$data_msg['msg'] = "Error";
			}
		}
		else
		{
			insertActivityArchiveData($project_id, $activity_id); // call function for archive data insert
			
			$update_activity = "update usaid_project_activity set title='".$activity_title."', activity_description='".$activity_description."',activity_benefitting_country='".$activity_benefitting_country."',planned_start_date='".$planned_start_date."',planned_end_date='".$planned_end_date."',actual_start_date='".$actual_start_date."',actual_end_date='".$actual_end_date."' where project_id='".$project_id."' and activity_id = '".$activity_id."'";
			$result_activity = $mysqli->query($update_activity);
			if($result_activity)
			{
				$data_msg['msg_type'] ="Success";
				$data_msg['mode'] ="Update";
				$data_msg['msg'] = "Congratulation your project activity has updated successfully";
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