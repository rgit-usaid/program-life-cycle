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
			<div class="wrap" style="margin-top:20px; padding:10px;">
				<div style="margin-bottom:20px; padding-top:10px; font-weight:bold;">
					Rationale for choosing each IR or Sub-IR
				</div>
				<textarea class="autoh_textarea"></textarea>
				<div style="height:10px;"></div>
				<button type="button" class="usa-button-outline">Cancel</button> <button type="button" class="usa-button-hover" id="save_proceed">Save & Proceed</button> <button type="button" class="usa-button-hover">Save & Exit</button>
			</div>
		</div>
		<!-- Help Content -->
		<div class="col-md-3">
			<div class="wrap-right-menu">
				<div id="help">
					<h3 class="text-center">HELP</h3>
					<hr>	
					<p class="blk">Explain why this project will contribute to the Intermediate Result or the Sub-Intermediate Result that you have aligned it to. Also, please provide a description of how contextual conditions relevant to an IR or Sub-IR will be monitored. If you know them now, include a list of any context indicators for monitoring assumptions, or list risks that may affect progress or the operational context in which strategies and projects are being implemented. For additional guidance on context monitoring, see ADS 201.3.5.5</p>
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
			});
			
			$('#save_proceed').click(function(){
				window.location ="project_role.php";
			});
		});
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>