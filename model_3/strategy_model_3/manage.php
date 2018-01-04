<?php
include('config/config.inc.php');
include('include/function.inc.php');
###get program element number =========
	$operating_unit_id = '';
	if(isset($_REQUEST['operating_unit_id']))
	{
		$operating_unit_id = trim($_REQUEST['operating_unit_id']); // exit;
		$_SESSION['operating_unit_id'] = $operating_unit_id;
	}
	else
	{
		$operating_unit_id = $_SESSION['operating_unit_id'];
	}
	if($_SESSION['operating_unit_id']=='')
	{
		header('location:index.php');
	}
//## get Detail operating unit ===========
	$url = PHOENIX_API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;
	$operating_unit_arr = requestByCURL($url); 
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
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">	
	<link href="css/uswds.min.css" rel="stylesheet">	
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
	<h3 class="text-center">Choose one of the options above or below for this OU</h3>
	<!-- Header Details -->
	<div class="container-fluid">
		<div class="header-detail">
			<div class="head-title">
				<div class="head">Operating Unit <span class="pull-right">(<a href=".">Back to Operating Unit</a>)</span></div>
			</div>
			<div class="row clear details">
				<div class="col-md-12 info">Operating Unit: <span class="disc"> <?php echo $operating_unit_arr['data']['operating_unit_description'];?></span></div>
			</div>
		</div>
	</div>
	
	<!-- Menu Below List -->
	<div class="container">
		<div class="usa-navbar site-header-navbar" style="">
			<ul class="usa-button-list usa-unstyled-list list-inline">
				<li style="margin:  0 40px;">
					<a class="usa-button usa-button-outline-inverse active-class-white" href="framework_management.php">Framework Management</a>
				</li>
				<li style="margin:  0 40px;">
					<a class="usa-button usa-button-outline-inverse active-class-white" href="indicator_management.php">Indicator Management</a>
				</li>
				<li style="margin:  0 40px;">
					<a class="usa-button usa-button-outline-inverse active-class-white" href="add_doag.php">DOAGs and SOAGs</a>
				</li>
			</ul>
		</div>
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/uswds.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

	</body>
	</html>