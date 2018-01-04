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
			<div id="proj_prps_blk" class="wrap" style="margin-top:20px; padding:10px;">
				<div style="margin-bottom:20px; padding-top:10px; font-weight:bold;">
					Identify proposed project roles
				</div>
				<form >
					<div class="form_grp">
						<div class="from_label">Project Roles</div>
						<textarea style="height:300px;" class="autoh_textarea"></textarea>
					</div>
				</form>
				<div style="height:10px;"></div>
				<button type="button" class="usa-button-outline">Cancel</button> <button type="button" class="usa-button-hover" id="save_exit">Save & Exit</button>
			</div>
			<div id="proj_prps_msg_blk" class="wrap disp-none" style="margin-top:20px; padding:10px; height:400px">
				<div style="margin-bottom:20px; padding-top:10px; ">
					<span class="bold">“Project Purpose Statement”</span>Stage is assigned to this record.
				</div>
			</div>
		</div>
		<!-- Help Content -->
		<div class="col-md-3">
			<div class="wrap-right-menu">
				<div id="help">
					<h3 class="text-center">HELP</h3>
					<hr>	
					<p class="blk">The information that you enter into this area should include the person’s role, their level of effort, and the length of time the person will work on the project. For AOR/COR roles, please identify which roles will be responsible for which activities (if known).</p>
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
	
			$('#save_exit').click(function(){
				$('#proj_prps_blk').addClass('disp-none');
				$('#proj_prps_msg_blk').removeClass('disp-none');
				setTimeout(function(){
					window.location = "index.php";
				},5000);
			});
		});
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>