<?php include('config/functions.inc.php');
##==validate user====
validate_user();
###request for get single project details using project id ===========
$project_id = '';
if(isset($_REQUEST['details']))
{
	$project_id = trim($_REQUEST['project_id']);
}

if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];	
}

if($project_id!="")
{
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);

    $url = API_HOST_URL_PROJECT."api_demo.php?stage";  
  	$project_stage_arr = requestByCURL($url);
	$project_owner_id = $project_arr['data']['employee_id']; 
	
	$empinfo_url = API_HOST_URL_PROJECT."get_hr_employee.php?employee_id=".$project_owner_id;  
	$empinfo_arr = requestByCURL($empinfo_url);
	$project_stage_id = $project_arr['data']['project_stage_id'];

	$project_act_url = API_HOST_URL_PROJECT."get_all_project_activity.php?project_id=".$project_id;  
	$project_activity_arr = requestByCURL($project_act_url);

}
### get all archive of project appraisal============
$url = API_HOST_URL_PROJECT."get_archive_project.php?project_id=".$project_id."";  
$project_archive_arr = requestByCURL($url);

##==flag of stage document form 
$flag_stage_doc = 0;
if($project_stage_id>2){ ##==if project stage is fall in group '2' than show appraisal document
	$flag_stage_doc = 1;
}

##==if project design plan in view only mode==
if(isset($_REQUEST['view_only_mode'])){
	$flag_stage_doc = $_REQUEST['view_only_mode'];
}

?> 
<!DOCTYPE html>
<html>
<head>
<style>
.btnstyle .back_button{
margin-bottom:20px;
}
.project-detail-blk .form-blk {
    margin-bottom:10px !important;
}
.tablegap{
border:1px solid #a9a9a9;
margin:5px;
padding:5px;}
</style>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico"/>
<link rel="stylesheet" href="<?php echo HOST_URL;?>css/vis.css">
<script src="<?php echo HOST_URL;?>/js/vis.js"></script>
<title><?php echo TITLE;?></title>
<?php include('includes/resources.php');?>
</head>
<body class="page-ui-components">
	<!-- Modal -->
  	 <?php include('includes/project_detail_popup.php');?>
	
	<link href="<?php echo HOST_URL;?>css/timeline.css" type="text/css" rel="stylesheet"/>	
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<div>
			<?php include('includes/project_header.php');?>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head">Project Appraisal Document Archive</div>
				<div class="clear"></div>
			</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="add_new_project.php"><button type="button" class="btn btn-primary back_button">Back to Project Apppraisal</button></a>
		</div>
		        <div class="clearfix"></div>
			<!--add new project start-->
		
				<table id="projects_table" class="display table table-bordered table-striped" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th class="text-center comm-width">Archive Date</th>
						<th class="text-center comm-width">Archive By</th>
						<th class="text-center comm-width">View</th>
					</tr>
					</thead>
					<tbody>
					<?php
					if(count($project_archive_arr['data'])>0)
					{
						for($i=0; $i<count($project_archive_arr['data']); $i++)
						{
					?>	
							<tr>
								<td><?php echo dateTimeFormat($project_archive_arr['data'][$i]['archive_on']);?></td>
								<td class="text-center comm-width"><?php echo $project_archive_arr['data'][$i]['archive_by'];?></td>
								<td class="text-center text-center"><i class="fa fa-chevron-circle-down pointer show_table" aria-hidden="true"></i></td>
							</tr>
							<tr class="disp-none">
								<td colspan="3">
								     <div class="tablegap">
									    <div class="project-detail-blk table-container">
											<div class="form-blk">
												<form id="project_detail_form" action="home" method="post" autocomplete="off">				
												<div class="row project-detail-textarea">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label>Title</label>
														<textarea class="form-textarea sm project_title autoh_textarea" name="title" rows="1" placeholder="Project Title" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['title'];?></textarea>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="pull-right">
														<label>Project Stage</label><br>
														<select class="form-control" name="project_stage_id" disabled="disabled">
															<option value="3" selected="selected"><?php echo $project_archive_arr['data'][$i]['stage_name'];?></option>
														 </select>
														</div>
														<div class="clear"></div>
													 </div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Purpose</label>
														<textarea class="form-textarea sm project_title autoh_textarea project_purpose" name="project_purpose" rows="1" placeholder="Purpose" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['project_purpose'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Description</label>
														<textarea class="form-textarea sm autoh_textarea project_description" name="project_description" rows="2" placeholder="Project Description" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['project_description'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">	
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Context</label>
														<textarea class="form-textarea sm autoh_textarea " name="context" rows="2" placeholder="Project Context" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['context'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Other Levaraged Resources</label>
														<textarea class="form-textarea sm autoh_textarea" name="leveraged_resources" rows="2" placeholder="Project Other Levaraged Resources" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['leveraged_resources'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Summary of Conclusions and Analyses</label>
														<textarea class="form-textarea sm autoh_textarea" name="conclusions_and_analyses_summary" rows="2" placeholder="Summary of Conclusions and Analyses" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['conclusions_and_analyses_summary'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Management Plan</label>
														<textarea class="form-textarea sm autoh_textarea" name="management_plan" rows="2" placeholder="Management Plan" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['management_plan'];?></textarea>
													</div>
												</div>
																	<div class="row project-detail-textarea">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Financial Plan</label>
														<textarea class="form-textarea sm autoh_textarea" name="financial_plan" rows="2" placeholder="Financial Plan" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['financial_plan'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Monitoring, Evaluation and Learning Plan</label>
														<textarea class="form-textarea sm autoh_textarea" name="monitoring_evaluation_and_learning_plan" rows="2" placeholder="Project Summary of Conclusions And Analyses" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['monitoring_evaluation_and_learning_plan'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Activity Plan</label>
														<textarea class="form-textarea sm autoh_textarea" name="activity_plan" rows="2" placeholder="Is this already addressed in the Activity tab?" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['activity_plan'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Logic Model, AUPGS(for G2G) and Other Required Annexes</label>
														<textarea class="form-textarea sm autoh_textarea" name="logical_framework_discretion" rows="2" placeholder="Artifacts" disabled="disabled"><?php echo $project_archive_arr['data'][$i]['logical_framework_discretion'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div class="calendar-blk">
															<div class="sm-head">Planned Start Date</div>
															<table class="project_dates no-bdr">
															  <tbody>
																<tr class="head">
																	<td>Month</td>
																	<td>Day</td>
																	<td>Year</td>
																</tr>	
																<tr class="pls_date">
																<?php
																	$explode_date = array();
																	$planned_start_date = '';
																	if(isset($project_archive_arr)) $planned_start_date = $project_archive_arr['data'][$i]['planned_start_date'];
																	
																	$explode_date = explode("/",$planned_start_date);
																	$month = trim($explode_date[0]);
																	$date = trim($explode_date[1]);
																	$year = trim($explode_date[2]);
																?>
																	<td><input type="text" class="form-control month date_ip only_num" value="<?php echo $month;?>" placeholder="MM" disabled="disabled"></td>
																	<td><input type="text" class="form-control date date_ip only_num" value="<?php echo $date;?>" placeholder="DD" disabled="disabled"></td>
																	<td><input type="text" class="form-control year date_ip only_num" value="<?php echo $year;?>" placeholder="YYYY" disabled="disabled"></td>
																  </tr>
																</tbody>
															</table>
														</div>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div class="calendar-blk">
															<div class="sm-head">Planned End Date</div>
															<table class="project_dates no-bdr">
																<tbody>
																  <tr class="head">
																		<td>Month</td>
																		<td>Day</td>
																		<td>Year</td>
																  </tr> 
																 <tr class="ple_date">
																 <?php
																	$explode_date = array();
																	$planned_end_date = '';
																	if(isset($project_archive_arr)) $planned_end_date = $project_archive_arr['data'][$i]['planned_end_date'];
																	
																	$explode_date = explode("/",$planned_end_date);
																	$month = trim($explode_date[0]);
																	$date = trim($explode_date[1]);
																	$year = trim($explode_date[2]);
																?> 	
																	<td><input type="text" class="form-control month date_ip only_num" value="<?php echo $month;?>" placeholder="MM" disabled="disabled"></td>
																	<td><input type="text" class="form-control date date_ip only_num" value="<?php echo $date;?>" placeholder="DD" disabled="disabled"></td>
																	<td><input type="text" class="form-control year date_ip only_num" value="<?php echo $year;?>" placeholder="YYYY" disabled="disabled"></td>
																</tr>
															   </tbody>
															</table>
														</div>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div class="calendar-blk">
															<div class="sm-head">Project Review Date</div>
															<table class="project_dates no-bdr">
																<tbody>
																  <tr class="head">
																	<td>Month</td>
																	<td>Day</td>
																	<td>Year</td>
																</tr>
																<tr class="nex_date">
																<?php
																	$explode_date = array();
																	$next_review_date = '';
																	if(isset($project_archive_arr)) $next_review_date = $project_archive_arr['data'][$i]['next_review_date'];
																	
																	$explode_date = explode("/",$next_review_date);
																	$month = trim($explode_date[0]);
																	$date = trim($explode_date[1]);
																	$year = trim($explode_date[2]);
																?>  
																	<td><input type="text" class="form-control month date_ip only_num" value="<?php echo $month;?>" placeholder="MM" disabled="disabled"></td>
																	<td><input type="text" class="form-control date date_ip only_num" value="<?php echo $date;?>" placeholder="DD" disabled="disabled"></td>
																	<td><input type="text" class="form-control year date_ip only_num" value="<?php echo $year;?>" placeholder="YYYY" disabled="disabled"></td>
																</tr>
															  </tbody>
														    </table>
														
														</div>
													</div>
												</div>
												</form>
											</div>
					                  </div>
									  </div>
								</td>
							</tr> 
					<?php
						}
					}
					else{
						echo '<tr><td colspan="3">No Archive</td></tr>';
					}
					?> 
					</tbody>
				</table> 
		
			<!--add new project end-->
     	  </div>
		</div>
		<!--add new project start-->
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
 <script>
$(document).ready(function(){
	$('.show_table').click(function() {
	$(this).closest("tr").next().toggleClass("disp-none");
    if ($(this).hasClass('fa-chevron-circle-down')){
        $(this).removeClass('fa-chevron-circle-down').addClass('fa-chevron-circle-up');
    }
	 else {
         $(this).addClass('fa-chevron-circle-down').removeClass('fa-chevron-circle-up');
      }
});
});
</script>
</body> 
</html>
