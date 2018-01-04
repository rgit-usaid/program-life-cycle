<?php include('config/functions.inc.php');
##==validate user====
validate_user();

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
	
	$url = API_HOST_URL_PHOENIX."get_unique_obligate_fund_by_type.php?ledger_type_id=".$activity_id."&ledger_type=Project_Activity";  
	$activity_fund_strip  = requestByCURL($url);
	
	## fet all archive project activity=======================
	$url = API_HOST_URL_PROJECT."get_all_archive_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id;
	$archive_project_activity_arr = requestByCURL($url);
	
}
$page_type="activity_pages";
?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-AMP</title>
<?php include('includes/resources.php');?>

</head>
<style>
.pointer{
cursor:pointer;}
.btnstyle .back_button{
margin-bottom:20px;
}

.tablegap {
    margin: 20px;
    border: 1px solid gray;
    padding: 10px;
}
.project-detail-blk {
    min-height: 403px;
}
.project-detail-blk .form-blk {
    margin-bottom: 15px !important;
}

</style>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<div>
			<?php include('includes/activity_header.php');?>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head"><?php if($activity_id!=""){  echo "You are view archive activity details";} else echo "Add New Activity Details";?></div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="add_new_activity"><button type="button" class="btn btn-primary back_button">Back to Details</button></a>
		</div>
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
					if(count($archive_project_activity_arr['data'])>0)
					{
						for($k=0; $k<count($archive_project_activity_arr['data']); $k++)
						{  ?>
						<tr>
							<td class="text-center"><?php echo $archive_project_activity_arr['data'][$k]['modified_on']; ?></td>
							<td class="text-center comm-width"><?php echo $archive_project_activity_arr['data'][$k]['modified_by']; ?></td>
							<td class="text-center"><i class="fa fa-chevron-circle-down pointer show_table" aria-hidden="true"></i></td>
						</tr>
						<tr class="disp-none">
							<td colspan="3">
								 <div class="tablegap">
									<div class="project-detail-blk table-container">
										<form id="activity_detail_form" action="home" method="post" autocomplete="off">
											<input type="hidden" name="project_id" class="project_id" value="<?php echo $project_id;?>"/>
											<div class="form-blk">
												<div class="row project-detail-textarea">
													<div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
														<label class="lg">Title of the Activity</label>
														<textarea class="form-textarea form-control sm autoh_textarea" rows="1" name="title" disabled="disabled"><?php echo $archive_project_activity_arr['data'][$k]['title']; ?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
														<label class="lg">Activity Description</label>
														<textarea class="form-textarea form-control sm autoh_textarea" rows="2" name="activity_description" disabled="disabled"><?php echo $archive_project_activity_arr['data'][$k]['activity_description']; ?></textarea>
													</div>
												</div>
											</div>
											<div class="form-blk">	
												<div>
													<div class="row">
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div>
															<div class="sm-head">Benefitting Country</div>
															<div>&nbsp;</div>
															
															<input type="text" class="form-control sm only_string" disabled="disabled" name="activity_benefitting_country" style="max-width:300px" onKeyUp="validate()" value="<?php echo $archive_project_activity_arr['data'][$k]['activity_benefitting_country']; ?>"/>
														</div>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div class="calendar-blk">
															<div class="sm-head">Operational Planned Start Date</div>
															<table class="project_dates">
																<tr class="head">
																	<td>Month</td>
																	<td>Day</td>
																	<td>Year</td>
																</tr>
																<?php
																	$explode_date = array();
																	$planned_start_date = '';
																	if($archive_project_activity_arr['data'][$k]['planned_start_date']!='') $planned_start_date = $archive_project_activity_arr['data'][$k]['planned_start_date'];
																	
																	$explode_date = explode("/",$planned_start_date);
																	$month = trim($explode_date[0]);
																	$date = trim($explode_date[1]);
																	$year = trim($explode_date[2]);
																?> 	
																<tr>
																	<td><input type='text' class="form-control month date_ip only_num" disabled="disabled" value="<?php echo $month?>"/></td>
																	<td><input type='text' class="form-control date date_ip only_num" disabled="disabled" value="<?php echo $date?>"/></td>
																	<td><input type='text' class="form-control year date_ip only_num" disabled="disabled" value="<?php echo $year?>"/></td>
																</tr>
															</table>
															<input type="hidden" name="planned_start_date" class="formatted_date" value="<?php echo $planned_start_date;?>"/>
														</div>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div class="calendar-blk">
															<div class="sm-head">Operational Planned End Date</div>
															<table class="project_dates">
																<tr class="head">
																	<td>Month</td>
																	<td>Day</td>
																	<td>Year</td>
																</tr>
																<?php
																	$explode_date = array();
																	$planned_end_date = '';
																	if($archive_project_activity_arr['data'][$k]['planned_end_date']!='') $planned_end_date = $archive_project_activity_arr['data'][$k]['planned_end_date'];
																	
																	$explode_date = explode("/",$planned_end_date);
																	$month = trim($explode_date[0]);
																	$date = trim($explode_date[1]);
																	$year = trim($explode_date[2]);
																	
																?> 
																<tr>
																	<td><input type='text' class="form-control month date_ip only_num"  disabled="disabled"value="<?php echo $month?>"/></td>
																	<td><input type='text' class="form-control date date_ip only_num" disabled="disabled" value="<?php echo $date?>"/></td>
																	<td><input type='text' class="form-control year date_ip only_num" disabled="disabled" value="<?php echo $year?>"/></td>
																</tr>
															</table>
															<input type="hidden" name="planned_end_date" class="formatted_date" disabled="disabled"  value="<?php echo $planned_end_date;?>"/>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div class="calendar-blk">
															<div class="sm-head">Operational Actual Start Date</div>
															<table class="project_dates">
																<tr class="head">
																	<td>Month</td>
																	<td>Day</td>
																	<td>Year</td>
																</tr>
																<?php
																	$explode_date = array();
																	$actual_start_date = '';
																	if($archive_project_activity_arr['data'][$k]['actual_start_date']!='') $actual_start_date = $archive_project_activity_arr['data'][$k]['actual_start_date'];
																	
																	$explode_date = explode("/",$actual_start_date);
																	$month = trim($explode_date[0]);
																	$date = trim($explode_date[1]);
																	$year = trim($explode_date[2]);
																?> 	
																<tr>
																	<td><input type='text' class="form-control month date_ip only_num" disabled="disabled" value="<?php echo $month?>"/></td>
																	<td><input type='text' class="form-control date date_ip only_num" disabled="disabled" value="<?php echo $date?>"/></td>
																	<td><input type='text' class="form-control year date_ip only_num" disabled="disabled" value="<?php echo $year?>"/></td>
																</tr>
															</table>
															<input type="hidden" name="actual_start_date" class="formatted_date" disabled="disabled" value="<?php echo $actual_start_date;?>"/>
														</div>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div class="calendar-blk">
															<div class="sm-head">Operational Actual End Date</div>
															<table class="project_dates">
																<tr class="head">
																	<td>Month</td>
																	<td>Day</td>
																	<td>Year</td>
																</tr>
																<?php
																	$explode_date = array();
																	$actual_end_date = '';
																	if($archive_project_activity_arr['data'][$k]['actual_end_date']!='') $actual_end_date = $archive_project_activity_arr['data'][$k]['actual_end_date'];
																	
																	$explode_date = explode("/",$actual_end_date);
																	$month = trim($explode_date[0]);
																	$date = trim($explode_date[1]);
																	$year = trim($explode_date[2]);
																?> 
																<tr>
																	<td><input type='text' class="form-control month date_ip only_num" disabled="disabled" value="<?php echo $month?>"/></td>
																	<td><input type='text' class="form-control date date_ip only_num" disabled="disabled" value="<?php echo $date?>"/></td>
																	<td><input type='text' class="form-control year date_ip only_num" disabled="disabled" value="<?php echo $year?>"/></td>
																</tr>
															</table>
															<input type="hidden" name="actual_end_date" class="formatted_date" disabled="disabled" value="<?php echo $actual_end_date;?>"/>
														</div>
													</div>
												</div>
												
												</div>
											</div  class="form-blk">
											<div>
											</div>
										</form>
									<!--add new project end-->
     	 							 </div>
								  </div>
							</td>
						</tr>
				  <?php }
					 } 
					 else { ?>
							<tr>
								<td colspan="3" align="center"> No Archive List</td>
								
							</tr>
					<?php } ?>
					</tbody>
				</table>
		</div>
		<!--add new project start-->
	</div>
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
