<?php include('config/functions.inc.php');
##==validate user====
validate_user();
###request for get single project details using project id ===========
$project_id = '';

if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];	
}
##==get review info==
if($project_id!=""){
	##==get project_info
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);
}


## Archive evalution id for get archive details================
if($_REQUEST['evaluation_id']!='')
{
	$evaluation_id = $_REQUEST['evaluation_id'];
	$_SESSION['evaluation_id'] = $evaluation_id;
}
else
{
	$evaluation_id = $_SESSION['evaluation_id'];
}
?> 
<!DOCTYPE html>
<html>
<head>
<style>
.btnstyle .back_button{
margin-bottom:20px;
}
.pointer{
	cursor: pointer;
}
</style>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-AMP</title>
<?php include('includes/resources.php');?>
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<?php include('includes/project_header.php');?>
		<!--main container blk start-->
		<div class="tbl-block">
			<div class="tbl-caption">
				<div class="tbl-content-head"><?php if(!isset($edit_mode)){?>View Project Evaluation Details<?php } else {?>Edit Evaluation<?php }?></div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<div class="project-detail-blk">
				<!--add review block start-->
			
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="add_new_evaluation.php"><button type="button" class="btn btn-primary back_button">Back to Evaluation</button></a>
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
						$url = API_HOST_URL_PROJECT."get_all_archive_project_evalution.php?archive_evaluation_id=".$evaluation_id."";  
						$archive_evalution_arr = requestByCURL($url);
					if(count($archive_evalution_arr['data'])>0)
					{
						for($k=0; $k<count($archive_evalution_arr['data']); $k++)
						{  ?>
						<tr>
							<td class="text-center"><?php echo dateTimeFormat($archive_evalution_arr['data'][$k]['archive_on']);  ?></td>
							<td class="text-center comm-width"><?php echo $archive_evalution_arr['data'][$k]['modified_by']; ?></td>
							<td class="text-center"><i class="fa fa-chevron-circle-down pointer show_table" aria-hidden="true"></i></td>
						</tr>
						<tr class="disp-none">
							<td colspan="3">
							  <div class="tablegap">
								   <div class="evaluation_blk">
									<div class="eval-blk-sm-head">Evaluation</div>
									<div class="eval-ip-blk">
										<header><h3 class="form-blk-head">Evaluation Type</h3></header>
										<form id="add_evaluation_form" method="post" autocomplete="off">
											<input type="hidden" value="000051" name="project_id" class="project_id">
											<div><?php echo $archive_evalution_arr['data'][$k]['type']; ?></div>
											<div class="row threshold evaluation_type_blk">
												<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
												</div>
												<div class="col-lg-offset-1 col-lg-6 col-md-offset-1 col-md-8 col-sm-6 col-xs-12">
													<div class="row">
														<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 "><br>
															
														</div>
														<div class="col-lg-5 col-md-7 col-sm-12 col-xs-12 disp-none other_type_dsc_blk">
															<div>Description of other type of Evaluation</div>
															<textarea class="form-textarea form-control sm autoh_textarea" name="evaluation_type_description_other" rows="2" disabl><?php echo $archive_evalution_arr['data'][$k]['evaluation_type_description_other']; ?></textarea>
															
														</div>
													</div>
												</div>
											</div>
											<div class="row mgmt_type_blk">
												<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
													<header><h3 class="form-blk-head">Management Type</h3></header>
													<div>Selected management type</div>
													<div class="threshold">
														<label>
														 <?php echo $archive_evalution_arr['data'][$k]['management_type']; ?>
														</label><br>
													</div>
												</div>
												<div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-6 col-xs-12">
													<div class="threshold">
														<header><h3 class="form-blk-head">&nbsp;</h3></header>
														<div>Additional information about this evaluation</div>
														<textarea class="form-textarea form-control sm autoh_textarea" name="additional_comment" rows="2" disabled="disabled"><?php echo $archive_evalution_arr['data'][$k]['additional_comment']; ?></textarea>
													</div>
												</div>
											</div>
											<div class="extra_ht"></div><div class="extra_ht"></div>
											<div class="row">
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
												<div class="calendar-blk">
													<div class="sm-head">Start Date</div>
													<table class="project_dates">
														<tbody><tr class="head">
															<td>Month</td>
															<td>Day</td>
															<td>Year</td>
														</tr>
														<?php 
															$explode_date = array();
															$start_date = '';
															if($archive_evalution_arr['data'][$k]['start_date']!='') $start_date = $archive_evalution_arr['data'][$k]['start_date'];
															$explode_date = explode("/",$start_date);
															$month = trim($explode_date[0]);
															$date = trim($explode_date[1]);
															$year = trim($explode_date[2]);
															?> 
														<tr>
															<td><input type="text" class="form-control month date_ip only_num" value="<?php echo $month?>" disabled="disabled"></td>
															<td><input type="text" class="form-control date date_ip only_num" value="<?php echo $date?>" disabled="disabled"></td>
															<td><input type="text" class="form-control year date_ip only_num" value="<?php echo $year?>" disabled="disabled"></td>
														</tr>
													</tbody></table>
												</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
												<div class="calendar-blk">
													<div class="sm-head">End Date</div>
													<table class="project_dates">
														<tbody><tr class="head">
															<td>Month</td>
															<td>Day</td>
															<td>Year</td>
														</tr>
														<?php 
															$explode_date = array();
															$end_date = '';
															if($archive_evalution_arr['data'][$k]['end_date']!='') $end_date = $archive_evalution_arr['data'][$k]['end_date'];
															
															$explode_date = explode("/",$end_date);
															$month = trim($explode_date[0]);
															$date = trim($explode_date[1]);
															$year = trim($explode_date[2]);
															?> 
														<tr>
															<td><input type="text" class="form-control month date_ip only_num" value="<?php echo $month?>" disabled="disabled"></td>
															<td><input type="text" class="form-control date date_ip only_num" value="<?php echo $date?>" disabled="disabled"></td>
															<td><input type="text" class="form-control year date_ip only_num" value="<?php echo $year?>" disabled="disabled"></td>
														</tr>
													</tbody></table>
												</div>
												</div>
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
												<div class="calendar-blk">
													<div class="sm-head">Cost</div>
													<div>&nbsp;</div>
													<input type="text" name="estimated_cost" class="form-control maxw_350 only_num_with_blank estimated_cost" value="<?php echo $archive_evalution_arr['data'][$k]['estimated_cost']; ?>" disabled="disabled">
												</div>
											</div>
											</div>
										</form>
								</div>
								</div>
						      </div>
							</td>
						</tr>
						
			<?php   }	} else { ?>
						<tr>
							<td colspan="3" align="center">No Archive Data </td>
						</tr>
					<?php }?>		
						
					</tbody>
				</table>
				
	   	 </div>
	  	<!--main container blk end-->
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


<?php unset($_SESSION['form_msg']);?>
</body>
</html>
