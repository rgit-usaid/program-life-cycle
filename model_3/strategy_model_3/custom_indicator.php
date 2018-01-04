<?php
include('config/config.inc.php');
include('include/function.inc.php');
## get current page ==========
$get_current_page = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']); 

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

if($_REQUEST['indicator']=='new')
{
	unset($_SESSION['custom_indicator_edit_id']);
	unset($_SESSION['custom_indicator_id']); 
	
}
$date= date("Y,m,d"); 

if(isset($_REQUEST['custom_indicator_edit_id']))
{
	$custom_indicator_edit_id = $_REQUEST['custom_indicator_edit_id']; 
	$_SESSION['custom_indicator_edit_id'] = $custom_indicator_edit_id;
	$custom_indicator_edit_id = $_SESSION['custom_indicator_edit_id']; 
	$_SESSION['custom_indicator_id'] = $custom_indicator_edit_id; 
}
else
{
	$custom_indicator_edit_id = $_SESSION['custom_indicator_id'];
}

## Add and update custom indicator ================
$error = '';
if(isset($_REQUEST['custom_indicator']))
{	
	$custom_indicator = trim($_REQUEST['custom_indicator']); 
	$name_indicator = $mysqli->real_escape_string(trim($_REQUEST['name_indicator'])); 
	$result_measured = $mysqli->real_escape_string(trim($_REQUEST['result_measured'])); 
	$PPR_indicator = trim($_REQUEST['PPR_indicator']); 
	$reported_year = trim($_REQUEST['reported_year']); 
	
	if($name_indicator=='')
	{
		$error = 'Indicator name should not be blank';
	}
	if($operating_unit_id=='')
	{
		$error = 'Please select operating unit again';
	}
	
	if($error=='')
	{	
		if($custom_indicator_edit_id=='')
		{
			 $insert_custom_indicator_data = "insert into usaid_custom_indicator set
				operating_unit_id = '".$operating_unit_id."',
				name_indicator = '".$name_indicator."',
				result_measured = '".$result_measured."',
				PPR_indicator = '".$PPR_indicator."',
				reported_year = '".$reported_year."',
				added_on = '".$date."'"; 
			$result_data = $mysqli->query($insert_custom_indicator_data);
			$custom_indicator_id_new = $mysqli->insert_id;
				if($result_data)
				{
					if($custom_indicator=='exit')
					{
						$_SESSION['custom_indicator_id']=$custom_indicator_id_new;
							header("location:custom_indicator_list.php");
					}
					else
					{
						$_SESSION['custom_indicator_id']=$custom_indicator_id_new;
						header("location:indicator_description.php");
					}
				}
		}
		else
		{
		$update_indicator_description_data = "update usaid_custom_indicator set
			name_indicator = '".$name_indicator."',
			result_measured = '".$result_measured."',
			PPR_indicator = '".$PPR_indicator."',
			reported_year = '".$reported_year."',
			modified_on = '".$date."'
			where id='".$custom_indicator_edit_id."'";
		$result_data = $mysqli->query($update_indicator_description_data);
			if($result_data)
			{
				if($custom_indicator=='exit')
					{
							header("location:custom_indicator_list.php");
					}
					else
					{
						header("location:indicator_description.php");
					}
			
			}
		}	
	}
}
## Get detail for custom indicator ============
$select_data="select * from usaid_custom_indicator where id='".$custom_indicator_edit_id."'";
$result_custom_indicator = $mysqli->query($select_data);
$fetch_custom_indicator = $result_custom_indicator->fetch_array();

$previous_page = basename($_SERVER['HTTP_REFERER'], '?' . $_SERVER['QUERY_STRING']);
if($previous_page=='custom_indicator_list.php')
{
	if($fetch_custom_indicator['reviewer_name']!='') 
	{ 
		header("location:indicator_changes.php"); exit;
	}
	if($fetch_custom_indicator['baseline_timeframe']!='') 
	{
		header("location:indicator_data_quality.php"); exit;
	} 
	if($fetch_custom_indicator['data_soure']!='')  
	{
		header("location:indicator_targets.php"); exit;
	} 
	if($fetch_custom_indicator['precise_definition']!='') 
	{
		header("location:indicator_data_collection.php"); exit;
	}
	if($fetch_custom_indicator['name_indicator']!='') 
	{
		header("location:indicator_description.php"); exit;
	}
}

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
	<script>
	function showYear(value)
	{
		document.getElementById('add-year').style.display = "block";
	}
	function hiddenYear(value)
	{
		document.getElementById('add-year').style.display = "none";
	}
	</script>	
</head>

<body>
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
	<!-- Menu -->
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
							<a class="<?php if($get_current_page=='custom_indicator.php') echo "usa-current"; ?>" href="custom_indicator.php"> Custom Indicator <?php if($fetch_custom_indicator['name_indicator']!='') { ?> <span class="fa fa-check text-success pull-right"> </span> <?php } ?></a>
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
								<h3><?php if($_SESSION['custom_indicator_edit_id']=='') echo " Create to Indicator- "; else echo "Update Changes to Indicator-";  ?>  <?php if($fetch_custom_indicator['name_indicator']!='') echo ucfirst($fetch_custom_indicator['name_indicator']); ?></h3>
							</div>
							<div class="form-indicator">
								<form class="form-horizontal" role="form" method="post" action="">
									<label for="input-type-textarea">Name of Indicator</label>
									<?php if($fetch_custom_indicator!='') $name_indicator= $fetch_custom_indicator['name_indicator']; ?>						
									<input id="input-type-text" name="name_indicator" value="<?php echo $name_indicator; ?>" type="text">
									<span style="font-size: 14px; color: #ccc;">Type the name of indicator</span>

									<label for="input-type-textarea">Name of Result Measured</label>
									<?php if($fetch_custom_indicator!='') $result_measured= $fetch_custom_indicator['result_measured']; ?>						
									<input id="input-type-text" name="result_measured" value="<?php echo $result_measured; ?>" type="text">
									<span style="font-size: 14px; color: #ccc;">(DO, IR, sub-IR, Project Purpose, Project
										Outcome, Project Output, etc.)</span>

										<label for="input-type-textarea">Is This a Performance Plan and Report Indicator?</label>						
										<ul class="usa-unstyled-list" style="display: inline-flex;">
											<li>
											<?php if($fetch_custom_indicator!='') $PPR_indicator= $fetch_custom_indicator['PPR_indicator']; ?>	
												<input id="stanton" onClick="hiddenYear();" type="radio" name="PPR_indicator" value="No" <?php if($PPR_indicator=='No' or $PPR_indicator=='') echo "checked"; ?> >
												<label for="stanton">No</label>
											</li>
											<li style="margin-left: 50px;">
												<input id="anthony" type="radio" name="PPR_indicator" value="Yes" <?php if($PPR_indicator=='Yes') echo "checked"; ?> onClick="showYear();">
												<label for="anthony">Yes</label>
											</li>
										</ul>
										<div id="add-year" style="float:none;max-width:150px;display:<?php if($fetch_custom_indicator['PPR_indicator']=='Yes') echo "block"; else echo "none";?>" class=" clearfix">
											<?php if($fetch_custom_indicator!='') $reported_year= $fetch_custom_indicator['reported_year']; ?>
											<label for="input-type-textarea"> Report Year</label>							
											<input class="usa-input-inline usa-form-control" placeholder="YYYY" aria-describedby="dobHint" id="date_of_birth_3" name="reported_year" pattern="[0-9]{4}" type="number" min="1900" max="3000" value="<?php echo $reported_year; ?>">
										</div>	

										<div class="button_wrapper clear">
											<button class="usa-button-outline" type="reset">Cancel</button>
											<button type="submit" name="custom_indicator" style="display:inline" value="save">Save & Proceed</button>
											<button type="submit" name="custom_indicator" style="display:inline" value="exit">Save & Exit</button>
										</div> 
										
									</form>
								</div>
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