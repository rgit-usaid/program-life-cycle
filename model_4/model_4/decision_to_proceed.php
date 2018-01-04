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
	<style>
		.box>a{
			color:#fff;
		}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a></li>
			<li class="active">Manage Activity</li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include('include/create_opportunity_leftpanel.php');?>
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal">
						<h3>Do you want to proceed with this opportunity?</h3>
						<p></p>
						<hr>
						<div class="form-group">
							<ul class="usa-unstyled-list list-inline" style="margin-top: 10px;">
								<li>
									<input id="new_activity" type="radio" name="activity" value="new_activity">
									<label for="add">Yes</label>
								</li>
								<li style="margin-left: 30px;">
									<input id="view_activity" type="radio" name="activity" value="view_activity">
									<label for="view">No</label>
								</li>
							</ul>
						</div>
						<div class="new box" style="display: none;">
							<a class="btn btn-primary" id="proceed">Proceed</a>
						</div>
						<div class="view box" style="display: none;">
							<a href="index.php" class="btn btn-primary" id="archive">Archive</a>
						</div>
						
						<div id="msg" style="margin-top:15px; border:solid 1px #ddd; background:#e4f5ff; padding:20px; font-size:24px; font-weight:bold; display:none">
							<img src="img/loading.gif" width="30" class="center-block" style="display:inline"/>
							Status changed to Opportunity Design Plan
						</div>
					</form> 
				</div> 
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
			$('input[type="radio"]').click(function(){
				if($(this).attr("value")=="new_activity"){
					$(".box").not(".new").hide();
					$(".new").show();
				}
				if($(this).attr("value")=="view_activity"){
					$(".box").not(".view").hide();
					$(".view").show();
				}
			});
			
			$('#proceed').click(function(){
				$('#msg').css({'display':'block'});
				
				setTimeout(function(){
					window.location="manage_activity.php";
				},1500);
			});
			
			
		
		
		});
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>