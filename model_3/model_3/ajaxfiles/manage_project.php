<?php
include("../config/functions.inc.php");
$employee_id = $_SESSION['user'];
##function for generate project id ==================
function updateProjectId($id)
{
	global $mysqli;
	$num_str = sprintf("%06d", $id);
	$update_val = "update usaid_project set project_id = '".$num_str."' where id = '".$id."'";
	$result_update = $mysqli->query($update_val);
	if($result_update)return true; else return false;
}

### function for project archive to both case==========
function insertProjectArchive($project_id,$archive_type)
{
	global $mysqli;
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);
	### these variable used to project design plan=================   
    $title = $mysqli->real_escape_string($project_arr['data']['title']);
    $project_purpose = $mysqli->real_escape_string($project_arr['data']['project_purpose']); 
   	$project_stage_id = $project_arr['data']['project_stage_id'];
    $employee_id =  $project_arr['data']['employee_id'];
    $originating_operating_unit_id =  $project_arr['data']['originating_operating_unit_id'];
    $implementing_operating_unit_id =  $project_arr['data']['implementing_operating_unit_id'];
    $estimated_total_funding_amount =  $project_arr['data']['estimated_total_funding_amount'];
    $engaging_local_actor_plan = $mysqli->real_escape_string($project_arr['data']['engaging_local_actor_plan']);
    $conducting_analyses_plan = $mysqli->real_escape_string($project_arr['data']['conducting_analyses_plan']);
    $use_of_govt_to_govt_plan = $mysqli->real_escape_string($project_arr['data']['use_of_govt_to_govt_plan']);
    $proposed_design_schedule = $mysqli->real_escape_string($project_arr['data']['proposed_design_schedule']);
    $proposed_design_cost =  $project_arr['data']['proposed_design_cost'];

    ##these variable used to project appraisal document ==========
   
   	$project_description = $mysqli->real_escape_string($project_arr['data']['project_description']);
    $context = $mysqli->real_escape_string($project_arr['data']['context']);
    $leveraged_resources = $mysqli->real_escape_string($project_arr['data']['leveraged_resources']);
    $conclusions_and_analyses_summary = $mysqli->real_escape_string($project_arr['data']['conclusions_and_analyses_summary']);
    $management_plan = $mysqli->real_escape_string($project_arr['data']['management_plan']);
    $financial_plan = $mysqli->real_escape_string($project_arr['data']['financial_plan']);
    $monitoring_evaluation_and_learning_plan = $mysqli->real_escape_string($project_arr['data']['monitoring_evaluation_and_learning_plan']);
    $activity_plan = $mysqli->real_escape_string($project_arr['data']['activity_plan']);
    $logical_framework_discretion = $mysqli->real_escape_string($project_arr['data']['logical_framework_discretion']);
    $planned_start_date = dateFormat($project_arr['data']['planned_start_date']);
    $planned_end_date = dateFormat($project_arr['data']['planned_end_date']);
    $next_review_date = dateFormat($project_arr['data']['next_review_date']);

    if($archive_type=='Project Design Plan')
    {
    	$insert_project = "insert into usaid_archive_project set project_id='".$project_id."', title='".$title."',project_stage_id='".$project_stage_id."',project_purpose='".$project_purpose."',estimated_total_funding_amount='".$estimated_total_funding_amount."',originating_operating_unit_id='".$originating_operating_unit_id."', implementing_operating_unit_id='".$implementing_operating_unit_id."',engaging_local_actor_plan='".$engaging_local_actor_plan."', conducting_analyses_plan='".$conducting_analyses_plan."',use_of_govt_to_govt_plan='".$use_of_govt_to_govt_plan."',proposed_design_schedule='".$proposed_design_schedule."',proposed_design_cost='".$proposed_design_cost."',employee_id='".$employee_id."', project_archive_type='Project Design Plan', archive_by='".$_SESSION['first_last_name']."'";
    	$result_project = $mysqli->query($insert_project);
    }
    if($archive_type=='Project Appraisal Document')
    { 
    	$insert_project = "insert into usaid_archive_project set project_id='".$project_id."', title='".$title."',project_stage_id='".$project_stage_id."',project_purpose='".$project_purpose."',project_description='".$project_description."',context='".$context."',leveraged_resources='".$leveraged_resources."', conclusions_and_analyses_summary='".$conclusions_and_analyses_summary."',management_plan='".$management_plan."', financial_plan='".$financial_plan."',monitoring_evaluation_and_learning_plan='".$monitoring_evaluation_and_learning_plan."',logical_framework_discretion='".$logical_framework_discretion."',activity_plan='".$activity_plan."',planned_start_date='".$planned_start_date."',planned_end_date='".$planned_end_date."',next_review_date='".$next_review_date."', project_archive_type='Project Appraisal Document' ,employee_id='".$employee_id."', archive_by='".$_SESSION['first_last_name']."'";
    	$result_project = $mysqli->query($insert_project);
    }
}


$data_msg = array(); 
###insert project ========
if(isset($_REQUEST['add_project']))
{
	$project_id = trim($_REQUEST['project_id']);
	$project_title = $mysqli->real_escape_string(ucwords(trim($_REQUEST['title'])));
	$project_purpose = $mysqli->real_escape_string(ucwords(trim($_REQUEST['project_purpose'])));
	
	$estimated_total_funding_amount = "estimated_total_funding_amount=NULL";
	if($_REQUEST['estimated_total_funding_amount']!=""){
		$temp = trim($_REQUEST['estimated_total_funding_amount']);
		$temp = str_replace('$','',$temp);
		$temp = str_replace(',','',$temp);
		$estimated_total_funding_amount = "estimated_total_funding_amount='".$temp."'";
	}
	$originating_operating_unit_id = "originating_operating_unit_id=NULL";
	if($_REQUEST['originating_operating_unit_id']!=""){
		$originating_operating_unit_id = "originating_operating_unit_id='".trim($_REQUEST['originating_operating_unit_id'])."'";
	}
	$engaging_local_actor_plan = "engaging_local_actor_plan=NULL";
	if($_REQUEST['engaging_local_actor_plan']!=""){
		$engaging_local_actor_plan = "engaging_local_actor_plan='".trim($_REQUEST['engaging_local_actor_plan'])."'";
	}
	$implementing_operating_unit_id = "implementing_operating_unit_id=NULL";
	if($_REQUEST['implementing_operating_unit_id']!=""){
		$implementing_operating_unit_id = "implementing_operating_unit_id='".trim($_REQUEST['implementing_operating_unit_id'])."'";
	}
	$use_of_govt_to_govt_plan = "use_of_govt_to_govt_plan=NULL";
	if($_REQUEST['use_of_govt_to_govt_plan']!=""){
		$use_of_govt_to_govt_plan = "use_of_govt_to_govt_plan='".$mysqli->real_escape_string(trim($_REQUEST['use_of_govt_to_govt_plan']))."'";
	}
	$conducting_analyses_plan = "conducting_analyses_plan=NULL";
	if($_REQUEST['conducting_analyses_plan']!=""){
		$conducting_analyses_plan = "conducting_analyses_plan='".$mysqli->real_escape_string(trim($_REQUEST['conducting_analyses_plan']))."'";
	}
	$proposed_design_schedule = "proposed_design_schedule=NULL";
	if($_REQUEST['proposed_design_schedule']!=""){
		$proposed_design_schedule = "proposed_design_schedule='".$mysqli->real_escape_string(trim($_REQUEST['proposed_design_schedule']))."'";
	}
	$proposed_design_cost = "proposed_design_cost=NULL";
	if($_REQUEST['proposed_design_cost']!=""){
		$temp = trim($_REQUEST['proposed_design_cost']);
		$temp = str_replace('$','',$temp);
		$temp = str_replace(',','',$temp);
		$proposed_design_cost = "proposed_design_cost='".$temp."'";
	}

	if($project_title=='')
	{
	 	$data_msg['msg_type'] ="Error";
		$data_msg['msg'] = "Please fill project title.";
	}
	elseif($project_purpose=='')
	{
		$data_msg['msg_type'] ="Error";
		$data_msg['msg'] = "Please fill project purpose.";
	}
	else{
		if($project_id=='')
		{
			$insert_project = "insert into usaid_project set project_id = '".$project_id."', title='".$project_title."',project_purpose='".$project_purpose."',".$estimated_total_funding_amount.",".$originating_operating_unit_id.",".$implementing_operating_unit_id.",".$use_of_govt_to_govt_plan.",".$conducting_analyses_plan.",".$proposed_design_schedule.",".$proposed_design_cost.",".$engaging_local_actor_plan.", employee_id='".$employee_id."'";
			$result_project = $mysqli->query($insert_project);
			if($result_project)
			{
				$id = $mysqli->insert_id;
				updateProjectId($id);
				$data_msg['msg_type'] ="Success";
				$data_msg['mode'] ="Insert";
				$data_msg['msg'] = "Congratulation your project has added successfully.";
			}
			else
			{
				$data_msg['msg_type'] ="Error";
				$data_msg['msg'] = "Error";
			}
		}
		else
		{
			insertProjectArchive($project_id,'Project Design Plan'); // function used to get archive 
			$project_stage_id = trim($_REQUEST['project_stage_id']);
			$update_project = "update usaid_project set title='".$project_title."',project_purpose='".$project_purpose."',".$estimated_total_funding_amount.",".$originating_operating_unit_id.",".$implementing_operating_unit_id.",".$use_of_govt_to_govt_plan.",".$conducting_analyses_plan.",".$proposed_design_schedule.",".$proposed_design_cost.",".$engaging_local_actor_plan.", project_stage_id='".$project_stage_id."' where project_id='".$project_id."'";
			$result_project = $mysqli->query($update_project);
			if($result_project)
			{
				$data_msg['msg_type'] ="Success";
				$data_msg['mode'] ="Update";
				$data_msg['msg'] = "Congratulation your project has updated successfully.";
			}
			else
			{
				$data_msg['msg_type'] ="Error";
				$data_msg['msg'] = "Some error has been found please try again.";
			}
		}
	}
	
	echo json_encode($data_msg);
}

###update project appraisal document========
if(isset($_REQUEST['manage_project_appraisal_doc'])){
	$project_id = trim($_REQUEST['project_id']);
	$project_title = $mysqli->real_escape_string(ucwords(trim($_REQUEST['title'])));
	$project_purpose = $mysqli->real_escape_string(ucwords(trim($_REQUEST['project_purpose'])));
	$project_description = "project_description=NULL";
	$project_stage_id = trim($_REQUEST['project_stage_id']);
	if($_REQUEST['project_description']!=""){
		$project_description = "project_description='".trim($_REQUEST['project_description'])."'";
	}
	
	$context = "context=NULL";
	if($_REQUEST['context']!=""){
		$context = "context='".trim($_REQUEST['context'])."'";
	}
	
	$leveraged_resources = "leveraged_resources=NULL";
	if($_REQUEST['leveraged_resources']!=""){
		$leveraged_resources = "leveraged_resources='".trim($_REQUEST['leveraged_resources'])."'";
	}
	
	$conclusions_and_analyses_summary = "conclusions_and_analyses_summary=NULL";
	if($_REQUEST['conclusions_and_analyses_summary']!=""){
		$conclusions_and_analyses_summary = "conclusions_and_analyses_summary='".trim($_REQUEST['conclusions_and_analyses_summary'])."'";
	}

	$management_plan = "management_plan=NULL";
	if($_REQUEST['management_plan']!=""){
		$management_plan = "management_plan='".trim($_REQUEST['management_plan'])."'";
	}
	
	$financial_plan = "financial_plan=NULL";
	if($_REQUEST['financial_plan']!=""){
		$financial_plan = "financial_plan='".trim($_REQUEST['financial_plan'])."'";
	}

	$monitoring_evaluation_and_learning_plan = "monitoring_evaluation_and_learning_plan=NULL";
	if($_REQUEST['monitoring_evaluation_and_learning_plan']!=""){
		$monitoring_evaluation_and_learning_plan = "monitoring_evaluation_and_learning_plan='".trim($_REQUEST['monitoring_evaluation_and_learning_plan'])."'";
	}
	
	$logical_framework_discretion = "logical_framework_discretion=NULL";
	if($_REQUEST['logical_framework_discretion']!=""){
		$logical_framework_discretion = "logical_framework_discretion='".trim($_REQUEST['logical_framework_discretion'])."'";
	}
	
	$activity_plan = "activity_plan=NULL";
	if($_REQUEST['activity_plan']!=""){
		$activity_plan = "activity_plan='".trim($_REQUEST['activity_plan'])."'";
	}
	
	$planned_start_date = "planned_start_date=NULL";
	if($_REQUEST['planned_start_date']!=""){
		$planned_start_date = "planned_start_date='".dateFormat(trim($_REQUEST['planned_start_date']))."'";
	}
	
	$planned_end_date = "planned_end_date=NULL";
	if($_REQUEST['planned_end_date']!=""){
		$planned_end_date = "planned_end_date='".dateFormat(trim($_REQUEST['planned_end_date']))."'";
	}
	
	$next_review_date = "next_review_date=NULL";
	if($_REQUEST['next_review_date']!=""){
		$next_review_date = "next_review_date='".dateFormat(trim($_REQUEST['next_review_date']))."'";
	}
	insertProjectArchive($project_id,'Project Appraisal Document'); // function used to get archive 
	$update_project = "update usaid_project set title='".$project_title."',project_purpose='".$project_purpose."',".$project_description.",".$context.",".$leveraged_resources.",".$conclusions_and_analyses_summary.",".$management_plan.",".$financial_plan.",".$monitoring_evaluation_and_learning_plan.",".$logical_framework_discretion.",".$activity_plan.",".$planned_start_date.",".$planned_end_date.",".$next_review_date.",project_stage_id='".$project_stage_id."' where project_id='".$project_id."'";
	
	$result_project = $mysqli->query($update_project);
	if($result_project)
	{
		$data_msg['msg_type'] ="Success";
		$data_msg['mode'] ="Update";
		$data_msg['msg'] = "Congratulation your project has updated successfully.";
	}
	else
	{
		$data_msg['msg_type'] ="Error";
		$data_msg['msg'] = "Some error has been found please try again.";
	}
	echo json_encode($data_msg);
}
?>