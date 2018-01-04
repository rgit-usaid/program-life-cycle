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
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a class="active">Manage Activity Info</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include('include/project_leftpanel.php');?>
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal">
						<div class="form_grp bold" style="padding:20px 0px 10px 0; font-size:16px;">
							Choose one :
						</div>
						<hr>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div style="padding-top:20px" >
									<input type="radio" name="manage_team_member" value="add_activity"/><label>Create new activity</label>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div style="padding-top:20px">
									<input type="radio" name="manage_team_member" value="edit_activity"/><label>Edit existing activity</label>
								</div>
							</div>
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
		$('input[type="radio"][name="manage_team_member"]').click(function(){
			if($(this).val()=="add_activity"){
				window.location = "add_activity.php";
			}
			else if($(this).val()=="edit_activity"){
				window.location = "activity_list.php";
			}
			
		});
	
		$("#proceed").click(function(){
			window.location = "manage_activity_team.php";
		});
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>