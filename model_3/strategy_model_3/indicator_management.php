<?php
include('config/config.inc.php');
include('include/function.inc.php');
//## get Detail operating unit ===========
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
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">	
	<link href="css/uswds.min.css" rel="stylesheet">
	<link href="css/responsive.dataTables.min.css" type="text/css" rel="stylesheet">
	
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
		<div class="header-detail">
			<div class="head-title">
				<div class="head">Operating Unit <span class="pull-right">(<a href=".">Back to Operating Unit</a>)</span></div>
			</div>
			<div class="row clear details">
				<div class="col-md-12"><a href="manage.php" class="remove-line"><?php echo $operating_unit_arr['data']['operating_unit_description'];?></a> </div>
			</div>
		</div>
		<div class="container-fluid">
			<nav id="nav">
				<ul class="list-inline">
					<li><a class="usa-button usa-button-outline-inverse active-class-white" href="standard_indicator_list.php">Standard Indicators</a></li>
					<li><a class="usa-button usa-button-outline-inverse active-class-white" href="custom_indicator_list.php">Custom Indicators</a></li>
				</ul>
			</nav>
			<div id="content"></div>
		</div>


	<!-- <script type="text/javascript" src="js/jquery-ui.min.js"></script> -->
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.responsive.js"></script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/uswds.min.js"></script>
</body>
</html>