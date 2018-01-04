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
	
	//echo "<prE>";
	//print_r($activity_fund_strip);
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
				<div class="tbl-content-head"><?php if($activity_id!=""){  echo "You are editing activity details";} else echo "Add New Activity Details";?></div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="project-detail-blk table-container">
				<div class="form-msg"></div>
				<form id="activity_detail_form" action="home" method="post" autocomplete="off">
				<input type="hidden" name="project_id" class="project_id" value="<?php echo $project_id;?>"/>
				<div class="form-blk">
					<header class="row">
					    <div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
							  <h2 class="form-blk-head">Activity Details</h2>
						</div>
						<div  class="col-lg-5 col-md-4 col-sm-5 col-xs-12 text-right">
						<a href="view_activity_archive">Details change Log</a>
						</div>   
					</header>
					<div class="row project-detail-textarea">
						<div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
							<label class="lg">Title of the Activity</label>
							<textarea class="form-textarea form-control sm autoh_textarea" rows="1" name="title"><?php echo $title;?></textarea>
						</div>
					</div>
					<?php
						$activity_description = '';
						if(isset($project_arr)) $activity_description = $project_activity_arr['data']['activity_description'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
							<label class="lg">Activity Description</label>
							<textarea class="form-textarea form-control sm autoh_textarea" rows="2" name="activity_description"><?php echo $activity_description;?></textarea>
						</div>
					</div>
					<!--<div class="ip-outer">
						<input id="profile-image-upload" class="hidden" type="file">
						<div id="profile-image">click here to change profile image</div>
					</div>-->
				</div>
				<div class="form-blk">	
					<div>
						<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
							<div>
								<div class="sm-head">Benefitting Country</div>
								<div>&nbsp;</div>
								<?php
									$benefitting_country = '';
									if(isset($project_activity_arr)) $benefitting_country = $project_activity_arr['data']['activity_benefitting_country'];
								?> 
								<input type="text" class="form-control sm only_string" name="activity_benefitting_country" style="max-width:300px" onKeyUp="validate()" value="<?php echo $benefitting_country;?>"/>
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
										if(isset($project_activity_arr)) $planned_start_date = $project_activity_arr['data']['planned_start_date'];
										
										$explode_date = explode("/",$planned_start_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 	
									<tr>
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>"/></td>
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
										if(isset($project_activity_arr)) $planned_end_date = $project_activity_arr['data']['planned_end_date'];
										
										$explode_date = explode("/",$planned_end_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
										
									?> 
									<tr>
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>"/></td>
									</tr>
								</table>
								<input type="hidden" name="planned_end_date" class="formatted_date" value="<?php echo $planned_end_date;?>"/>
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
										if(isset($project_activity_arr)) $actual_start_date = $project_activity_arr['data']['actual_start_date'];
										
										$explode_date = explode("/",$actual_start_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 	
									<tr>
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>"/></td>
									</tr>
								</table>
								<input type="hidden" name="actual_start_date" class="formatted_date" value="<?php echo $actual_start_date;?>"/>
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
										if(isset($project_activity_arr)) $actual_end_date = $project_activity_arr['data']['actual_end_date'];
										
										$explode_date = explode("/",$actual_end_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 
									<tr>
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>"/></td>
									</tr>
								</table>
								<input type="hidden" name="actual_end_date" class="formatted_date" value="<?php echo $actual_end_date;?>"/>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-4 col-md-5 col-sm-7 col-xs-12">
							<div class="sm-head">Funding Mechanism</div>
							<table class="table table-striped">
								<?php 
								if(count($activity_fund_strip['data'])>0){
								for($i=0;$i<count($activity_fund_strip['data']);$i++){
									$fund_strip= $activity_fund_strip['data'][$i]['fund_code'];
									$b_year =  $activity_fund_strip['data'][$i]['fund_beginning_fiscal_year'];
									$e_year =  $activity_fund_strip['data'][$i]['fund_ending_fiscal_year'];
									$pe_id =  $activity_fund_strip['data'][$i]['program_element_id'];
									$fund_id =  $activity_fund_strip['data'][$i]['fund_id'];
									
									if($b_year!="" && $e_year!=""){
										$fund_strip= $fund_strip.' FY '.$b_year.' - '.$e_year;
									}
									
									
									/*check if closeing balance of this fund is greater than 0*/
									$url = API_HOST_URL_PHOENIX."get_openning_closing_balance.php?ledger_type_id=".$activity_id."&b_year=".$b_year."&e_year=".$e_year."&pe_id=".$pe_id."&fund_id=".$fund_id."&ledger_type=Project_Activity&fund_status=Obligate"; 
									$project_obamount_arr  = requestByCURL($url); 
									
									
									$fund_strip= $fund_strip.' '.$activity_fund_strip['data'][$i]['program_element_id'];
								?>
								<tr>
									<td><?php echo $fund_strip;?></td>
								</tr>
								<?php }} else {?>
								<tr>
									<td colspan="2" class="bold text-danger">No data found</td>
								</tr>
								<?php }?>
							</table>	
						</div>
					</div>
					</div>
				</div  class="form-blk">
				<div>
					<!--<h4 class="form-blk-head">Implementing Organisation</h4>
					<div class="row">
						<div class="col-lg-5 col-md-6 colsm-7 col-xs-12 actv_table">
							<table class="table table-striped table-bordered">
								<tr class="head"><td>Supplier ID</td><td>Supplier Name</td></tr>
								<tr><td>17147</td><td>UNDP CONTRIBUTION ACCOUNT</td></tr>
								<tr><td>17145</td><td>UNDP CONTRIBUTION ACCOUNT</td></tr>
							</table>
						</div>
					</div>-->
				</div>
				<div class="form-blk">
					<div>
						<input type="hidden" name="project_id" value="<?php echo $project_id;?>"/>
						<input type="hidden" name="activity_id" value="<?php echo $activity_id;?>"/>
						<input type="button" value="Save" class="btn btn-blue" id="save_activity"/><br/><br/>
						<a href="list_activity" style="text-decoration:underline;">Back to list</a>
					</div>
				</div>
			</form>
			<!--add new project end-->
     	  </div>
		</div>
		<!--add new project start-->
	</div>
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script src="<?php echo HOST_URL?>js/add_new_project.js"></script>
<script>
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
					$('.form-msg').html(data['msg']);
					$('.form-msg').css({display:'block',color:'green'});
				}
				else{	
					$('#activity_detail_form').find('input[type="text"], textarea').val("");
					$('.form-msg').html(data['msg']);
					$('.form-msg').css({display:'block',color:'red'});
				}
				
				setTimeout(function(){
					$('.form-msg').html("");
				},5000);
			}
		});
	}
	else{
		$('.form-msg').html("Something went wrong...");
		$('.form-msg').css({display:'block',color:'red'});
		$(document).scrollTop(0);
		setTimeout(function(){
			$('.form-msg').html("");
		},5000);
	}
});
</script>
</body>
</html>
