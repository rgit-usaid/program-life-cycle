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
}

## Archive project monitoring id for get archive details================
if($_REQUEST['project_monitoring_id']!='')
{
	$project_monitoring_id = $_REQUEST['project_monitoring_id'];
	$_SESSION['project_monitoring_id'] = $project_monitoring_id;
}
else
{
	$project_monitoring_id = $_SESSION['project_monitoring_id'];
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
padding:10px;
box-shadow:0px 0px 5px #a9a9a9;}
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
				<div class="tbl-content-head">Project Monitoring Document Archive</div>
				<div class="clear"></div>
			</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="add_project_review.php"><button type="button" class="btn btn-primary back_button">Back to Monitoring</button></a>
		</div>
		        <div class="clearfix"></div>
			<!--add new project start-->
		
				<table id="projects_table" class="display table table-bordered table-striped" cellspacing="0" width="100%">
					<thead>
						<th class="text-center comm-width">Archive Date</th>
						<th class="text-center comm-width">Archive By</th>
						<th class="text-center comm-width">View</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$url = API_HOST_URL_PROJECT."get_all_archive_review.php?project_monitoring_id=".$project_monitoring_id."";  
					$archive_review_arr = requestByCURL($url);
					if(count($archive_review_arr['data'])>0)
					{
						for($k=0; $k<count($archive_review_arr['data']); $k++)
						{  ?>
					<tr>
						<tr>
							<td class="text-center"><?php echo dateTimeFormat($archive_review_arr['data'][$k]['archive_on']); ?></td>
							<td class="text-center comm-width"><?php echo $archive_review_arr['data'][$k]['modified_by']; ?></td>
							<td class="text-center"><i class="fa fa-chevron-circle-down pointer show_table" aria-hidden="true"></i></td>
						</tr>
						<tr class="disp-none">
							<td colspan="3">
							     <div class="tablegap">
								    <div class="project-detail-blk table-container">
				                        <div class="form-blk">
											<header><h3 id="add_review_blk_header" class="form-blk-head">Edited Review</h3></header><br>
											<form id="add_review_form" method="post" autocomplete="off">
											<div class="add_review_blk">
											<div class="row review_row">
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 review_radio_blk">
													<div class="threshold">
														<div class="sm-head">Type of Review</div>
														<div style="height:40px"></div>
														<select class="form-control" id="sel_review_type" name="review_type" disabled="disabled">
															<option value="Annual Review" selected="selected">Annual Review</option>
														</select>
														</div>
														</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk review_ip_blk">
													<div class="calendar-blk">
														<div class="sm-head">Review Due Date</div>
														<table class="project_dates no-bdr">
															<tbody><tr class="head">
																<td>Month</td>
																<td>Day</td>
																<td>Year</td>
															</tr>
														<?php 
														$explode_date = array();
														$review_due_date = '';
														if($archive_review_arr['data'][$k]['review_due_date']!='') $review_due_date = $archive_review_arr['data'][$k]['review_due_date'];
														$explode_date = explode("/",$review_due_date);
														$month = trim($explode_date[0]);
														$date = trim($explode_date[1]);
														$year = trim($explode_date[2]);
														?> 		
															<tr>
																<td><input type="text" class="form-control month date_ip comp_date only_num" value="<?php echo $month; ?>" disabled="disabled"></td>
																<td><input type="text" class="form-control date date_ip comp_date only_num" value="<?php echo $date; ?>" disabled="disabled"></td>
																<td><input type="text" class="form-control year date_ip comp_date only_num" value="<?php echo $year; ?>" disabled="disabled"></td>
															</tr>
														</tbody></table>
																					
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk review_ip_blk">
													<div class="calendar-blk">
														<div class="sm-head">Review Prompt Date</div>
														<table class="project_dates no-bdr">
															<tbody><tr class="head">
																<td>Month</td>
																<td>Day</td>
																<td>Year</td>
															</tr>
														<?php 
														$explode_date = array();
														$review_prompt_date = '';
														if($archive_review_arr['data'][$k]['review_prompt_date']!='') $review_prompt_date = $archive_review_arr['data'][$k]['review_prompt_date'];
														$explode_date = explode("/",$review_prompt_date);
														$month = trim($explode_date[0]);
														$date = trim($explode_date[1]);
														$year = trim($explode_date[2]);
														?> 	
															<tr>
																<td><input type="text" class="form-control month date_ip comp_date only_num" value="<?php echo $month; ?>" disabled="disabled"></td>
																<td><input type="text" class="form-control date date_ip comp_date only_num" value="<?php echo $date; ?>" disabled="disabled"></td>
																<td><input type="text" class="form-control year date_ip comp_date only_num" value="<?php echo $year; ?>" disabled="disabled"></td>
															</tr>
														</tbody></table>
														<input type="hidden" name="review_prompt_date" class="formatted_date" value="">
													</div>
												</div>
											</div>
											<div class="row review_row">
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk review_ip_blk">
													<div>
														<div class="sm-head">Overall Score</div>
														<div style="height:40px"></div>
														<input type="text" name="review_overall_score" class="form-control sm-ip" value="<?php echo $archive_review_arr['data'][$k]['overall_score']; ?>" disabled="disabled">								
													</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk review_ip_blk">
													<div class="calendar-blk">
														<div class="sm-head">Actual Review Date</div>
														<table class="project_dates no-bdr">
															<tbody><tr class="head">
																<td>Month</td>
																<td>Day</td>
																<td>Year</td>
															</tr>
														<?php 
														$explode_date = array();
														$actual_review_date = '';
														if($archive_review_arr['data'][$k]['actual_review_date']!='') $actual_review_date = $archive_review_arr['data'][$k]['actual_review_date'];
														$explode_date = explode("/",$actual_review_date);
														$month = trim($explode_date[0]);
														$date = trim($explode_date[1]);
														$year = trim($explode_date[2]);
														?> 			
															<tr>
																<td><input type="text" class="form-control month date_ip only_num can_be_blank" value="<?php echo $month; ?>" disabled="disabled"></td>
																<td><input type="text" class="form-control date date_ip only_num can_be_blank" value="<?php echo $date; ?>"disabled="disabled"></td>
																<td><input type="text" class="form-control year date_ip only_num can_be_blank" value="<?php echo $year; ?>"disabled="disabled"></td>
															</tr>
														</tbody></table>
																	
													</div>
												</div>
											</div>
											<!---add output scoring block start-->
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 review_ip_blk">
													<div class="form-blk">
														<header><h3 class="sm-head">Output Scoring</h3></header>
														<div class="add_output_scoring_blk">
															<table id="review_output_score_tbl" class="table table-bordered table-striped">
																<tbody>
																  <tr class="head">
																	<td>Description</td>
																	<td class="sm_field">Impact Weight (%)</td>
																	<td class="sm_field">Perfomance</td>
																	<td class="sm_field">Risk</td>
																 </tr>
														<?php
														$url = API_HOST_URL_PROJECT."get_all_archive_project_review_output_score.php?archive_project_monitoring_id=".$archive_review_arr['data'][$k]['id']."";  
														$archive_review_output_score_arr = requestByCURL($url);
														if(count($archive_review_output_score_arr['data'])>0)
														{
															for($i=0; $i<count($archive_review_output_score_arr['data']); $i++)
															{  ?>
																<tr class="output_score_row">
																	<td><?php echo $archive_review_output_score_arr['data'][$i]['project_output_score_description']; ?></td>
																	<td><?php echo $archive_review_output_score_arr['data'][$i]['project_output_impact_weight']; ?>%</td>
																	<td><?php echo $archive_review_output_score_arr['data'][$i]['project_output_performance']; ?></td>
																	<td><?php echo $archive_review_output_score_arr['data'][$i]['project_output_risk']; ?></td>
																</tr>
													  <?php } 
														} else { ?>
																<tr class="output_score_row">
																	<td colspan="4" align="center">No Data Available</td>
																</tr>
													<?php } ?>		
																
																</tbody>
																</table>	
														</div>
													</div>
												</div>	
											</div>
											<!--add output scoring block end-->
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 review_ip_blk">
													<div class="form-blk">
														<header><h3 class="sm-head">Documents</h3></header>
														<div class="row">
															<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
																<div class="review_doc_lblk_out">
																	<header><h2 class="form-blk-head">Submission</h2></header>
																	<header><h2 class="sm-head">Submission Comments</h2></header>
																	<textarea class="form-control autoh_textarea" name="annual_review_submission_comments" rows="2" disabled="disabled"><?php echo $archive_review_arr['data'][$k]['annual_review_submission_comments']; ?></textarea>
																</div>
															</div>
															<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
																<div class="review_doc_rblk_out">
																	<header><h3 class="form-blk-head">Authorization</h3></header>
																	<header><h2 class="sm-head">Comments</h2></header>
																	<textarea class="autoh_textarea" name="annual_review_approver_comments" rows="2" disabled="disabled"><?php echo $archive_review_arr['data'][$k]['annual_review_approver_comments']; ?></textarea>
																	<div class="extra_ht"></div><div class="extra_ht"></div>
																	<div class="threshold">
														<div class="div_label">
														<input type="radio" name="annual_review_approval" value="Approve" class="review_req project_status"<?php if($archive_review_arr['data'][$k]['annual_review_approval']=='Approve') echo 'checked="checked"'; ?>> <label> Approve </label> </div>
														<div style="height:10px;"></div>
														<div class="div_label">	
														<input type="radio" name="annual_review_approval" value="Reject" class="review_req project_status"<?php if($archive_review_arr['data'][$k]['annual_review_approval']=='Reject') echo 'checked="checked"'; ?>> <label> Reject </label>
														</div>
													
													<div class="not_now "><a id="not_now_review_flag">Not Now Approve or Reject</a></div>
												</div>
													<div class="approver-blk disp-none">
													<div class="extra_ht"></div>
													<header><h2 class="sm-head">Approver</h2></header>
													<input type="text" class="form-control annual_review_approver" name="annual_review_approver" value="">
												</div>									
																</div>
															</div>
														</div>
													</div>
												</div>
					
											</div>
											
										</div>
										</form>
											<div class="gray-line"></div>
										</div>
				<!--review display only block end-->
			                        </div>
								  </div>
							</td>
						</tr> 
				<?php   }	
					} else { ?>
							<tr>
								<td colspan="3" align="center">No Archive Data </td>
							</tr>
						<?php }?>	
							
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
$('#not_now_review_flag').click(function(){
		$('.add_review_blk').find('input[name="annual_review_approval"]').prop("checked","");
		$('.approver-blk').find('input[type="text"]').val("");
		$('.approver-blk').addClass('disp-none');
	});
});
</script>
</body> 
</html>
