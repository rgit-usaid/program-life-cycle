<?php
include('config/config.inc.php');
include('include/function.inc.php'); 
$get_current_page = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']); 
## add new vendor wither thier address ============
if($_SESSION['custom_indicator_id']!='')
{
	$custom_indicator_id = $_SESSION['custom_indicator_id']; 
}
## Get operating unit ============
if($_SESSION['operating_unit_id']!='')
{
	$operating_unit_id = $_SESSION['operating_unit_id']; 
	$url = PHOENIX_API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;
	$operating_unit_arr = requestByCURL($url); 
}
else
{
	header('location:index.php');
}

$error = '';
if(isset($_REQUEST['quality_issue']))
{	
	$quality_issue = trim($_REQUEST['quality_issue']);
	$previous_month = trim($_REQUEST['previous_month']);
	$previous_day = trim($_REQUEST['previous_day']);
	$previous_year = trim($_REQUEST['previous_year']);
	 
	$future_month = trim($_REQUEST['future_month']);
	$future_day = trim($_REQUEST['future_day']);
	$future_year = trim($_REQUEST['future_year']);
	 
	$reviewer_name = $mysqli->real_escape_string(trim($_REQUEST['reviewer_name'])); 	
	$data_limitation = $mysqli->real_escape_string(trim($_REQUEST['data_limitation'])); 
	
	if($quality_issue=='save')
	{
		if($previous_month=='')
		{	
			$error = 'Previous data quality momth should not be blank';
		}
		elseif($previous_day=='')
		{
			$error = 'Previous data quality day should not be blank';
		}
		elseif($previous_year=='')
		{
			$error = 'Previous data quality year should not be blank';
		}
		elseif($reviewer_name=='')
		{
			$error = 'Name of reviewer should not be blank';
		}
	}	
	if($error=='')
	{	
			$previous_date=$future_month.'/'.$previous_day.'/'.$previous_year;
			$previous_formate_date=date('Y-m-d',strtotime($previous_date));
			
			$future_date=$previous_month.'/'.$future_day.'/'.$future_year;
			$future_formate_date=date('Y-m-d',strtotime($future_date));
			
			 
		$update_quality_issue_data = "update usaid_custom_indicator set
			data_quality_assessments_date = '".$previous_formate_date."',
			reviewer_name = '".$reviewer_name."',
			future_data_quality_assessments_date = '".$future_formate_date."',
			data_limitation = '".$data_limitation."'
			where id='".$custom_indicator_id."'"; 
		$result_data = $mysqli->query($update_quality_issue_data);
			if($result_data)
			{
				if($quality_issue=='exit')
					{
							header("location:custom_indicator_list.php");
					}
				else
					{
						header("location:indicator_changes.php");
					}
				
			}
	}
}

## get detail of indicator ===========
$custom_indicator_edit_id=$_SESSION['custom_indicator_edit_id'];
$select_data="select * from usaid_custom_indicator where id='".$custom_indicator_id."'";
$result_custom_indicator = $mysqli->query($select_data);
$fetch_custom_indicator = $result_custom_indicator->fetch_array();	

 $data_quality_assessments_date = $fetch_custom_indicator['data_quality_assessments_date']; 
 $quality_date=explode("-", $data_quality_assessments_date);
 $previous_year = $quality_date[0];
 $previous_month = $quality_date[1];
 $previous_day = $quality_date[2];
 
 $future_data_quality_assessments_date = $fetch_custom_indicator['future_data_quality_assessments_date']; 
 $future_date=explode("-", $future_data_quality_assessments_date);
 $future_year = $future_date[0];
 $future_month = $future_date[1];
 $future_day = $future_date[2];
 
$page_name="indicator_management";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>USAID</title>
	<!-- Bootstrap -->
	<link rel="shortcut icon" type="image/ico" href="images/favicon.ico"/>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css"/>
	<link href="css/sumoselect.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">	
	<link href="css/uswds.min.css" rel="stylesheet">	
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
	<!-- Create Indicator  -->
	<div class="indicator">
		<div class="container-fluid">
			<div class="header-detail">
				<div class="head-title">
					<div class="head">Operating Unit <span class="pull-right">(<a href=".">Back to Operating Unit</a>)</span></div>
				</div>
				<div class="row clear details">
					<div class="col-md-12"><a href="manage.php" class="remove-line"><?php echo $operating_unit_arr['data']['operating_unit_description'];?></a> >><a href="indicator_management.php" class="remove-line"> Indicator Management</a> >> <a href="custom_indicator_list.php" class="remove-line">Custom Indicator</a> >> <?php if($_SESSION['custom_indicator_edit_id']=='') echo " Add"; else echo "Update";  ?></div>
				</div>
			</div>
			<div id="content">
				<div class="col-md-3">
					<nav id="nav-in">
						<ul class="usa-sidenav-list">
							<li>
							<a class="<?php if($get_current_page=='custom_indicator.php') echo "usa-current"; ?>" href="custom_indicator.php"> Custom Indicator <span class="fa fa-check text-success pull-right"> </span></a>
							</li>
						<?php if($fetch_custom_indicator['precise_definition']!='') { ?>	
							<li><a class="<?php if($get_current_page=='indicator_description.php') echo "usa-current"; ?>" href="indicator_description.php">Indicator Description<span class="fa fa-check text-success pull-right"> </span></a></li>
						<?php } else { ?>
							<li><a class="<?php if($get_current_page=='indicator_description.php') echo "usa-current"; ?>">Indicator Description</a></li>
						<?php }
						 	if($fetch_custom_indicator['data_soure']!='') {  ?>	
							<li><a class="<?php if($get_current_page=='indicator_data_collection.php') echo "usa-current"; ?>" href="indicator_data_collection.php">Plan for Data Collection <span class="fa fa-check text-success pull-right"> </span></a></li>
						<?php } else { ?>
							<li><a class="<?php if($get_current_page=='indicator_data_collection.php') echo "usa-current"; ?>">Plan for Data Collection</a></li>
						<?php }
							if($fetch_custom_indicator['baseline_timeframe']!='') {  ?>		
							<li><a class="<?php if($get_current_page=='indicator_targets.php') echo "usa-current"; ?>" href="indicator_targets.php">Targets and Baseline<span class="fa fa-check text-success pull-right"> </span></a></li>
						<?php } else { ?>
							<li><a class="<?php if($get_current_page=='indicator_targets.php') echo "usa-current"; ?>">Targets and Baseline</a></li>
						<?php } 
							if($fetch_custom_indicator['reviewer_name']!='') {  ?>	
							<li><a class="<?php if($get_current_page=='indicator_data_quality.php') echo "usa-current"; ?>" href="indicator_data_quality.php">Data Quality Issues<span class="fa fa-check text-success pull-right"> </span></a></li>
						<?php } else { ?>
							<li><a class="<?php if($get_current_page=='indicator_data_quality.php') echo "usa-current"; ?>">Data Quality Issues</a></li>
						<?php } 
							if($fetch_custom_indicator['change_indicator']!='' or $fetch_custom_indicator['other_note']!='') {  ?>	
							<li><a class="<?php if($get_current_page=='indicator_changes.php') echo "usa-current"; ?>" href="indicator_changes.php">Changes to Indicator<span class="fa fa-check text-success pull-right"> </span></a></li>
						<?php } else { ?>
							<li><a class="<?php if($get_current_page=='indicator_changes.php') echo "usa-current"; ?>">Changes to Indicator</a></li>
						<?php } ?> 
						</ul>
					</nav>
				</div>
				<div class="col-md-9">
					<div class="main-form">
						<div class="container-fluid">
							<div class="heading">
								<h3> <?php if($_SESSION['custom_indicator_edit_id']=='') echo "Create to Indicator- "; else echo "Update Changes to Indicator-";  ?>  <?php if($fetch_custom_indicator['name_indicator']!='') echo ucfirst($fetch_custom_indicator['name_indicator']); ?></h3>
							</div>
							<div class="form-indicator">
								<form class="form-horizontal" role="form" method="post" action="">
									<label for="input-type-textarea" style="max-width: 60rem">Dates of Previous Data Quality Assessments & Name of Reviewer</label>						
									<div class="usa-date-of-birth">
										<div class="usa-form-group usa-form-group-month">
											<input class="usa-input-inline" placeholder="MM" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_1" name="previous_month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="<?php echo $previous_month; ?>">
										</div>
										<div class="usa-form-group usa-form-group-day">
											<input class="usa-input-inline" placeholder="DD" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_2" name="previous_day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="<?php echo $previous_day; ?>">
										</div>
										<div class="usa-form-group usa-form-group-year">
											<input class="usa-input-inline" placeholder="YYYY" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_3" name="previous_year" pattern="[0-9]{4}" type="number" min="1900" max="3000" value="<?php echo $previous_year; ?>">
										</div>
									</div><br><br><br>
									<?php if($fetch_custom_indicator!='') $reviewer_name= $fetch_custom_indicator['reviewer_name']; ?>	
									<input id="input-type-text" name="reviewer_name" placeholder="Enter the Name of Reviewer" value="<?php echo $reviewer_name; ?>" type="text">

									<label for="input-type-textarea">Date of Future Data Quality Assessments<span style="margin-left: 10px; font-size: 14px; color: #ccc; font-style: italic;">(Optional)</span></label>						
									<div class="usa-date-of-birth">
										<div class="usa-form-group usa-form-group-month">
											<input class="usa-input-inline" placeholder="MM" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_1" name="future_month" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="<?php echo $future_month; ?>">
										</div>
										<div class="usa-form-group usa-form-group-day">
											<input class="usa-input-inline" placeholder="DD" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_2" name="future_day" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="<?php echo $future_day; ?>">
										</div>
										<div class="usa-form-group usa-form-group-year">
											<input class="usa-input-inline" placeholder="YYYY" aria-describedby="dobHint" class="usa-form-control" id="date_of_birth_3" name="future_year" pattern="[0-9]{4}" type="number" min="1900" max="3000" value="<?php echo $future_year; ?>">
										</div>
									</div>
									<br><br>
									<label for="input-type-textarea">Known Data Limitations</label>
									<?php if($fetch_custom_indicator!='') $data_limitation= $fetch_custom_indicator['data_limitation']; ?>							
									<input id="input-type-text" name="data_limitation" value="<?php echo $data_limitation; ?>" type="text">

									<div class="button_wrapper clear">
										<button class="usa-button-outline" type="reset">Cancel</button>
										<button type="submit" name="quality_issue" style="display:inline" value="save">Save & Proceed</button>
										<button type="submit" name="quality_issue" style="display:inline" value="exit">Save & Exit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Include all compiled plugins (below), or include individual files as needed -->

		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/main.js"></script>
		
	</body>
	</html>
