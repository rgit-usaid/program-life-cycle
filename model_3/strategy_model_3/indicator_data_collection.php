<?php
include('config/config.inc.php');
include('include/function.inc.php'); 
$get_current_page = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']); 

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
if(isset($_REQUEST['data_collection']))
{	
	$data_collection = $mysqli->real_escape_string(trim($_REQUEST['data_collection']));
	$data_soure = $mysqli->real_escape_string(trim($_REQUEST['data_soure'])); 
	$data_collection_method = $mysqli->real_escape_string(trim($_REQUEST['data_collection_method'])); 
	$reporting_frequency = $mysqli->real_escape_string(trim($_REQUEST['reporting_frequency'])); 
	$individual_responsible = $mysqli->real_escape_string(trim($_REQUEST['individual_responsible']));
	if($data_collection=='save')
	{ 
		if($data_soure=='')
		{
			$error = 'Data source should not be blank';
		}
	}
	if($error=='')
	{	
		$update_data_collection_data = "update usaid_custom_indicator set
			data_soure = '".$data_soure."',
			data_collection_method = '".$data_collection_method."',
			reporting_frequency = '".$reporting_frequency."',
			individual_responsible = '".$individual_responsible."'
			where id='".$custom_indicator_id."'"; 
		$result_data = $mysqli->query($update_data_collection_data);
			if($result_data)
			{
				if($data_collection=='exit')
					{
							header("location:custom_indicator_list.php");
					}
				else
					{
						header("location:indicator_targets.php");
					}
				
			}
	}
}

## get detail of indicator ===========

$select_data="select * from usaid_custom_indicator where id='".$custom_indicator_id."'";
$result_custom_indicator = $mysqli->query($select_data);
$fetch_custom_indicator = $result_custom_indicator->fetch_array();	

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
								<h3> <?php if($_SESSION['custom_indicator_edit_id']=='') echo "Create to Indicator- "; else echo "Update Changes to Indicator- ";  ?>  <?php if($fetch_custom_indicator['name_indicator']!='') echo ucfirst($fetch_custom_indicator['name_indicator']); ?></h3>
							</div>
							<div class="form-indicator">
								<form class="form-horizontal" role="form" method="post" action="">
									<label for="input-type-textarea">Data Source</label>
									<?php if($fetch_custom_indicator!='') $data_soure= $fetch_custom_indicator['data_soure']; ?>						
									<input id="input-type-text" name="data_soure" value="<?php echo $data_soure; ?>" type="text">

									<label for="input-type-textarea">Method of Data Collection and Construction</label>
									<?php if($fetch_custom_indicator!='') $data_collection_method= $fetch_custom_indicator['data_collection_method']; ?>						
									<input id="input-type-text" name="data_collection_method" value="<?php echo $data_collection_method; ?>" type="text">

									<label for="input-type-textarea">Reporting Frequency</label>
									<?php if($fetch_custom_indicator!='') $reporting_frequency= $fetch_custom_indicator['reporting_frequency']; ?>						
									<input id="input-type-text" name="reporting_frequency" value="<?php echo $reporting_frequency; ?>" type="text">

									<label for="input-type-textarea">Individual(s) Responsible at USAID</label>
									<?php if($fetch_custom_indicator!='') $individual_responsible= $fetch_custom_indicator['individual_responsible']; ?>						
									<input id="input-type-text" name="individual_responsible" value="<?php echo $individual_responsible; ?>" type="text">

									<div class="button_wrapper clear">
										<button class="usa-button-outline" type="reset">Cancel</button>
										<button type="submit" name="data_collection" style="display:inline" value="save">Save & Proceed</button>
										<button type="submit" name="data_collection" style="display:inline" value="exit">Save & Exit</button>
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
