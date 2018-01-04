<?php include('config/functions.inc.php');
##==validate user====
validate_user();
unset($_SESSION['project_monitoring_id']);
## insert review/monitoring data in archive table  ====================
function insertArchiveMonitoringData($project_id,$review_id)
{
	global $mysqli;
	if($project_id && $review_id!='')
	{
		$url = API_HOST_URL_PROJECT."get_project_review.php?project_id=".$project_id."&review_id=".$review_id;  
   		$project_review_arr = requestByCURL($url);
		if(count($project_review_arr['data'])>0)
		{
			$insert_archive_project_monitoring = "insert into usaid_archive_project_monitoring set
				 project_monitoring_id='".$project_review_arr['data']['review_id']."',
				 project_id='".$project_review_arr['data']['project_id']."',
				 review_type='".$project_review_arr['data']['review_type']."',
				 review_due_date='".dateFormat($project_review_arr['data']['review_due_date'])."',
				 review_prompt_date='".dateFormat($project_review_arr['data']['review_prompt_date'])."',
				 actual_review_date='".dateFormat($project_review_arr['data']['actual_review_date'])."',
				 overall_score='".$project_review_arr['data']['overall_score']."',
				 annual_review_submission_comments='".$mysqli->real_escape_string($project_review_arr['data']['annual_review_submission_comments'])."',
				 annual_review_approval='".$project_review_arr['data']['annual_review_approval']."',
				 annual_review_approver='".$project_review_arr['data']['annual_review_approver']."',
				 annual_review_approver_comments='".$mysqli->real_escape_string($project_review_arr['data']['annual_review_approver_comments'])."',
				 added_on='".$project_review_arr['data']['added_on']."',
				 modified_by='".$_SESSION['first_last_name']."'";
			$result_archive_project_monitoring = $mysqli->query($insert_archive_project_monitoring);
			$archive_monitoring_new_id = $mysqli->insert_id;
			if($result_archive_project_monitoring)
			{
				$url = API_HOST_URL_PROJECT."get_project_review_output_score.php?review_id=".$review_id;  
    			$project_review_op_score_arr = requestByCURL($url);
				if(count($project_review_op_score_arr['data'])>0)
				{
					for($i=0; $i<count($project_review_op_score_arr['data']); $i++)
					{
						$insert_archive_project_monitoring_output_score = "insert into usaid_archive_project_monitoring_output_score set
							 archive_project_monitoring_id='".$archive_monitoring_new_id."',
							 project_output_score_description='".$mysqli->real_escape_string($project_review_op_score_arr['data'][$i]['project_output_score_description'])."',
							 project_output_impact_weight='".$project_review_op_score_arr['data'][$i]['project_output_impact_weight']."',
							 project_output_performance='".$project_review_op_score_arr['data'][$i]['project_output_performance']."',
							 project_output_risk='".$project_review_op_score_arr['data'][$i]['project_output_risk']."',
							 added_on='".dateFormat($project_geo_arr['data'][$i]['added_on'])."'";
						$result_archive_output_score = $mysqli->query($insert_archive_project_monitoring_output_score);

					}
				
				}
				
			}
		}
		
	}
} 

###request for get single project details using project id ===========
$project_id = '';

if(isset($_REQUEST['details']))
{
	$project_id = trim($_REQUEST['project_id']);
}
if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];	
}
$project_stage_id = '';
$environmental_threshold = '';
$gender_threshold = '';
if(isset($_REQUEST['project_stage_id'])) $project_stage_id = $_REQUEST['project_stage_id'];
if(isset($project_arr)) {
	$project_stage_id = $project_arr['data']['project_stage_id'];
	$environmental_threshold = $project_arr['data']['environmental_threshold'];
	$gender_threshold = $project_arr['data']['gender_threshold'];
}
##==add new review==
if(isset($_REQUEST['add_new_review']) && isset($_REQUEST['project_id']) && $_REQUEST['project_id']!=""){
	$_SESSION['form_msg'] = array();
	 
	$data_msg = array();
	$project_id = $_REQUEST['project_id'];
	$review_id = $_REQUEST['review_id'];
	$review_type = $_REQUEST['review_type'];
	$review_due_date = dateFormat(trim($_REQUEST['review_due_date']));
	$review_prompt_date = dateFormat(trim($_REQUEST['review_prompt_date']));
	$actual_review_date= "actual_review_date =NULL";
	if($_REQUEST['actual_review_date']!=""){
		$actual_review_date = "actual_review_date='".dateFormat(trim($_REQUEST['actual_review_date']))."'";
	}
	$overall_score = $mysqli->real_escape_string($_REQUEST['review_overall_score']);
	$annual_review_submission_comments = $mysqli->real_escape_string($_REQUEST['annual_review_submission_comments']);
	$annual_review_approver_comments = $mysqli->real_escape_string($_REQUEST['annual_review_approver_comments']);
	$annual_review_approval = $_REQUEST['annual_review_approval'];
	$annual_review_approver = $mysqli->real_escape_string($_REQUEST['annual_review_approver']);
	$output_ids = array();
	if(isset($_REQUEST['output_id'])){
		$output_ids = $_REQUEST['output_id'];
	}
	$project_output_score_description = $_REQUEST['project_output_score_description'];
	$project_output_impact_weight = $_REQUEST['project_output_impact_weight'];
	$project_output_performance = $_REQUEST['project_output_performance'];
	$project_output_risk = $_REQUEST['project_output_risk'];
	

	if($_REQUEST['review_id']==""){
		##===find review for this date is unique or not==##
		$sel_review = "SELECT review_due_date FROM usaid_project_monitoring WHERE project_id='".$project_id."' AND review_due_date='".$review_due_date."' AND review_type ='".$review_type."'";
		$exe_review = $mysqli->query($sel_review);
		
		if($exe_review->num_rows<=0){
			##==insert new review==#
			
			$mysqli->autocommit(0);
			$ins ="INSERT INTO usaid_project_monitoring set project_id='".$project_id."', review_type ='".$review_type."', review_due_date='".$review_due_date."', review_prompt_date='".$review_prompt_date."', ".$actual_review_date.", overall_score='".$overall_score."',annual_review_submission_comments='".$annual_review_submission_comments."',annual_review_approver_comments='".$annual_review_approver_comments."',annual_review_approval='".$annual_review_approval."',annual_review_approver='".$annual_review_approver."'";
			$exe = $mysqli->query($ins);
			
			if(!$ins){
				$_SESSION['form_msg']['msg_type'] ="error";
				$_SESSION['form_msg']['msg'] ="Something went wrong.";		
				$mysqli->rollback();	
			}
			else{
				$monitoring_id = $mysqli->insert_id;
				##==insert review score==##
				$ins_opt_score = "INSERT INTO usaid_project_monitoring_output_score(project_monitoring_id, project_output_score_description, project_output_impact_weight, project_output_performance, project_output_risk) VALUES ";
				
				for($i=0;$i<count($project_output_score_description);$i++){
					$project_output_score_description[$i] = $mysqli->real_escape_string($project_output_score_description[$i]);
					$ins_opt_score.="(".$monitoring_id.",'".$project_output_score_description[$i]."','".$project_output_impact_weight[$i]."','".$project_output_performance[$i]."','".$project_output_risk[$i]."'),";	
				}
				
				$ins_opt_score = substr_replace($ins_opt_score,"",-1);
				$exe_opt_score = $mysqli->query($ins_opt_score);
				$_SESSION['form_msg']['msg_type'] ="success";
				$_SESSION['form_msg']['msg'] ="Review inserted successfully";		
				$mysqli->commit();
				header("Location:add_project_review");	
			}
		}
		else{
			$_SESSION['form_msg']['msg_type'] ="error";
			$_SESSION['form_msg']['msg'] ="Project Review already exitst for this date.";		
		}
	}
	else if($_REQUEST['review_id']!=""){
		$all_output_score_ids = $_REQUEST['all_output_id'];
		$actual_output_ids = $_REQUEST['actual_output_id'];
		$rem_output_score_ids = array_diff_key($all_output_score_ids,$actual_output_ids);
		$rem_output_score_ids = array_filter($rem_output_score_ids);
		$edit_row = $_REQUEST['edit_row'];
		
		##===find review for this date is unique or not==##
		$sel_review = "SELECT review_due_date FROM usaid_project_monitoring WHERE id!='".$review_id."' AND review_due_date='".$review_due_date."' AND review_type ='".$review_type."'";
		$exe_review = $mysqli->query($sel_review);
		if($exe_review->num_rows<=0){
			insertArchiveMonitoringData($project_id,$review_id); // this functon use for insert monitoring review archive data
			##==update old review==#
			$mysqli->autocommit(0);
			$upd ="UPDATE usaid_project_monitoring set review_type ='".$review_type."', review_due_date='".$review_due_date."', review_prompt_date='".$review_prompt_date."', ".$actual_review_date.", overall_score='".$overall_score."', annual_review_submission_comments='".$annual_review_submission_comments."', annual_review_approver_comments='".$annual_review_approver_comments."', annual_review_approval='".$annual_review_approval."', annual_review_approver='".$annual_review_approver."' WHERE id='".$review_id."'";
			$exe = $mysqli->query($upd);
			
			if(!$upd){
				$_SESSION['form_msg']['msg_type']="error";
				$_SESSION['form_msg']['msg']="Something went wrong.";		
				$mysqli->rollback();	
			}
			else{
				
				if(count($rem_output_score_ids)>0){
					$rem_output_score_ids_string = implode(",",$rem_output_score_ids);
					$del_opt_score = "DELETE FROM usaid_project_monitoring_output_score WHERE id IN(".$rem_output_score_ids_string.")";
					$del_opt_score = $mysqli->query($del_opt_score);
				}
				else if(count($rem_output_score_ids)==0 && count($actual_output_ids)==0){
					$rem_output_score_ids_string = implode(",",$rem_output_score_ids);
					$del_opt_score = "DELETE FROM usaid_project_monitoring_output_score WHERE project_monitoring_id  =".$review_id;
					$del_opt_score = $mysqli->query($del_opt_score);
				}
				else{
					$_SESSION['form_msg']['msg_type'] ="error";
					$_SESSION['form_msg']['msg'] ="Something went wrong.";
				}
				
				##==if all output is deleted 
				if(count($output_ids)==0){
					$del_opt_score = "DELETE FROM usaid_project_monitoring_output_score WHERE project_monitoring_id='".$review_id."'";
					$del_opt_score = $mysqli->query($del_opt_score);
					$mysqli->commit();
					$_SESSION['form_msg']['msg_type'] ="success";
					$_SESSION['form_msg']['msg'] ="Review updated sucessfully.";	
					header("Location:add_project_review");
				}
				else{
					##==loop in all review output score
					foreach($output_ids as $key=> $val)
					{
						##===new output is added
						$i=$key;
						if($val!="" && $edit_row[$i]=="edit_row")
						{
							$output_score_description = $mysqli->real_escape_string($project_output_score_description[$i]);
							$upd_opt_score = "UPDATE usaid_project_monitoring_output_score set project_output_score_description='".$output_score_description."', project_output_impact_weight='".$project_output_impact_weight[$i]."', project_output_performance='".$project_output_performance[$i]."', project_output_risk='".$project_output_risk[$i]."' WHERE id='".$val."'";
							$exe_opt_score = $mysqli->query($upd_opt_score);
							if(!$exe_opt_score)
					           {	 	
					             $_SESSION['form_msg']['msg_type'] ="error";
					             $_SESSION['form_msg']['msg'] ="Something went wrong.";
					           }
						}
						else if($val == "")
						{
							$project_output_score_description[$i] = $mysqli->real_escape_string($project_output_score_description[$i]);
							$ins_opt_score = "INSERT INTO usaid_project_monitoring_output_score set project_monitoring_id='".$review_id."', project_output_score_description='".$project_output_score_description[$i]."', project_output_impact_weight='".$project_output_impact_weight[$i]."', project_output_performance='".$project_output_performance[$i]."', project_output_risk='".$project_output_risk[$i]."'";
							$exe_opt_score = $mysqli->query($ins_opt_score);
							if(!$exe_opt_score)
					           {	 	
					             $_SESSION['form_msg']['msg_type'] ="error";
					             $_SESSION['form_msg']['msg'] ="Something went wrong.";
					           }
						}
						  $mysqli->commit();
						
					}
				}			
			}
			
			$_SESSION['form_msg']['msg_type'] ="success";
			$_SESSION['form_msg']['msg'] ="Review updated sucessfully.";
			header("Location:add_project_review");
			
		}
		else{
			$_SESSION['form_msg']['msg_type'] ="error";
			$_SESSION['form_msg']['msg'] ="Project Review already exitst for this date.";		
		}

	}
}

##==delete review==
if(isset($_REQUEST['delete_review']) && isset($_REQUEST['project_id']) && $_REQUEST['project_id']!="" && isset($_REQUEST['review_id']) && $_REQUEST['review_id']!=""){
	$review_id = $_REQUEST['review_id'];
	$project_id = $_REQUEST['project_id'];
	
	$mysqli->autocommit(0);
	$del_review_op ="DELETE FROM usaid_project_monitoring_output_score WHERE project_monitoring_id='".$review_id."'";
	$exe_review_op = $mysqli->query($del_review_op);
	
	if(!$exe_review_op){
		$data_msg['msg_type']="error";
		$data_msg['msg']="Something went wrong.";		
		$mysqli->rollback();	
	}
	else{
		$del_review ="DELETE FROM usaid_project_monitoring WHERE id='".$review_id."' and project_id='".$project_id."'";
		$exe_review = $mysqli->query($del_review);
		if($exe_review){
			$mysqli->commit();
			header("Location:add_project_review");		
		}
		else{
			$_SESSION['form_msg']['msg'] ="error";
			$_SESSION['form_msg']['msg'] ="Something went wrong.";
		}
	}
}

##==edit project review==
if(isset($_REQUEST['edit_review']) && isset($_REQUEST['project_id']) && $_REQUEST['project_id']!=""){
	$review_id = $_REQUEST['review_id'];
	$project_id = $_REQUEST['project_id'];
	$edit_mode = "edit_mode";
	##==get project_review_info
	$url = API_HOST_URL_PROJECT."get_project_review.php?project_id=".$project_id."&review_id=".$review_id;  
    $project_review_arr = requestByCURL($url);
	$review_type = $project_review_arr['data']['review_type'];
	$review_due_date = $project_review_arr['data']['review_due_date'];
	$review_prompt_date = $project_review_arr['data']['review_prompt_date'];
	$actual_review_date = $project_review_arr['data']['actual_review_date'];
	$overall_score = $project_review_arr['data']['overall_score'];
	$annual_review_submission_comments = $project_review_arr['data']['annual_review_submission_comments'];
	$annual_review_approval = $project_review_arr['data']['annual_review_approval'];
	$annual_review_approver = $project_review_arr['data']['annual_review_approver'];
	$annual_review_approver_comments = $project_review_arr['data']['annual_review_approver_comments'];
	
	$url = API_HOST_URL_PROJECT."get_project_review_output_score.php?review_id=".$review_id;  
    $project_review_op_score_arr = requestByCURL($url);
}

##==get review info==
if($project_id!=""){
	##==get project_info
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);
	##==get project_stage_info
    $url = API_HOST_URL_PROJECT."api_demo.php?stage"; 
  	$project_stage_arr = requestByCURL($url);
	$ar_required= $par_required ='N';
	$ar_due_date = $ar_due_date_month = $ar_due_date_day = $ar_due_date_year = "";
	$ar_prompt_date = $ar_prompt_date_month = $ar_prompt_date_day = $ar_prompt_date_year ="";
	$par_due_date = $par_due_date_month = $par_due_date_day = $par_due_date_year ="";
	$par_prompt_date = $par_prompt_date_month = $par_prompt_date_day = $par_prompt_date_year ="";
	$ar_review_exits=false;
	$par_review_exits=false;
	
	##==get project upcoming annual review info
    $url = API_HOST_URL_PROJECT."get_project_upcoming_review.php?project_id=".$project_id."&review_type=annual_review"; 
  	$project_upcoming_ar = requestByCURL($url);

	##==get project upcoming project activity review info
	$url = API_HOST_URL_PROJECT."get_project_upcoming_review.php?project_id=".$project_id."&review_type=project_activity_review"; 
  	$project_upcoming_par = requestByCURL($url);
	
	if($project_upcoming_ar['data']!==NULL){
		$ar_review_exits=true;
		$ar_due_date = str_replace("-","/",$project_upcoming_ar['data']['review_due_date']);
		$ar_due_date_month = date('m',strtotime($ar_due_date));
		$ar_due_date_day = date('d',strtotime($ar_due_date));  
		$ar_due_date_year = date('Y',strtotime($ar_due_date));
		
		$ar_prompt_date = str_replace("-","/",$project_upcoming_ar['data']['review_prompt_date']);
		$ar_prompt_date_month = date('m',strtotime($ar_prompt_date));
		$ar_prompt_date_day = date('d',strtotime($ar_prompt_date));  
		$ar_prompt_date_year = date('Y',strtotime($ar_prompt_date));
	}
	if($project_upcoming_par['data']!==NULL){
		$par_review_exits=true;
		$par_due_date = str_replace("-","/",$project_upcoming_par['data']['review_due_date']);
		$par_due_date_month = date('m',strtotime($par_due_date));
		$par_due_date_day = date('d',strtotime($par_due_date));  
		$par_due_date_year = date('Y',strtotime($par_due_date));
		
		$par_prompt_date = str_replace("-","/",$project_upcoming_par['data']['review_prompt_date']);
		$par_prompt_date_month = date('m',strtotime($par_prompt_date));
		$par_prompt_date_day = date('d',strtotime($par_prompt_date));  
		$par_prompt_date_year = date('Y',strtotime($par_prompt_date));
	}
}
?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title><?php echo TITLE;?></title>
<?php include('includes/resources.php');?>
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<?php include('includes/project_header.php');?>
		<div class="extra_ht"></div><div class="extra_ht"></div>
		<!--main container blk start-->
		<div class="tbl-block">
			<div class="tbl-caption">
				<div class="tbl-content-head">Manage Monitoring</div>
				<div class="clear"></div>
			</div>
			<div class="project-detail-blk table-container">
				<!--review display only block start-->
				<div class="form-blk">
				<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
						</div>
					<div id="submission_msg" class="form-msg usa-alert disp-none <?php if(isset($_SESSION['form_msg']) && $_SESSION['form_msg']['msg']=="success") { echo "usa-alert-success"; } else { echo "usa-alert-error"; }?>">
						<div class="usa-alert-body">
						<h3 class="usa-alert-heading"><?php if(isset($_SESSION['form_msg']) && $_SESSION['form_msg']['msg']=="success") { echo "Success"; } else { echo "Error"; }?></h3>
						<p class="usa-alert-text"><?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg'];}?></p>
						</div>
					</div>
					<!--<div id="submission_msg" class="form-msg <?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg_type'];}?>">
						<?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg'];}?>
					</div>-->
					<div></div>
					<header><h2 class="form-blk-head">Reviews</h2></header>
					<div class="extra_ht"></div><div class="extra_ht"></div>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="threshold">
								<div class="sm-head">AR Required?</div>
								<div class="div_label">
									<input type="radio" name="annual_review_required" value="Y" class="review_req" <?php if($ar_review_exits){?> checked="checked" <?php } else {?> disabled="disabled" <?php }?>/> <label>Yes</label>
								</div>
								<br/>
								<div class="div_label">
									<input type="radio" name="annual_review_required" value="N" <?php  if(!$ar_review_exits){?> checked="checked" <?php } else {?> disabled="disabled" <?php }?> class="review_req"/> <label>No
</label>						</div>
								
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk <?php if(!$ar_review_exits){?>disp-none<?php }?>">
							<div class="calendar-blk">
								<div class="sm-head">AR Due Date</div>
								<table class="project_dates">
									<tr class="head">
										<td>Month</td>
										<td>Day</td>
										<td>Year</td>
									</tr>	
									<tr>
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $ar_due_date_month;?>" readonly="reaonly"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $ar_due_date_day;?>" readonly="reaonly"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $ar_due_date_year;?>" readonly="reaonly"/></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk <?php if(!$ar_review_exits){?>disp-none<?php }?>">
							<div class="calendar-blk">
								<div class="sm-head">AR Prompt Date</div>
								<table class="project_dates">
									<tr class="head">
										<td>Month</td>
										<td>Day</td>
										<td>Year</td>
									</tr>
									<tr>
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $ar_prompt_date_month;?>" readonly="reaonly"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $ar_prompt_date_day;?>" readonly="reaonly"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $ar_prompt_date_year;?>" readonly="reaonly"/></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12 <?php if($ar_review_exits){?>disp-none<?php }?>">
							<div style="height:30px"></div>
							<div class="highlight_msg text-danger">No Annual Review Required.</div>
						</div>
					</div>
					<div class="gray-line"></div>
					<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
						<div class="threshold">
							<div class="sm-head">PAR Required?</div>
							<div class="div_label">
								<input type="radio" name="project_activity_review_required" value="Y" <?php if($par_review_exits){?> checked="checked" <?php } else {?> disabled="disabled" <?php }?> class="review_req"/> <label>Yes </label>
							</div>
							
							<br/>
							<div class="div_label">
								<input type="radio" name="project_activity_review_required" value="N" <?php if(!$par_review_exits){?> checked="checked" <?php } else {?> disabled="disabled" <?php } ?> class="review_req"/><label> No </label>
							
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk <?php if(!$par_review_exits){?>disp-none<?php }?>">
						<div class="calendar-blk">
							<div class="sm-head">PAR Due Date</div>
							<table class="project_dates">
								<tr class="head">
									<td>Month</td>
									<td>Day</td>
									<td>Year</td>
								</tr>	
								<tr>
									<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $par_due_date_month;?>" readonly="reaonly"/></td>
									<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $par_due_date_day;?>" readonly="reaonly"/></td>
									<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $par_due_date_year;?>" readonly="reaonly"/></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk <?php if(!$par_review_exits){?>disp-none<?php }?>">
						<div class="calendar-blk">
							<div class="sm-head">PAR Prompt Date</div>
							<table class="project_dates">
								<tr class="head">
									<td>Month</td>
									<td>Day</td>
									<td>Year</td>
								</tr>
								<tr>
									<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $par_prompt_date_month;?>" readonly="reaonly"/></td>
									<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $par_prompt_date_day;?>" readonly="reaonly"/></td>
									<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $par_prompt_date_year;?>" readonly="reaonly"/></td>
								</tr>
							</table>
						</div>
					</div>
					<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12 <?php if($par_review_exits){?>disp-none<?php }?>">
						<div style="height:30px"></div>
						<div class="highlight_msg text-danger">No Project/ Activity Review Required.</div>
					</div>
					</div>
					<div class="gray-line"></div>
				</div>
				<!--review display only block end-->
				<!--add review block start-->
				<div class="form-blk">
					<header><h3 id="add_review_blk_header" class="form-blk-head"><?php if(!isset($edit_mode)){?>Add New Review<?php } else {?>Edit Review<?php }?></h3></header><br/>
					<button type="button" class="<?php if(isset($edit_mode)){?>disp-none<?php }?>" id="add_new_review"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Review</button>
					<!--add review block start-->
					<form id="add_review_form" method="post" autocomplete="off">
						<input type="hidden" name="project_id" class="project_id" value="<?php echo $project_id;?>"/>
						<div class="add_review_blk <?php if(!isset($edit_mode)){?>disp-none<?php }?>">
						<div class="row review_row">
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 review_radio_blk">
								<div class="threshold">
									<div class="sm-head">Type of Review</div>
									<div style="height:40px"></div>
									<select class="form-control" id="sel_review_type" name="review_type">
										<option value="">Select Review Type</option>
										<option value="Annual Review">Annual Review</option>
										<option value="Project Activity Review">Project Activity Review</option>
									</select>
								</div>
								<?php 
								##==fill review type on edit mode==
								if(isset($edit_mode)){?>
								<script>
									$(document).ready(function(){
										$('#sel_review_type').val("<?php echo $review_type;?>");
										$('#sel_review_type').trigger("change");
									});
								</script>	
								<?php }?>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk review_ip_blk disp-none">
								<div class="calendar-blk">
									<div class="sm-head">Review Due Date</div>
									<table class="project_dates no-bdr">
										<tr class="head">
											<td>Month</td>
											<td>Day</td>
											<td>Year</td>
										</tr>	
										<tr>
											<td><input type='text' class="form-control month date_ip comp_date only_num" value="<?php if(isset($edit_mode)){ echo dateSpecificFormat($review_due_date,'m'); }?>"/></td>
											<td><input type='text' class="form-control date date_ip comp_date only_num" value="<?php if(isset($edit_mode)){ echo dateSpecificFormat($review_due_date,'d'); }?>"/></td>
											<td><input type='text' class="form-control year date_ip comp_date only_num" value="<?php if(isset($edit_mode)){ echo dateSpecificFormat($review_due_date,'Y'); }?>"/></td>
										</tr>
									</table>
									<input type="hidden" name="review_due_date" class="formatted_date"  value="<?php if(isset($edit_mode)){ echo dateSpecificFormat($review_due_date,'m').'/'.dateSpecificFormat($review_due_date,'d').'/'.dateSpecificFormat($review_due_date,'Y'); }?>"/>								
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk review_ip_blk disp-none">
								<div class="calendar-blk">
									<div class="sm-head">Review Prompt Date</div>
									<table class="project_dates no-bdr">
										<tr class="head">
											<td>Month</td>
											<td>Day</td>
											<td>Year</td>
										</tr>
										<tr>
											<td><input type='text' class="form-control month date_ip comp_date only_num" value="<?php if(isset($edit_mode)){ echo dateSpecificFormat($review_prompt_date,'m'); }?>"/></td>
											<td><input type='text' class="form-control date date_ip comp_date only_num" value="<?php if(isset($edit_mode)){ echo dateSpecificFormat($review_prompt_date,'d'); }?>"/></td>
											<td><input type='text' class="form-control year date_ip comp_date only_num" value="<?php if(isset($edit_mode)){ echo dateSpecificFormat($review_prompt_date,'Y'); }?>"/></td>
										</tr>
									</table>
									<input type="hidden" name="review_prompt_date" class="formatted_date"  value="<?php if(isset($edit_mode)){ echo dateSpecificFormat($review_prompt_date,'m').'/'.dateSpecificFormat($review_prompt_date,'d').'/'.dateSpecificFormat($review_prompt_date,'Y'); }?>"/>
								</div>
							</div>
						</div>
						<div class="row review_row">
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk review_ip_blk disp-none">
								<div>
									<div class="sm-head">Overall Score</div>
									<div style="height:40px"></div>
									<input type="text" name="review_overall_score" class="form-control sm-ip" value="<?php if(isset($edit_mode)){ echo $overall_score;}?>"/>								
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk review_ip_blk disp-none">
								<div class="calendar-blk">
									<div class="sm-head">Actual Review Date</div>
									<table class="project_dates no-bdr">
										<tr class="head">
											<td>Month</td>
											<td>Day</td>
											<td>Year</td>
										</tr>	
										<tr>
											<td><input type='text' class="form-control month date_ip only_num can_be_blank" value="<?php if(isset($edit_mode) && $actual_review_date!=""){ echo dateSpecificFormat($actual_review_date,'m'); }?>"/></td>
											<td><input type='text' class="form-control date date_ip only_num can_be_blank" value="<?php if(isset($edit_mode) && $actual_review_date!=""){ echo dateSpecificFormat($actual_review_date,'d'); }?>"/></td>
											<td><input type='text' class="form-control year date_ip only_num can_be_blank" value="<?php if(isset($edit_mode) && $actual_review_date!=""){ echo dateSpecificFormat($actual_review_date,'Y'); }?>"/></td>
										</tr>
									</table>
									<input type="hidden" name="actual_review_date" class="formatted_date"  value="<?php if(isset($edit_mode) && $actual_review_date!=""){ echo dateSpecificFormat($actual_review_date,'m').'/'.dateSpecificFormat($actual_review_date,'d').'/'.dateSpecificFormat($actual_review_date,'Y'); }?>"/>								
								</div>
							</div>
						</div>
						<!---add output scoring block start-->
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 review_ip_blk disp-none">
								<div class="form-blk">
									<header><h3 class="sm-head">Output Scoring</h3></header>
									<a class="btn btn-blue <?php if(isset($edit_mode) && $annual_review_approval=="Approve"){?> disp-none <?php }?>" id="add_output_score"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Output Scoring</a>
									<div class="add_output_scoring_blk">
										<table id="review_output_score_tbl" class="table table-bordered table-striped">
											<tr class="head">
												<td>Description</td>
												<td class="sm_field">Impact Weight (%)</td>
												<td class="sm_field">Perfomance</td>
												<td class="sm_field">Risk</td>
												<td class="sm_field text-center">Action</td>
											</tr>
											<?php if(isset($edit_mode) && count($project_review_op_score_arr['data'])>0){
												  for($i=0;$i<count($project_review_op_score_arr['data']);$i++){
												  $output_id = $project_review_op_score_arr['data'][$i]['project_monitoring_output_score_id'];
											?>
											<input type="hidden" value="<?php echo $project_review_op_score_arr['data'][$i]['project_monitoring_output_score_id'];?>" name="all_output_id[<?php echo $output_id;?>]" />
											<tr class="output_score_row">
												<td class="text-left">
													<input type="hidden" class="edit_row" value="" name="edit_row[]"/>
													<input type="hidden" value="<?php echo $project_review_op_score_arr['data'][$i]['project_monitoring_output_score_id'];?>" name="actual_output_id[<?php echo $output_id?>]" />
													<input type="hidden" value="<?php echo $project_review_op_score_arr['data'][$i]['project_monitoring_output_score_id'];?>" name="output_id[]" />
													<textarea class="form-control autoh_textarea" name="project_output_score_description[]" readonly="readonly" rows="2"><?php echo $project_review_op_score_arr['data'][$i]['project_output_score_description'];?></textarea>
												</td>
												<td>
													<input type="text" class="form-control impact_perc only_num"  name="project_output_impact_weight[]" readonly="readonly" value="<?php echo $project_review_op_score_arr['data'][$i]['project_output_impact_weight'];?>"/>
												</td>
												<td>
													<?php $perfm = $project_review_op_score_arr['data'][$i]['project_output_performance'];?>
													<input type="hidden" name="project_output_performance[]"  value="<?php echo $perfm;?>"/>
													<select class="form-control project_output_performance_dpw" disabled="disabled">
														<option value="">Select Perfomance</option>
														<option value="A" <?php if($perfm=="A"){ echo "selected='selected'";}?>>A</option>
														<option value="B" <?php if($perfm=="B"){ echo "selected='selected'";}?>>B</option>
														<option value="C" <?php if($perfm=="C"){ echo "selected='selected'";}?>>C</option>
														<option value="D" <?php if($perfm=="D"){ echo "selected='selected'";}?>>D</option>
														<option value="F" <?php if($perfm=="F"){ echo "selected='selected'";}?>>F</option>
													</select>		
												</td>
												<td><?php $risk= $project_review_op_score_arr['data'][$i]['project_output_risk'];?>
													<input type="hidden" name="project_output_risk[]"  value="<?php echo $risk;?>"/>
													<select class="form-control project_output_risk_dpw" disabled="disabled">
														<option value="">Select Risk</option>
														<option value="L" <?php if($risk=="L"){ echo "selected='selected'";}?>>L</option>
														<option value="M" <?php if($risk=="M"){ echo "selected='selected'";}?>>M</option>
														<option value="H" <?php if($risk=="H"){ echo "selected='selected'";}?>>H</option>
													</select>
												</td>
												<td class="text-center">
													<a class="btn btn-warning edit_output_score"><i class="fa fa-pencil" aria-hidden="true"></i> </a> 
													<a class="btn btn-danger remove_output_score"><i class="fa fa-trash-o" aria-hidden="true"></i> </a>
												</td>
											</tr>			
											<?php } ?>
											<tr class="no_row_found disp-none">
												<td colspan="5" class="bold text-danger">No output score found</td>
											</tr>
											<?php } else {?>
											<tr class="no_row_found">
												<td colspan="5" class="bold text-danger">No output score found</td>
											</tr>
											<?php }?>
										</table>	
									</div>
								</div>
							</div>	
						</div>
						<!--add output scoring block end-->
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 review_ip_blk disp-none">
								<div class="form-blk">
									<header><h3 class="sm-head">Documents</h3></header>
									<div class="row">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="review_doc_lblk_out">
												<header><h2 class="form-blk-head">Submission</h2></header>
												<header><h2 class="sm-head">Submission Comments</h2></header>
												<textarea class="form-control autoh_textarea" name="annual_review_submission_comments" rows="2"><?php if(isset($edit_mode)){ echo $annual_review_submission_comments; }?></textarea>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="review_doc_rblk_out">
												<header><h3 class="form-blk-head">Authorization</h3></header>
												<header><h2 class="sm-head">Comments</h2></header>
												<textarea class="autoh_textarea" name="annual_review_approver_comments" rows="2"><?php if(isset($edit_mode)){ echo $annual_review_approver_comments; }?></textarea>
												<div class="extra_ht"></div>
												<div class="threshold">
														<div class="div_label">
														<input type="radio" name="annual_review_approval" value="Approve" class="review_req project_status" <?php if(isset($edit_mode) && ($annual_review_approval=="Approve")){ echo "checked='checked'"; }?>/> <label> Approve </label> </div>
														<div style="height:10px;"></div>
														<div class="div_label">	
														<input type="radio" name="annual_review_approval" value="Reject" class="review_req project_status" <?php if(isset($edit_mode) && ($annual_review_approval=="Reject")){ echo "checked='checked'"; }?> <?php if(isset($edit_mode) && ($annual_review_approval=="Approve")){?> disabled="disabled"  <?php }?>/> <label> Reject </label>
														</div>
													
													<div class="not_now <?php if(isset($edit_mode) && ($annual_review_approval=="Approve")){?> disp-none  <?php }?>"><a id="not_now_review_flag">Not Now Approve or Reject</a></div>
												</div>
												<div class="approver-blk <?php if((isset($edit_mode) && ($annual_review_approval!="Approve" && $annual_review_approval!="Reject")) || !isset($edit_mode)){?> disp-none  <?php }?> ">
													<div class="extra_ht"></div><div class="extra_ht"></div>
													<header><h2 class="sm-head">Approver</h2></header>
													<input type="text" class="form-control annual_review_approver" name="annual_review_approver" value="<?php if(isset($edit_mode)){ echo $annual_review_approver; }?>"/>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row review_ip_blk disp-none">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<input type="hidden" name="review_id" value="<?php if(isset($edit_mode)){ echo $review_id;}?>" class="review_id"/>
								<input type="hidden" name="add_new_review" value="add_new_review"/>
								<button class="btn btn-blue" id="cancel_review" type="button">Cancel</button>
								<button class="btn btn-blue <?php if(isset($edit_mode) && $annual_review_approval=="Approve"){?> disp-none <?php }?>" id="save_review" type="button">Save Review</button>
								<div class="form-msg usa-alert disp-none">
									<div class="usa-alert-body">
									<h3 class="usa-alert-heading"></h3>
									<p class="usa-alert-text"></p>
									</div>
								</div>	
							</div>
						</div>
					</div>
					</form>
					<!--add review block end-->
					<div style="height:30px;"></div>
					<div class="gray-line"></div>
					<!--review listing block start-->
					<?php 
						$url = API_HOST_URL_PROJECT."get_all_project_reviews.php?project_id=".$project_id."";  
						$project_review_arr = requestByCURL($url);
					?>
					<div class="review_listing_blk">
						<header><h3 class="form-blk-head">Project Review List</h3></header>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<table class="table table-bordered review-listing table-striped">
									<tr class="head">
										<td class="md_field">Type of Review</td>
										<td class="sm_field">Due Date</td>
										<td class="sm_field">Prompt Date</td>
										<td>Approver</td>
										<td class="sm_field">Change Log</td>
										<td class="sm_field text-center">Action</td>
									</tr>
									<?php 
									if(count($project_review_arr['data'])>0){
									for($i=0;$i<count($project_review_arr['data']);$i++){?>
									<tr class="data_tr">
										<td><?php echo $project_review_arr['data'][$i]['review_type'];?></td>
										<td><?php echo date('d M Y',strtotime($project_review_arr['data'][$i]['review_due_date']));?></td>
										<td><?php echo date('d M Y',strtotime($project_review_arr['data'][$i]['review_prompt_date']));?></td>
										<td><?php echo $project_review_arr['data'][$i]['annual_review_approver'];?></td>
										<td class="text-center"><?php
											$url = API_HOST_URL_PROJECT."get_all_archive_review.php?project_monitoring_id=".$project_review_arr['data'][$i]['review_id']."";  
											$archive_review_arr = requestByCURL($url);
											if(count($archive_review_arr['data'])>0) { ?>
											<a href="view_project_monitoring_archive.php?project_monitoring_id=<?php echo $project_review_arr['data'][$i]['review_id'];?>">Change Log</a><?php } else { ?><a href="javascript:void(0)">No Change Log</a><?php }?></td>
										<td class="text-center">
											<?php if($project_review_arr['data'][$i]['annual_review_approval']!="Approve"){?>
											<form class="edit_review_form disp-inline" method="post">
												<input type="hidden" value="edit_review" name="edit_review"/>
												<input type="hidden" value="<?php echo $project_review_arr['data'][$i]['project_id'];?>" name="project_id"/>
												<input type="hidden" value="<?php echo $project_review_arr['data'][$i]['review_id'];?>" name="review_id"/>
												<a class="btn btn-warning edit_review" title="Edit Review"><i class="fa fa-pencil" aria-hidden="true"></i> </a> 
											</form>
											<form class="delete_review_form disp-inline" method="post">
												<input type="hidden" value="delete_review" name="delete_review"/>
												<input type="hidden" value="<?php echo $project_review_arr['data'][$i]['project_id'];?>" name="project_id"/>
												<input type="hidden" value="<?php echo $project_review_arr['data'][$i]['review_id'];?>" name="review_id"/>
												<a class="btn btn-danger delete_review" title="Delete Review"><i class="fa fa-trash-o" aria-hidden="true"></i> </a>
											</form>
											<?php } else {?>
											<form class="edit_review_form disp-inline" method="post">
												<input type="hidden" value="edit_review" name="edit_review"/>
												<input type="hidden" value="<?php echo $project_review_arr['data'][$i]['project_id'];?>" name="project_id"/>
												<input type="hidden" value="<?php echo $project_review_arr['data'][$i]['review_id'];?>" name="review_id"/>
												<a class="btn btn-warning edit_review" title="View Approved Review"><i class="fa fa-eye" aria-hidden="true"></i> </a> 
											</form>
											<?php }?>
										</td>
									</tr>
									<?php }} else {?>
									<tr class="no_row_found">
										<td colspan="5" class="bold text-danger">No review found for this project</td>
									</tr>
									<?php }?>
								</table>
							</div>
						</div>
					</div>
					<!--review listing block end-->
				</div>
				<!--review display only block end-->
			</div>
	    </div>
	  	<!--main container blk end-->
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script>
	setTimeout(function(){
		$('#submission_msg').html("");
	},10000);	
			
			
	/*add Project Output*/
	$('#add_project_output').click(function(){
		var html = '<div class="opt-ip-outer"><textarea class="form-control opt-ip" name="project_output_score_description[]"></textarea></div>';
		$('.proj_opt_blk').append(html);
		$('#save_project_output_desc').removeClass('disp-none');
	});
	
	/*show hide date input on the basis of review type*/
	$('#sel_review_type').change(function(){
		var val = $(this).val();
		
		if(val!=""){ /*show everything if review type is selected*/
			$('.add_review_blk').find('.review_ip_blk').removeClass('disp-none');
			$('#add_new_review').addClass('disp-none');	
		}
		else{
			/*if no review select hide everything*/
			$('.add_review_blk').find('.review_ip_blk').find('input[type="text"],input[type="hidden"]').val("");
			$('.add_review_blk').find('.review_ip_blk').addClass('disp-none');	
			$('#add_new_review').removeClass('disp-none');
		}
	});
	
	/*cancel review*/
	$('#cancel_review').click(function(){
		$('#sel_review_type').val("");
		$('.add_review_blk').find('.review_ip_blk').addClass('disp-none');	
		$('.add_review_blk').addClass('disp-none');
		$('#add_new_review').removeClass('disp-none');
		$('.add_review_blk').find('input[type="text"],select,textarea').val("");
		$('.add_review_blk').find('input[name="annual_review_approval"]').prop("checked","");
		$('.add_output_scoring_blk').find('.output_score_row').remove();	
		$('.add_output_scoring_blk').find('.no_row_found').removeClass('disp-none');	
		$('#add_new_review').removeClass('disp-none');
		$('#save_review,#add_output_score,.not_now').removeClass('disp-none');
		$('#add_review_form').find('.review_id').val("");
		$('#add_review_form').find('.invalid_ip').removeClass("error_ip invalid_ip");
		$('#add_review_form').find('.project_status').removeAttr('disabled');
		$('#add_review_blk_header').text("Add New Review");
	});
	
	
	/*add new review*/
	$('#add_new_review').click(function(){
		$('.add_review_blk').removeClass('disp-none');
	});
	
	/*add_output_score*/
	$('#add_output_score').click(function(){
		if($('.add_output_scoring_blk').find('.no_row_found').length>0){
			$('.add_output_scoring_blk').find('.no_row_found').addClass('disp-none');	
		}
		
		var performance_dpw ='<select class="form-control" name="project_output_performance[]"><option value="">Select Perfomance</option><option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option><option value="F">F</option></select>'; 
		var risk_dpw ='<select class="form-control" name="project_output_risk[]"><option value="">Select Risk</option><option value="L">L</option><option value="M">M</option><option value="H">H</option></select>'; 
		var html= '<tr class="output_score_row"><td><input type="hidden" value="" name="output_id[]" /><textarea class="autoh_textarea" name="project_output_score_description[]" rows="2"></textarea></td><td><input type="text" class="form-control impact_perc only_num"  name="project_output_impact_weight[]"/></td><td>'+performance_dpw+'</td><td>'+risk_dpw+'</td><td class="text-center"><a class="btn btn-danger remove_output_score" title="Delete Review"><i class="fa fa-trash-o" aria-hidden="true"></i> </a></td></tr>';
		
		if($('#review_output_score_tbl').find('.output_score_row').length==0)
			$('#review_output_score_tbl').find('.head').after(html);
		else
			$('#review_output_score_tbl').find('.output_score_row:last').after(html);
	});
	
	/*make output score input editable*/
	$(document).on('click','.edit_output_score', function(){
		$(this).addClass('disp-none');
		$(this).closest('.output_score_row').find('textarea, input[type="text"]').removeAttr('readonly');
		$(this).closest('.output_score_row').find('select').removeAttr('disabled');
		$(this).closest('.output_score_row').find('.edit_row').val('edit_row');
	});
	
	
	/*remove output score input*/
	$(document).on('click','.remove_output_score', function(){
		$(this).closest('.output_score_row').remove();
		if($('#review_output_score_tbl').find('.output_score_row').length==0)
		$('.add_output_scoring_blk').find('.no_row_found').removeClass('disp-none');	
	});
	

	/*save review*/
	$('#save_review').click(function(){
		var ar_due_date = $('#add_review_form').find('input[name="review_due_date"]').val(); 
		var ar_due_date_arr = new Array();
		ar_due_date_arr = ar_due_date.split("/"); 
		
		var ar_prompt_date = $('#add_review_form').find('input[name="review_prompt_date"]').val();
		var ar_prompt_date_arr = new Array();
		ar_prompt_date_arr = ar_prompt_date.split("/"); 
		ar_due_date_arr[0] = ar_due_date_arr[0]-1;
		ar_prompt_date_arr[0] = ar_prompt_date_arr[0]-1;
		
		var ard_date = new Date(ar_due_date_arr[2],ar_due_date_arr[0],ar_due_date_arr[1]); 
		var arp_date = new Date(ar_prompt_date_arr[2],ar_prompt_date_arr[0],ar_prompt_date_arr[1]);
	
		/*invalid ip*/
		var invalid_ip = $('#add_review_form').find('.invalid_ip').filter(function(){
			return $(this);
		}).length;
		
		
		/*any input box can't be blank*/
		var blank_date_ip = $('#add_review_form').find('.comp_date').filter(function(){
			return $(this).val()== "" ;
		}).length;
		
		var overall_score = $('#add_review_form').find('input[name="review_overall_score"]').val();
		var blank_proj_output_score_desc =  $('#add_review_form').find('input[name="project_output_score_description[]"]').filter(function(){
			return $(this).val()== "" ;
		}).length;
		
		var blank_proj_output_impact_weight =  $('#add_review_form').find('input[name="project_output_impact_weight[]"]').filter(function(){
			return $(this).val()== "" ;
		}).length;
		
		/*var blank_proj_output_performance =  $('#add_review_form').find('select[name="project_output_performance[]"]').filter(function(){
			return $(this).val()== "" ;
		}).length;
		*/
		var blank_output_risk =  $('#add_review_form').find('select[name="project_output_risk[]"]').filter(function(){
			return $(this).val()== "" ;
		}).length;
		
		var review_approver_comments =  $('#add_review_form').find('textarea[name="annual_review_approver_comments"]').val();
		var annual_review_approval =  $('#add_review_form').find('input[name="annual_review_approval"]:checked').val();
		var annual_review_approver =  $('#add_review_form').find('input[name="annual_review_approver"]').val();
		var project_status_len = $('.project_status:checked').length; 
		
		var invalid_proj_output_impact_weight =  $('#add_review_form').find('input[name="project_output_impact_weight[]"]').filter(function(){
			return Number($(this).val())< 0 || Number($(this).val())> 100;
		}).length;
		
		
		var error="", error_msg="";
		if(invalid_ip>0){
			error="error";
			error_msg="Something went wrong.";		
		}
		else if(blank_date_ip>0){
			error="error";
			error_msg="Date can't be blank";
		}
		else if(ard_date<arp_date){
			error="error";
			error_msg="Prompt date can't be greater than Due date";
		}
		else if(overall_score ==""){
			error="error";
			error_msg="Overall Score can't be blank";
		}
		else if(blank_proj_output_score_desc>0){
			error="error";
			error_msg="Output Score description can't be blank";
		}
		else if(invalid_proj_output_impact_weight>0){
			error="error";
			error_msg="Impact percentage is wrong.";
		}
		else if(project_status_len>0 && $(".annual_review_approver").val()==""){
			error="error";
			error_msg="Please fill the approver name";
		}
		else{
			$('#add_review_form').submit();
		} 
		
		
		if(error=="error"){
			$('#add_review_form').find('.form-msg').addClass('usa-alert-error').removeClass("disp-none");
			$('#add_review_form').find('.form-msg').find('.usa-alert-heading').text("Error");
			$('#add_review_form').find('.form-msg').find('.usa-alert-text').text(error_msg);
			setTimeout(function(){
				$('#add_review_form').find('.form-msg').addClass("disp-none");
			},5000);	
		}
	});
	
	/*delete review*/
	$(document).on('click','.delete_review',function(){
		if(confirm("Are you sure to delete this review?")){
			$(this).closest('.delete_review_form').submit();
		}
	});
	
	/*edit review*/
	$(document).on('click','.edit_review',function(){
		$(this).closest('.edit_review_form').submit();
	});
	
	/*validate review output impact percentage*/	
	$(document).on('keyup','.impact_perc',function(){
		$(this).validate_ip();
	});
	
	/*approve and reject flag unset*/
	$('#not_now_review_flag').click(function(){
		$('.add_review_blk').find('input[name="annual_review_approval"]').prop("checked","");
		$('.approver-blk').find('input[type="text"]').val("");
		$('.approver-blk').addClass('disp-none');
	});
	
	$('.project_output_risk_dpw').change(function(){
		$(this).prev('input[name="project_output_risk[]"]').val($(this).val());
	});
	
	$('.project_output_performance_dpw').change(function(){
		$(this).prev('input[name="project_output_performance[]"]').val($(this).val());
	});
	
	$('.project_status').click(function(){
		$('.approver-blk').removeClass('disp-none');
	});
</script>
<?php unset($_SESSION['form_msg']);?>
</body>
</html>
