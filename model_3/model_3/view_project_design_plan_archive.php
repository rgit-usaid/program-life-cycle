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
### get all archive of project desgin plan============
$url = API_HOST_URL_PROJECT."get_archive_project.php?project_id=".$project_id."";  
$project_archive_arr = requestByCURL($url);
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
				<div class="tbl-content-head">Project Design Plan Archive</div>
				<div class="clear"></div>
			</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="add_new_project.php"><button type="button" class="btn btn-primary back_button">Back to Project Design plan</button></a>
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
							$url = API_HOST_URL_PHOENIX."get_operating_unit_by_id.php?operating_unit_id=".$project_archive_arr['data'][$i]['originating_operating_unit_id']."";  
						  	$ou_arr = requestByCURL($url);
							$originating_operating_unit = $ou_arr['data']['operating_unit_description'];
							
							$url = API_HOST_URL_PHOENIX."get_operating_unit_by_id.php?operating_unit_id=".$project_archive_arr['data'][$i]['implementing_operating_unit_id']."";  
						  	$ou_arr = requestByCURL($url);
							$implementing_operating_unit = $ou_arr['data']['operating_unit_description'];
							 
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
												<div class="row project-detail-textarea">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<h2 class="form-blk-head" style="margin-top:0px">Project Design Plan</h2>
														<div>Activites scheduled for concurrent design are located on the Activtiy Tab</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="pull-right">
														<label>Project Stage</label><br>
															<input type="text" value="<?php echo $project_archive_arr['data'][$i]['stage_name'];?>" disabled="disabled">
														</div>
														<div class="clear"></div>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<label>Project Title</label>
														<textarea class="form-textarea  sm project_title autoh_textarea" name="title" rows="1" placeholder="Project Title" disabled="" style="height: 74px;"><?php echo $project_archive_arr['data'][$i]['title'];?></textarea>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="pull-right">
														   <label>Originating Operating Unit : </label><br>
															 <div class="search_div" style="position:relative">
																<input type="text" name="originating_operating_unit" class="search_txt form-control" autocomplete="off" onkeyup="search_val(this,'<?php echo API_HOST_URL_PHOENIX;?>get_all_operating_unit.php')" value="<?php echo $originating_operating_unit;?>" disabled="">
																 
																<div class="ajax_data"></div>
															</div>
														</div>
														<div class="clear"></div>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="validate_ip_par">
															<label>Estimated of Total USAID Project Funding Needed</label>
															<input type="text" class="maxw_350 money_format amount_type" name="estimated_total_funding_amount" placeholder="Estimated of Total USAID Project Funding Needed" disabled="" value="$<?php echo number_format($project_archive_arr['data'][$i]['estimated_total_funding_amount']);?>">
														</div>
													</div>
													<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
														<div class="pull-right">
														<label>Implementing Operating Unit : </label><br>
															<div class="search_div" style="position:relative">
																<input type="text" name="implementing_operating_unit" class="search_txt form-control" autocomplete="off" onkeyup="search_val(this,'<?php echo API_HOST_URL_PHOENIX;?>get_all_operating_unit.php')" value="<?php echo $implementing_operating_unit;?>" disabled=""> 
																<div class="ajax_data"></div>
															</div>
														</div>
														<div class="clear"></div>
													</div>
												</div>
												<div class="row project-detail-textarea">
												  	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Project Purpose</label>
														<textarea class="form-textarea sm project_purpose autoh_textarea" name="project_purpose" rows="2" placeholder="Project Purpose" disabled="" style="height: 74px;"><?php echo $project_archive_arr['data'][$i]['project_purpose'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
									              	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											            <label>Plan for Engaging Local Actors</label>
											               <textarea class="form-textarea sm autoh_textarea" name="engaging_local_actor_plan" rows="2" placeholder="Plan for Engaging Local Actors" disabled="" style="height: 74px;"><?php echo $project_archive_arr['data'][$i]['engaging_local_actor_plan'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
									              	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Plan for Conducting Analyses</label>
														<textarea class="form-textarea sm autoh_textarea" name="conducting_analyses_plan" rows="2" placeholder="Plan for Conducting Analyses" disabled="" style="height: 74px;"><?php echo $project_archive_arr['data'][$i]['conducting_analyses_plan'];?></textarea>
												   	</div>
											    </div>
												<div class="row project-detail-textarea">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Plan for Possible Use of Govt to Govt</label>
														<textarea class="form-textarea sm autoh_textarea" name="use_of_govt_to_govt_plan" rows="2" placeholder="Plan for Possible Use of Govt to Govt" disabled="" style="height: 74px;"><?php echo $project_archive_arr['data'][$i]['use_of_govt_to_govt_plan'];?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
														<label>Proposed Design Schedule</label><br>
														<input type="text" class="maxw_350   " id="smart_sheet_ip" value="<?php echo $project_archive_arr['data'][$i]['proposed_design_schedule'];?>" name="proposed_design_schedule" placeholder="Proposed Design Schedule" disabled=""> 
													</div>
													<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
														<div class="validate_ip_par">
														<label>Proposed Design Cost</label><br>
														<input type="text" class=" maxw_350 money_format amount_type" value="<?php echo $project_archive_arr['data'][$i]['proposed_design_cost'];?>" name="proposed_design_cost" placeholder="Proposed Design Cost" disabled="">
														</div>
													</div>	
												</div>
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
