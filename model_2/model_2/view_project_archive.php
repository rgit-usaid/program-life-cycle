<?php include('config/functions.inc.php');
##==validate user====
validate_user();
###request for get single project details using project id ===========
$project_id = '';
if(isset($_REQUEST['details']))
{
	$project_id = trim($_REQUEST['project_id']);
}

if(isset($_SESSION['project_id']))
{
	$project_id = $_SESSION['project_id'];	
}
if($project_id!="")
{
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
	$project_arr = requestByCURL($url);
	
	$url = API_HOST_URL_PROJECT."api_demo.php?stage";  
	$project_stage_arr = requestByCURL($url);
		
	$url = API_HOST_URL_PHOENIX."get_all_operating_unit.php";  
	$operating_unit_arr = requestByCURL($url);
	
	$url = API_HOST_URL_PROJECT."get_all_archive_project.php?project_id=".$project_id."";  
	$archive_project_arr = requestByCURL($url);
}
$project_stage_id = '';
$environmental_threshold = '';
$gender_threshold = '';
if(isset($project_arr)) {
	$project_stage_id = $project_arr['data']['project_stage_id'];
	$environmental_threshold = $project_arr['data']['environmental_threshold'];
	$gender_threshold = $project_arr['data']['gender_threshold'];
}

?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico"/>
<link rel="stylesheet" href="<?php echo HOST_URL;?>css/vis.css">
<script src="<?php echo HOST_URL;?>/js/vis.js"></script>
<title>USAID-AMP</title>
<style>
/* custom styles for individual items, load this after vis.css */

.vis-item.green {
  background-color: greenyellow;
  border-color: green;
}

/* create a custom sized dot at the bottom of the red item */
.vis-item.red {
  background-color: red;
  border-color: darkred;
  color: white;
  font-family: monospace;
  box-shadow: 0 0 10px gray;
}
.vis-item.vis-dot.red {
  border-radius: 10px;
  border-width: 10px;
}
.vis-item.vis-line.red {
  border-width: 5px;
}
.vis-item.vis-box.red {
  border-radius: 0;
  border-width: 2px;
  font-size: 24pt;
  font-weight: bold;
}

.vis-item.orange {
  background-color: gold;
  border-color: orange;
}
.vis-item.vis-selected.orange {
  /* custom colors for selected orange items */
  background-color: orange;
  border-color: orangered;
}

.vis-item.magenta {
  background-color: magenta;
  border-color: purple;
  color: white;
}

.vis-item.light-red {
  background-color: #ff7f7f;
  border-color: #b20000;
  color: #b20000;
}

.vis-item.blue {
  background-color: #b2b2ff;
  border-color: #000;
  font-weight:bold;
}

/* our custom classes overrule the styles for selected events,
   so lets define a new style for the selected events */
.vis-item.vis-selected {
  background-color: white;
  border-color: black;
  color: black;
  box-shadow: 0 0 10px gray;
}

#visualization{box-shadow:0px 0px 4px #0080ff;}

.btnstyle .back_button{
margin-bottom:20px;
}

.tablegap {
    margin: 20px;
    border: 1px solid gray;
    padding: 10px;
}
.pointer{
cursor:pointer;}
.date_blk{
    margin-bottom:5px !important;
}
.project-detail-blk .form-blk {
    margin-bottom:1px !important;
}
.project-detail-blk .project-detail-textarea {
    margin-top: 2px !important;
}
.project-detail-blk .project-detail-textarea label.lg {
    font-size: 14px !important;
    font-weight: 600 !important;
}
.project-detail-blk h2.form-blk-head {
    font-size: 18px !important;
    font-weight: 700 !important;
}
.project-detail-blk h3.form-blk-head {
    font-size: 15px !important;
    font-weight: 600;
}
.project-detail-blk h4.form-blk-head {
    font-size:14px !important;}
.project-detail-blk .sm-head {
    font-size: 13px !important;}
</style>
<?php include('includes/resources.php');?>
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<div>
			<?php include('includes/project_header.php');?>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head"><?php if($project_id!=""){  echo "Details Change log";} else echo "Details Change log";?></div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="add_new_project"><button type="button" class="btn btn-primary back_button">Back to Details</button></a>
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
					if(count($archive_project_arr['data'])>0)
					{
						for($k=0; $k<count($archive_project_arr['data']); $k++)
						{  ?>
							<tr>
								<td class="text-center"><?php echo $archive_project_arr['data'][$k]['modified_on']; ?></td>
								<td class="text-center comm-width"><?php echo $archive_project_arr['data'][$k]['modified_by']; ?></td>
								<td class="text-center"><i class="fa fa-chevron-circle-down pointer show_table" aria-hidden="true"></i></td>
							</tr>
							<tr class="disp-none">
								<td colspan="3">
									 <div class="tablegap">
										<div class="project-detail-blk table-container">
											<form id="project_detail_form" action="home" method="post" autocomplete="off">
												<div class="form-blk">
													<header>
													   <header><h2 class="form-blk-head">Project Details</h2></header>
													</header>
													<div class="row project-detail-textarea">
														<div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
															<label class="lg">Project Title</label>
															<textarea class="form-textarea form-control sm project_title autoh_textarea" name="title" rows="1" disabled="disabled"><?php echo $archive_project_arr['data'][$k]['title']; ?></textarea>
														</div>
														<div  class="col-lg-5 col-md-4 col-sm-5 col-xs-12">
															<label class="lg">Operating Unit : <span class="normal"></span></label><br/>
															<div style="padding-top: 7px;">
														<?php echo $archive_project_arr['data'][$k]['operating_unit_id']; ?>
															</div>
														</div>
													</div>
													<div class="row project-detail-textarea">
														<div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
															<label class="lg">Project Description</label>
															<textarea class="form-textarea form-control sm autoh_textarea" name="project_purpose" rows="2" disabled="disabled"><?php echo $archive_project_arr['data'][$k]['project_purpose']; ?></textarea>
														</div>
														<?php
															$project_stage_id = '';
															if($archive_project_arr['data'][$k]['stage_name']!='')
															{
														?>
															<div  class="col-lg-5 col-md-4 col-sm-5 col-xs-12">
																<label class="lg">Project Stage</label><div style="height:5px;"></div>
																<div style="padding-top:8px;"><p><?php echo $archive_project_arr['data'][$k]['stage_name']; ?><p></div>
															</div>
													<?php 	} ?>
													</div>
												</div>
												<div class="form-blk">
													<header><h3 class="form-blk-head">Operational Details</h3></header>
													<h4 class="form-blk-head">Important dates</h4>
													<div style="height:10px"></div>
													<div>
														<div class="row">
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
															<div>
																<div class="sm-head">Design Record Create Date</div>
																<table class="project_dates">
																	<tr class="head">
																		<td>Month</td>
																		<td>Day</td>
																		<td>Year</td>
																	</tr>
																	<?php 
																		$explode_date = array();
																		$design_record_create_date = '';
																		if($archive_project_arr['data'][$k]['design_record_create_date']!='') $design_record_create_date = $archive_project_arr['data'][$k]['design_record_create_date'];
																		
																		$explode_date = explode("/",$design_record_create_date);
																		$month = trim($explode_date[0]);
																		$date = trim($explode_date[1]);
																		$year = trim($explode_date[2]);
																	?> 
																	<tr class="cr_date">
																		<td><input type='text' class="form-control month date_ip only_num" disabled="disabled" value="<?php echo $month?>"/></td>
																		<td><input type='text' class="form-control date date_ip only_num" disabled="disabled" value="<?php echo $date?>"/></td>
																		<td><input type='text' class="form-control year date_ip only_num" disabled="disabled" value="<?php echo $year?>"/></td>
																	</tr>
																</table>
																<input type="hidden" name="design_record_create_date" class="formatted_date"  value="<?php echo $design_record_create_date;?>"/>
															</div>
														</div>
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
															<div class="calendar-blk">
																<div class="sm-head">Planned Start Date</div>
																<table class="project_dates">
																	<tr class="head">
																		<td>Month</td>
																		<td>Day</td>
																		<td>Year</td>
																	</tr>
																	<?php
																		$explode_date = array();
																		$planned_start_date = '';
																		if($archive_project_arr['data'][$k]['planned_start_date']!='') $planned_start_date = $archive_project_arr['data'][$k]['planned_start_date'];
																		
																		$explode_date = explode("/",$planned_start_date);
																		$month = trim($explode_date[0]);
																		$date = trim($explode_date[1]);
																		$year = trim($explode_date[2]);
																	?> 	
																	<tr class="pls_date">
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
																<div class="sm-head">Actual Start Date</div>
																<table class="project_dates">
																	<tr class="head">
																		<td>Month</td>
																		<td>Day</td>
																		<td>Year</td>
																	</tr>
																	<?php
																		$explode_date = array();
																		$actual_start_date = '';
																		if($archive_project_arr['data'][$k]['actual_start_date']!='') $actual_start_date = $archive_project_arr['data'][$k]['actual_start_date'];
																		
																		$explode_date = explode("/",$actual_start_date);
																		$month = trim($explode_date[0]);
																		$date = trim($explode_date[1]);
																		$year = trim($explode_date[2]);
																	?> 
																	<tr class="acs_date">
																		<td><input type='text' class="form-control month date_ip only_num" disabled="disabled" value="<?php echo $month?>"/></td>
																		<td><input type='text' class="form-control date date_ip only_num" disabled="disabled" value="<?php echo $date?>"/></td>
																		<td><input type='text' class="form-control year date_ip only_num" disabled="disabled" value="<?php echo $year?>"/></td>
																	</tr>
																</table>
																<input type="hidden" name="actual_start_date" class="formatted_date" value="<?php echo $actual_start_date;?>"/>
															</div>
														</div>
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
															<div class="calendar-blk">
																<div class="sm-head">Next Review Date</div>
																<table class="project_dates">
																	<tr class="head">
																		<td>Month</td>
																		<td>Day</td>
																		<td>Year</td>
																	</tr>
																	<?php
																		$explode_date = array();
																		$next_review_date = '';
																		if($archive_project_arr['data'][$k]['next_review_date']!='') $next_review_date = $archive_project_arr['data'][$k]['next_review_date'];
																		
																		$explode_date = explode("/",$next_review_date);
																		$month = trim($explode_date[0]);
																		$date = trim($explode_date[1]);
																		$year = trim($explode_date[2]);
																	?> 
																	<tr class="nex_date"> 
																		<td><input type='text' class="form-control month date_ip only_num" disabled="disabled" value="<?php echo $month?>"/></td>
																		<td><input type='text' class="form-control date date_ip only_num" disabled="disabled" value="<?php echo $date?>"/></td>
																		<td><input type='text' class="form-control year date_ip only_num" disabled="disabled" value="<?php echo $year?>"/></td>
																	</tr>
																</table>
																<input type="hidden" name="next_review_date" class="formatted_date" value="<?php echo $next_review_date;?>"/>
															</div>
														</div>
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
															<div class="calendar-blk">
																<div class="sm-head">Planned End Date</div>
																<table class="project_dates">
																	<tr class="head">
																		<td>Month</td>
																		<td>Day</td>
																		<td>Year</td>
																	</tr>
																	<?php
																		$explode_date = array();
																		$planned_end_date = '';
																		if($archive_project_arr['data'][$k]['planned_end_date']!='') $planned_end_date = $archive_project_arr['data'][$k]['planned_end_date'];
																		
																		$explode_date = explode("/",$planned_end_date);
																		$month = trim($explode_date[0]);
																		$date = trim($explode_date[1]);
																		$year = trim($explode_date[2]);
																	?> 	
																	<tr class="ple_date">
																		<td><input type='text' class="form-control month date_ip only_num" disabled="disabled" value="<?php echo $month?>"/></td>
																		<td><input type='text' class="form-control date date_ip only_num" disabled="disabled" value="<?php echo $date?>"/></td>
																		<td><input type='text' class="form-control year date_ip only_num" disabled="disabled" value="<?php echo $year?>"/></td>
																	</tr>
																</table>
																<input type="hidden" name="planned_end_date" class="formatted_date" value="<?php echo $planned_end_date;?>"/>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="form-blk">
													<header><h3 class="form-blk-head">Objective Section</h3></header>
													<div class="row">
														<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 threshold">
															<h4 class="form-blk-head">Gender equality</h4>
																<div><?php echo $archive_project_arr['data'][$k]['gender_threshold']; ?></div>
														</div>
														<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 threshold">
															<h4 class="form-blk-head">Environment</h4>
																<div><?php echo $archive_project_arr['data'][$k]['environmental_threshold']; ?></div>
															</div>
														</div>
													</div>
												</form>
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
