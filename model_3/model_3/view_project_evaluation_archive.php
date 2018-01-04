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
				<div class="tbl-content-head">Project Evaluation Document Archive</div>
				<div class="clear"></div>
			</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="add_new_evaluation.php"><button type="button" class="btn btn-primary back_button">Back to Evaluation</button></a>
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
										  <div class="eval-ip-blk" >
											<header><h3 class="form-blk-head">Evaluation Type</h3></header>
											<form id="add_evaluation_form" method="post" autocomplete="off">
												<div><?php echo $archive_evalution_arr['data'][$k]['type']; ?></div>
												<div class="row threshold evaluation_type_blk">
													<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
													</div>
													<div class="col-lg-offset-1 col-lg-6 col-md-offset-1 col-md-8 col-sm-6 col-xs-12">
														<div>
														</div>
													</div>
												</div>
												<div class="extra_ht"></div><div class="extra_ht"></div>
												<div class="row mgmt_type_blk">
													<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
														<header><h3 class="form-blk-head">Management Type</h3></header>
														<div>Selected management type</div>
														<div class="threshold">
															<div class="div_label">
															 <label><?php echo $archive_evalution_arr['data'][$k]['management_type']; ?></label></div>
															</div>
													    </div>
												
													<div class="col-lg-offset-1 col-lg-4 col-md-offset-1 col-md-4 col-sm-6 col-xs-12">
														<div class="threshold">
															<header><h3 class="form-blk-head">&nbsp;</h3></header>
															<div>Additional information about this evaluation</div>
															<textarea class="form-textarea sm autoh_textarea" name="additional_comment" rows="2" disabled="disabled"><?php echo $archive_evalution_arr['data'][$k]['additional_comment']; ?></textarea>
														</div>
													</div>
												</div>
												<div class="extra_ht"></div><div class="extra_ht"></div>
												<div class="row">
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
													<div class="calendar-blk">
														<div class="sm-head">Start Date</div>
														<table class="project_dates no-bdr">
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
															<table class="project_dates no-bdr">
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
																</tbody>
															</table>
													    </div>
													 </div>
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
													 <div class="calendar-blk">
														<div class="sm-head">Cost</div>
														<div style="height:38px"></div>
														<input type="text" name="estimated_cost" class="form-control maxw_350 money_format estimated_cost" value="<?php echo $archive_evalution_arr['data'][$k]['estimated_cost']; ?>" disabled="disabled">
													</div>
												</div>
												</div>
												</div>	
												  </form>
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
