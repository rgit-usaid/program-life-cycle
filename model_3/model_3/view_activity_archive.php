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
	
}
$page_type="activity_pages";
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
				<div class="tbl-content-head">You are viewing activity archive details</div>
				<div class="clear"></div>
			</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="add_new_activity.php"><button type="button" class="btn btn-primary back_button">Back to Activity</button></a>
		</div>
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
					## fet all archive project activity=======================
					$url = API_HOST_URL_PROJECT."get_all_archive_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id;
					$archive_project_activity_arr = requestByCURL($url);
					if(count($archive_project_activity_arr['data'])>0)
					{
						for($k=0; $k<count($archive_project_activity_arr['data']); $k++)
						{  ?>
						<tr>
							<td class="text-center"><?php echo dateTimeFormat($archive_project_activity_arr['data'][$k]['archive_on']); ?></td>
							<td class="text-center comm-width"><?php echo $archive_project_activity_arr['data'][$k]['modified_by']; ?></td>
							<td class="text-center"><i class="fa fa-chevron-circle-down pointer show_table" aria-hidden="true"></i></td>
						</tr>
						<tr class="disp-none">
							<td colspan="3">
							     <div class="tablegap">
								    <div class="project-detail-blk table-container">
										<form id="activity_detail_form" action="home" method="post" autocomplete="off">
											 <div class="form-blk">
												<header><h2 class="form-blk-head">Title</h2></header>
												<div class="row project-detail-textarea">
													<div class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
														<label>Title of the Activity</label>
														<textarea class="form-textarea sm autoh_textarea" rows="1" name="title" disabled="disabled"><?php echo $archive_project_activity_arr['data'][$k]['title']; ?></textarea>
													</div>
												</div>
												<div class="row project-detail-textarea">
													<div class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
														<label class="lg">Activity Description</label>
														<textarea class="form-textarea sm autoh_textarea" rows="2" name="activity_description" disabled="disabled"><?php echo $archive_project_activity_arr['data'][$k]['activity_description']; ?></textarea>
													</div>
												</div>
											</div>
											<div class="form-blk">	
												<div>
													<div class="row">
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div>
															<div class="sm-head">Benefitting Country</div>
															<input type="text" class="form-control sm only_string" name="activity_benefitting_country" style="max-width:300px; margin-top:53px;" onKeyUp="validate()" value="<?php echo $archive_project_activity_arr['data'][$k]['activity_benefitting_country']; ?>" disabled="disabled">
														</div>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div class="calendar-blk">
															<div class="sm-head">Operational Planned Start Date</div>
															<table class="project_dates no-bdr">
																<tbody><tr class="head">
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
																	<td><input type="text" class="form-control month date_ip only_num" value="<?php echo $month?>" disabled="disabled"></td>
																	<td><input type="text" class="form-control date date_ip only_num" value="<?php echo $date?>" disabled="disabled"></td>
																	<td><input type="text" class="form-control year date_ip only_num" value="<?php echo $year?>" disabled="disabled"></td>
																</tr>
															</tbody></table>
														</div>
													</div>
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div class="calendar-blk">
															<div class="sm-head">Operational Planned End Date</div>
															<table class="project_dates no-bdr">
																<tbody><tr class="head">
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
																	<td><input type="text" class="form-control month date_ip only_num" value="<?php echo $month?>" disabled="disabled"></td>
																	<td><input type="text" class="form-control date date_ip only_num" value="<?php echo $date?>" disabled="disabled"></td>
																	<td><input type="text" class="form-control year date_ip only_num" value="<?php echo $year?>" disabled="disabled"></td>
																</tr>
															</tbody>
														</table>
													  </div>
													</div>
												</div>
													<div class="row">
													<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
														<div class="calendar-blk">
															<div class="sm-head">Operational Actual Start Date</div>
															<table class="project_dates no-bdr">
																<tbody><tr class="head">
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
															<div class="sm-head">Operational Actual End Date</div>
																<table class="project_dates no-bdr">
																	<tbody>
																		<tr class="head">
																			<td>Month</td>
																			<td>Day</td>
																			<td>Year</td>
																		</tr>
																		 
																		<tr>
																			<td><input type="text" class="form-control month date_ip only_num" value="01" disabled="disabled"></td>
																			<td><input type="text" class="form-control date date_ip only_num" value="01" disabled="disabled"></td>
																				<td><input type="text" class="form-control year date_ip only_num" value="2022" disabled="disabled"></td>
																			</tr>
																	</tbody>
																</table>
															</div>
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
<script src="<?php echo HOST_URL?>js/add_new_project.js"></script>
<script>
/*reset form*/
$('.reset_form').click(function(){
	$(this).closest('form').find('input[type="text"],textarea,select').val("");
});
$('#save_activity').click(function(){
	var form_serialize = $('#activity_detail_form').serialize(); 
	form_serialize= form_serialize+'&add_activity=add_activity';
	
	if($('#activity_detail_form').find('.invalid_ip').length==0){
		$.ajax({
			url:'<?php echo HOST_URL?>ajaxfiles/manage_activity.php',
			type:'POST',
			data:form_serialize,
			success:function(data){
				data = JSON.parse(data);
				$('#project_detail_form').find('.formatted_date').val("");
				if(data['msg_type']=="Success"){
					if(data['mode']=="Insert"){
						window.location="list_activity";
					}
					$('.form-msg').removeClass('usa-alert-success usa-alert-error');
					$('.form-msg').addClass('usa-alert-success').removeClass('disp-none');
					$('.form-msg').find('.usa-alert-heading').text('Success');
					$('.form-msg').find('.usa-alert-text').text(data['msg']);
				}
				else{	
					$('#activity_detail_form').find('input[type="text"], textarea').val("");
					$('.form-msg').removeClass('usa-alert-success usa-alert-error');
					$('.form-msg').addClass('usa-alert-error').removeClass('disp-none');
					$('.form-msg').find('.usa-alert-heading').text('Error');
					$('.form-msg').find('.usa-alert-text').text(data['msg']);
				}
				
				setTimeout(function(){
					$('.form-msg').addClass("disp-none");
				},5000);
			}
		});
	}
	else{
		$('.form-msg').html("Something went wrong...");
		$('.form-msg').css({display:'block',color:'red'});
		$(document).scrollTop(0);
		setTimeout(function(){
			$('.form-msg').addClass("disp-none");
		},5000);
	}
});
</script>

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
