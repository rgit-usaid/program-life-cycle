<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<title>USAID-4</title>
	<link href="css/uswds.min.css" rel="stylesheet">
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Theme CSS -->
	<link href="css/style.css" rel="stylesheet">
	<!-- Custom Fonts -->
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="init()">

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a></li>
			<li class="active">Create an Opportunity</li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/cdcs_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap">
				<div class="container-fluid">
					<div style="height:300px; padding:30px 0; font-size:18px; text-align:center;">
						No Framework found
					</div>
				</div>
			</div> 
			<div class="wrap" style="margin-top:20px; padding:10px;">
				<div style="margin-bottom:20px; padding-top:10px; font-weight:bold;">
					Describe how this project supports one or more IRs or Sub-IRs in the CDCS or other country strategic plan.
				</div>
				<textarea></textarea>
				<div style="height:10px;"></div>
				<button type="button" class="usa-button-outline">Cancel</button> <button type="button" class="usa-button-hover" id="proceed">Save & Proceed</button> <button type="button" class="usa-button-hover">Save & Exit</button>
			</div>
		</div>
		<!-- Help Content -->
		<div class="col-md-3">
			<div class="wrap-right-menu">
				<div id="help">
					<h3 class="text-center">HELP</h3>
					<hr>	
					
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".readmore").click(function(){
			$(".read").removeClass('disp-none');
			$(".readmore").hide();
			})
		});
		
		$('#proceed').click(function(){
			window.location ="provide_rational.php";
		});
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>