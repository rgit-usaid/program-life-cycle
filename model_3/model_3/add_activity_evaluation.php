<?php include('config/functions.inc.php');
##==validate user====
validate_user();
unset($_SESSION['evaluation_id']);
###request for get single project details using project id ===========
$project_id = '';
$activity_id = '';
if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];
}

if(isset($_SESSION['project_id']) && isset($_REQUEST['activity_id']))
{	
	$_SESSION['activity_id'] = $activity_id = $_REQUEST['activity_id'];
}

if(isset($_SESSION['project_id']) && isset($_SESSION['activity_id']))
{	
	$activity_id = $_SESSION['activity_id'];
}


if($project_id!="" && $activity_id!=""){

	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);
	
	$url = API_HOST_URL_PROJECT."get_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id;
    $project_activity_arr = requestByCURL($url);

}
$page_type="activity_pages";
##==add new evaluation==
if(isset($_REQUEST['add_new_evaluation']) && isset($_REQUEST['project_id']) && $_REQUEST['project_id']!="" && isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!=""){
	
	$_SESSION['form_msg'] = array();
	$data_msg = array();
	$project_id = $_REQUEST['project_id'];
	$activity_id = $_REQUEST['activity_id'];
	$evaluation_id = $_REQUEST['evaluation_id'];
	$type = $_REQUEST['type'];
	$eval_type_desc_other= "evaluation_type_description_other = NULL";
	if($_REQUEST['evaluation_type_description_other']!=""){
		$eval_type_desc_other = "evaluation_type_description_other='".$mysqli->real_escape_string($_REQUEST['evaluation_type_description_other'])."'";
	}
	
	$management_type = $_REQUEST['management_type'];
	$additional_comment= "additional_comment = NULL";
	if($_REQUEST['additional_comment']!=""){
		$additional_comment = "additional_comment='".$mysqli->real_escape_string($_REQUEST['additional_comment'])."'";
	}
	$start_date = dateFormat(trim($_REQUEST['evaluation_start_date']));
	$end_date = dateFormat(trim($_REQUEST['evaluation_end_date']));
	$estimated_cost = trim($_REQUEST['estimated_cost']);
	$estimated_cost = str_replace('$','',$estimated_cost);
	$estimated_cost = str_replace(',','',$estimated_cost);
	
	if($_REQUEST['evaluation_id']=="")
	{
		##===find evaluation for this date is unique or not==##
		$sel_evaluation = "SELECT start_date FROM usaid_project_activity_evaluation WHERE activity_id='".$activity_id."' AND start_date='".$start_date."'";
		$exe_evaluation = $mysqli->query($sel_evaluation);
		$total = $exe_evaluation->num_rows;
		if($total > 0)
		{
		  	$_SESSION['form_msg']['msg_type'] ="error";
			$_SESSION['form_msg']['msg'] ="Duplicate evalution at start date.";		
		}
		else	 
		{
			##==insert new review==# 
			$mysqli->autocommit(0);
			$ins ="INSERT INTO usaid_project_activity_evaluation set project_id='".$project_id."',activity_id='".$activity_id."', type ='".$type."', ".$eval_type_desc_other.", management_type='".$management_type."',".$additional_comment.", start_date='".$start_date."',end_date='".$end_date."',estimated_cost='".$estimated_cost."'";
			$exe = $mysqli->query($ins);
			if(!$exe){
				$_SESSION['form_msg']['msg_type'] ="error";
				$_SESSION['form_msg']['msg'] ="Something went wrong.";		
				$mysqli->rollback();	
			}
			else{
				$monitoring_id = $mysqli->insert_id;
				$_SESSION['form_msg']['msg_type'] ="success";
				$_SESSION['form_msg']['msg'] ="Evaluation inserted successfully";
				$mysqli->commit();
				header("Location:add_activity_evaluation");	
			}
		}
	}
	else if($_REQUEST['evaluation_id']!="")
	{
		##===find review for this date is unique or not==##
		$sel_evaluation = "SELECT start_date FROM usaid_project_activity_evaluation WHERE activity_id='".$activity_id."' AND start_date='".$start_date."' and id!='".$_REQUEST['evaluation_id']."'";
		$exe_evaluation = $mysqli->query($sel_evaluation);
		$total = $exe_evaluation->num_rows;
		if($total == 0)
		{
			//insertArchiveEvaluationData($activity_id,$evaluation_id); // call for insert evalution data in archive table
			$upd ="UPDATE usaid_project_activity_evaluation set  type ='".$type."', ".$eval_type_desc_other.", management_type='".$management_type."', ".$additional_comment.", start_date='".$start_date."',end_date='".$end_date."',estimated_cost='".$estimated_cost."'  WHERE id='".$evaluation_id."'";
			$exe = $mysqli->query($upd);
			
			if(!$upd){
				$_SESSION['form_msg']['msg_type']="error";
				$_SESSION['form_msg']['msg']="Something went wrong.";		
				$mysqli->rollback();	
			}
			else{
				
				$_SESSION['form_msg']['msg_type'] ="success";
				$_SESSION['form_msg']['msg'] ="Evaluation updated sucessfully.";
				header("Location:add_activity_evaluation");
			}
		}
		else
		{
		  $_SESSION['form_msg']['msg_type'] ="error";
		  $_SESSION['form_msg']['msg'] ="Duplicate evalution at start date.";			
		}
	}
}

##==delete review==
if(isset($_REQUEST['delete_evaluation']) && isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!="" && isset($_REQUEST['evaluation_id']) && $_REQUEST['evaluation_id']!=""){
	$evaluation_id = $_REQUEST['evaluation_id'];
	$activity_id = $_REQUEST['activity_id'];
	
	$del_evaluation ="DELETE FROM usaid_project_activity_evaluation WHERE id='".$evaluation_id."' and activity_id='".$activity_id."'";
	$exe_evaluation = $mysqli->query($del_evaluation);
	if($exe_evaluation){
		$_SESSION['form_msg']['msg_type'] ="success";
		$_SESSION['form_msg']['msg'] ="Evaluation deleted sucessfully.";
		header("Location:add_activity_evaluation");		
	}
	else{
		$_SESSION['form_msg']['msg'] ="error";
		$_SESSION['form_msg']['msg'] ="Something went wrong.";
	}
}

##==edit project review==
if(isset($_REQUEST['edit_evaluation']) && isset($_REQUEST['activity_id']) && $_REQUEST['activity_id']!=""){
	$evaluation_id = $_REQUEST['evaluation_id'];
	$activity_id = $_REQUEST['activity_id'];
	$edit_mode = "edit_mode";
	
	##==get project_review_info
	$url = API_HOST_URL_PROJECT."get_all_project_activity_evaluations.php?activity_id=".$activity_id."&evaluation_id=".$evaluation_id;   
    $project_activity_evaluation_arr = requestByCURL($url);
	$type = $project_activity_evaluation_arr['data'][0]['type'];
	$management_type = $project_activity_evaluation_arr['data'][0]['management_type'];
	$evaluation_type_description_other = $project_activity_evaluation_arr['data'][0]['evaluation_type_description_other'];
	$estimated_cost = "$".number_format($project_activity_evaluation_arr['data'][0]['estimated_cost']);
	$additional_comment = $project_activity_evaluation_arr['data'][0]['additional_comment'];
	
	$start_date = $project_activity_evaluation_arr['data'][0]['start_date'];
	$start_formatted_date = str_replace("-","/",$start_date);
	$start_date_month = date('m',strtotime($start_date));
	$start_date_day = date('d',strtotime($start_date));  
	$start_date_year = date('Y',strtotime($start_date));

	$end_date = $project_activity_evaluation_arr['data'][0]['end_date'];
	$end_formatted_date = str_replace("-","/",$end_date);
	$end_date_month = date('m',strtotime($end_date));
	$end_date_day = date('d',strtotime($end_date));  
	$end_date_year = date('Y',strtotime($end_date));
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
		<div>
			<?php include('includes/activity_header.php');?>
			<div class="extra_ht"></div><div class="extra_ht"></div>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head"><?php if(!isset($edit_mode)){?>Add New Activity Evaluation<?php } else {?>Edit Evaluation<?php }?></div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="project-detail-blk">
		    <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
		   </div>
		   <br>
				<!--add review block start-->
				<div id="submission_msg" class="form-msg <?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg_type'];}?>">
					<?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg'];}?>
				</div>
				<div class="form-blk"> 
					<button type="button" class="add_blk_btn <?php if(isset($edit_mode)){?> disp-none<?php }?>" id="add_new_evaluation"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add New Evaluation</button>
					<div class="evaluation_blk <?php if(!isset($edit_mode)){?> disp-none<?php }?>">
						<div class="eval-blk-sm-head">Evaluation</div>
						<div class="eval-blk-head <?php if(!isset($edit_mode)){?> disp-none<?php }?>">
							<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div>
									Type:<br/><span class="bold"><?php echo $type;?></span>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div >
									Period Covered:<br/><span class="bold"><?php echo date('d M Y',strtotime($start_date)).' - '.date('d M Y',strtotime($end_date));?></span>
								</div>
							</div>
						</div>
						</div>
						<div class="eval-ip-blk">
							<header><h3 class="form-blk-head">Evaluation Type</h3></header>
							<form id="add_evaluation_form" method="post" autocomplete="off">
								<input type="hidden" value="<?php echo $project_id;?>" name="project_id" class="project_id"/>
								<input type="hidden" value="<?php echo $activity_id;?>" name="activity_id" class="activity_id"/>
								<div>Select evalutaion type</div>
								<div class="row threshold evaluation_type_blk">
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<div>
											<div class="div_label">
												<input type="radio" name="type" value="Impact"  class="eval_type" <?php if(isset($edit_mode) && $type=="Impact"){?> checked="checked" <?php }?>/> <label> Impact Evaluation</label></div>
											<div class="div_label">
												<input type="radio" name="type" value="Process" class="eval_type" <?php if(isset($edit_mode) && $type=="Process"){?> checked="checked" <?php }?>/> <label> Process Evaluation</label></div>
										</div>
									</div>
									<div class="col-lg-offset-1 col-lg-6 col-md-offset-1 col-md-8 col-sm-6 col-xs-12">
										<div>
											<div class="div_label">
												<input type="radio" name="type" value="Performance" class="eval_type" <?php if(isset($edit_mode) && $type=="Performance"){?> checked="checked" <?php }?>/> <label>Performance Evaluation </label>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
												<div class="div_label" style="margin-top:-10px">
													<input type="radio" name="type" value="Other" class="eval_type" <?php if(isset($edit_mode) && $type=="Other"){?> checked="checked" <?php }?>/> <label>Other</label>
												</div>
												
											</div>
											<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 <?php if((isset($edit_mode) && $type!="Other") || !isset($edit_mode)){?>disp-none<?php }?> other_type_dsc_blk">
												<div>Description of other type of Evaluation</div>
												<textarea class="form-textarea sm autoh_textarea" name="evaluation_type_description_other" rows="2"><?php echo $evaluation_type_description_other;?></textarea>
												
											</div>
										</div>
									</div>
								</div>
								<div class="extra_ht"></div><div class="extra_ht"></div>
								<div class="row mgmt_type_blk">
									<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
										<header><h3 class="form-blk-head">Management Type</h3></header>
										<div>Select management type</div>
										<div class="threshold">
												<div class="div_label">
													<input type="radio" name="management_type" value="USAID Commissioned" <?php if(isset($edit_mode) && $management_type=="USAID Commissioned"){?> checked="checked" <?php }?>/> <label>USAID Commissioned</label></div>
											
												<div class="div_label">
													<input type="radio" name="management_type" value="Partner Commissioned" <?php if(isset($edit_mode) && $management_type=="Partner Commissioned"){?> checked="checked" <?php }?>/> <label>Partner Commissioned</label></div>
											
										</div>
									</div>
								
									<div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-6 col-xs-12">
										<div class="threshold">
											<header><h3 class="form-blk-head">&nbsp;</h3></header>
											<div>Additional information about this evaluation</div>
											<textarea class="form-textarea sm autoh_textarea" name="additional_comment" rows="2"><?php if(isset($edit_mode)){ echo $additional_comment;}?></textarea>
										</div>
									</div>
								</div>
								<div class="extra_ht"></div><div class="extra_ht"></div>
								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
									<div class="calendar-blk">
										<div class="sm-head">Start Date</div>
										<table class="project_dates no-bdr">
											<tr class="head">
												<td>Month</td>
												<td>Day</td>
												<td>Year</td>
											</tr>
											<tr>
												<td><input type='text' class="form-control month date_ip only_num" value="<?php if(isset($edit_mode)){ echo $start_date_month;}?>"/></td>
												<td><input type='text' class="form-control date date_ip only_num" value="<?php if(isset($edit_mode)){ echo $start_date_day;}?>"/></td>
												<td><input type='text' class="form-control year date_ip only_num" value="<?php if(isset($edit_mode)){ echo $start_date_year;}?>"/></td>
											</tr>
										</table>
										<input type="hidden" name="evaluation_start_date" class="formatted_date evaluation_start_date" value="<?php if(isset($edit_mode)){ echo dateSpecificFormat($start_formatted_date,'m').'/'.dateSpecificFormat($start_formatted_date,'d').'/'.dateSpecificFormat($start_formatted_date,'Y');}?>"/>
									</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
									<div class="calendar-blk">
										<div class="sm-head">End Date</div>
										<table class="project_dates no-bdr">
											<tr class="head">
												<td>Month</td>
												<td>Day</td>
												<td>Year</td>
											</tr>
											<tr>
												<td><input type='text' class="form-control month date_ip only_num" value="<?php if(isset($edit_mode)){ echo $end_date_month;}?>"/></td>
												<td><input type='text' class="form-control date date_ip only_num" value="<?php if(isset($edit_mode)){ echo $end_date_day;}?>"/></td>
												<td><input type='text' class="form-control year date_ip only_num" value="<?php if(isset($edit_mode)){ echo $end_date_year;}?>"/></td>
											</tr>
										</table>
										<input type="hidden" name="evaluation_end_date" class="formatted_date evaluation_end_date" value="<?php if(isset($edit_mode)){ echo dateSpecificFormat($end_formatted_date,'m').'/'.dateSpecificFormat($end_formatted_date,'d').'/'.dateSpecificFormat($end_formatted_date,'Y');}?>"/>
									</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
									<div class="calendar-blk">
										<div class="sm-head">Cost</div>
										<div style="height:38px"></div>
										<input type="text" name="estimated_cost" class="form-control maxw_350 money_format estimated_cost" value="<?php if(isset($edit_mode)){ echo $estimated_cost;}?>"/>
									</div>
								</div>
								</div>
								<input type="hidden" name="evaluation_id" value="<?php if(isset($edit_mode)){ echo $evaluation_id;} else echo '';?>" class="evaluation_id"/>
								<input type="hidden" name="add_new_evaluation" value="add_new_evaluation"/>
								<button id="cancel_evaluation" class="usa-button-outline" type="button">Cancel Evaluation</button>
								<button id="save_evaluation" type="button"><?php if(isset($edit_mode)){ echo "Update Evaluation";} else {?> Save Evaluation<?php }?></button>
								<div class="form-msg usa-alert disp-none">
									<div class="usa-alert-body">
									<h3 class="usa-alert-heading"></h3>
									<p class="usa-alert-text"></p>
									</div>
								</div>	
								<div class="extra_ht"></div><div class="extra_ht"></div><div class="extra_ht"></div>
							</form>
						</div>
					</div>
					<?php 
						$url = API_HOST_URL_PROJECT."get_all_project_activity_evaluations.php?activity_id=".$activity_id."";
						$project_activity_evaluation_arr = requestByCURL($url);
					?>
					<div class="form-blk">
						<div class="review_listing_blk">
							<header><h3 class="form-blk-head">Activity Evaluation List</h3></header>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<table class="table table-bordered review-listing table-striped">
										<tr class="head">
											<td class="md_field">Evaluation Type</td>
											<td class="sm_field">Management Type</td>
											<td class="sm_field">Start Date</td>
											<td class="sm_field">End Date</td>
											<td class="sm_field text-center">Cost</td>
											<!--<td class="sm_field text-center">View Log</td>-->
											<td class="sm_field text-center">Action</td>
										</tr>
										<?php 
										if(count($project_activity_evaluation_arr['data'])>0){
										for($i=0;$i<count($project_activity_evaluation_arr['data']);$i++){?>
										<tr class="data_tr">
											<td><?php echo $project_activity_evaluation_arr['data'][$i]['type'];?></td>
											<td><?php echo $project_activity_evaluation_arr['data'][$i]['management_type'];?></td>
											<td><?php echo date('d M Y',strtotime($project_activity_evaluation_arr['data'][$i]['start_date']));?></td>
											<td><?php echo date('d M Y',strtotime($project_activity_evaluation_arr['data'][$i]['end_date']));?></td>
											<td>$<?php echo number_format($project_activity_evaluation_arr['data'][$i]['estimated_cost']);?></td>
											<!--<td><a href="view_project_evaluation_archive.php">View Log</a></td>-->
											<!--<td class="text-center"><?php 
												$url = API_HOST_URL_PROJECT."get_all_archive_project_activity_evalution.php?archive_evaluation_id=".$project_evaluation_arr['data'][$i]['evaluation_id']."";  
												$archive_evalution_arr = requestByCURL($url);
												if(count($archive_evalution_arr['data'])>0) { ?><a href="view_project_evaluation_archive.php?evaluation_id=<?php echo $project_evaluation_arr['data'][$i]['evaluation_id'];?>">Change Log</a> <?php } else {?><a href="javascript:void(0)">No Change Log</a> <?php } ?></td>-->
											<td class="text-center">
												
												<form class="edit_evaluation_form disp-inline" method="post">
													<input type="hidden" value="edit_evaluation" name="edit_evaluation"/>
													<input type="hidden" value="<?php echo $project_activity_evaluation_arr['data'][$i]['activity_id'];?>" name="activity_id"/>
													<input type="hidden" value="<?php echo $project_activity_evaluation_arr['data'][$i]['evaluation_id'];?>" name="evaluation_id"/>
													<a class="btn btn-warning edit_evaluation" title="Edit Evaluation"><i class="fa fa-pencil" aria-hidden="true"></i> </a> 
												</form>
												<form class="delete_evaluation_form disp-inline" method="post">
													<input type="hidden" value="delete_evaluation" name="delete_evaluation"/>
													<input type="hidden" value="<?php echo $project_activity_evaluation_arr['data'][$i]['activity_id'];?>" name="activity_id"/>
													<input type="hidden" value="<?php echo $project_activity_evaluation_arr['data'][$i]['evaluation_id'];?>" name="evaluation_id"/>
													<a class="btn btn-danger delete_evaluation" title="Delete Evaluation"><i class="fa fa-trash-o" aria-hidden="true"></i> </a>
												</form>
											</td>
										</tr>
										<?php }} else {?>
										<tr class="no_row_found">
											<td colspan="7" class="bold text-danger">No evaluation found for this activity</td>
										</tr>
										<?php }?>
									</table>
								</div>
							</div>
						</div>
					</div>
				<div class="gray-line"></div>
			</div>
	   	 </div>
		</div>
		<!--add new project start-->
	</div>
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script>
	setTimeout(function(){
		$('#submission_msg').html("");
	},10000);
	
	/*show add new evaluation blk*/
	$('#add_new_evaluation').click(function(){
		$('.evaluation_blk').removeClass('disp-none');
		$('.evaluation_blk').find('.evaluation_id').val("");
		$(this).addClass('disp-none');
	});	
	
	/*cancel evaluation*/
	$('#cancel_evaluation').click(function(){
		$('.evaluation_blk,.eval-blk-head').addClass('disp-none');
		$('#add_new_evaluation').removeClass('disp-none');
		$('.evaluation_blk').find('input[type="text"],textarea').val("");
		$('.evaluation_blk').find('input[type="text"]').removeClass("error_ip invalid_ip");
		$('.evaluation_type_blk .eval_type,.mgmt_type_blk input[name="management_type"]').closest('.select').removeClass("select");
		$('.evaluation_type_blk .eval_type,.mgmt_type_blk input[name="management_type"]').prop('checked',false);
		$('#save_evaluation').text("Save Evaluation");
		$('.tbl-content-head').text("Add New Evaluation");
		$('.evaluation_blk').find('input[name="evaluation_id"]').val();
	});
	
	/*checkbox highlight*/
	$('.threshold label').click(function(){
		$(this).closest('.threshold').find('label').removeClass('select');
		$(this).addClass('select');
	});
	
	/*save evaluation*/
	$('#save_evaluation').click(function(){
		var error="", error_msg="";
		var evaluation_type = $('.evaluation_type_blk .eval_type:checked').filter(function(){
			return $(this);
		}).length;
		
		var mgmt_type = $('.mgmt_type_blk input[name="management_type"]:checked').filter(function(){
			return $(this);
		}).length;
		
		
		/*invalid ip*/
		var invalid_ip = $('#add_evaluation_form').find('.invalid_ip').filter(function(){
			return $(this) ;
		}).length;
		
		var eval_start_date = $('#add_evaluation_form').find('.evaluation_start_date').val(); 
		var start_date_arr = new Array();
		start_date_arr = eval_start_date.split("/"); 
		
		var eval_end_date = $('#add_evaluation_form').find('.evaluation_end_date').val(); 
		var end_date_arr = new Array();
		end_date_arr = eval_end_date.split("/"); 
		
		start_date_arr[0] = start_date_arr[0]-1;
		end_date_arr[0] = end_date_arr[0]-1;
		
		var eval_start_date = new Date(start_date_arr[2],start_date_arr[0],start_date_arr[1]); 
		var eval_end_date = new Date(end_date_arr[2],end_date_arr[0],end_date_arr[1]);
		
		
		var eval_type = $('.evaluation_type_blk .eval_type:checked').val();
		var tmp = ($('.estimated_cost').val()).replace(/\,/g,'');
		var cost = parseInt(tmp.replace(/\$/g,''));
		
		if(invalid_ip>0){
			error="error";
			error_msg="Something went wrong.";		
		}
		else if(evaluation_type==0){
			error="error";
			error_msg="Please select evaluation type";	
		} 
		else if(eval_type=="Other" && $(".other_type_dsc_blk textarea").val()==""){
			error="error";
			error_msg="Please fill other description of Evaluation";
		}
		else if(mgmt_type==0){
			error="error";
			error_msg="Please select management type";	
		}
		else if(eval_start_date==""){
			error="error";
			error_msg="Start date can't be blank";
		}
		else if(eval_end_date==""){
			error="error";
			error_msg="End date can't be blank";
		}
		else if(eval_start_date>eval_end_date){
			error="error";
			error_msg="Start date can't be greater than End date"; 
		}
		else if(cost>99999){
			error="error";
			error_msg="Estimated Cost will be in 5 digit only";
		}
		else{	
			$('#add_evaluation_form').find('.form-msg').html("");
			$('#add_evaluation_form').submit();			
		}	
		
		if(error=="error"){
			$('#add_evaluation_form').find('.form-msg').removeClass('disp-none').addClass('usa-alert-error');
			$('#add_evaluation_form').find('.form-msg').find('.usa-alert-heading').text('Error');
			$('#add_evaluation_form').find('.form-msg').find('.usa-alert-text').text(error_msg);
			//$('#add_evaluation_form').find('.form-msg').addClass('error');
			//$('#add_evaluation_form').find('.form-msg').html(error_msg);
			setTimeout(function(){
				$('#add_evaluation_form').find('.form-msg').addClass('disp-none').removeClass('usa-alert-error');
			},5000);	
		}
	});
	
	/*evaluation type radio click*/
	$('.evaluation_type_blk .eval_type').click(function(){
		$(".other_type_dsc_blk textarea").val("");
		if($(this).prop("checked")==true && $(this).val()=="Other"){
			$(".other_type_dsc_blk").removeClass('disp-none');
		}
		else{
			$(".other_type_dsc_blk").addClass('disp-none');
		}
	});
	
	/*validate cost*/
	$('input[name="estimated_cost"]').keyup(function(){
		$(this).validate_ip();
	});
	
	/*delete evaluation*/
	$(document).on('click','.delete_evaluation',function(){
		if(confirm("Are you sure to delete this evaluation?")){
			$(this).closest('.delete_evaluation_form').submit();
		}
	});
	
	/*edit evaluation*/
	$(document).on('click','.edit_evaluation',function(){
		$(this).closest('.edit_evaluation_form').submit();
	});
	
</script>
<?php unset($_SESSION['form_msg']);?>
</body>
</html>
