<?php 
	$clp_sel ="cdcs";
?>
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
		
		.disp-none{
			display: none;
		}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a href="project_info.php">Edit Project Info</a> &raquo; <a href="manage_project_team.php">Project Team</a> &raquo; <a class="active">Add Project Team</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/project_info_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal app_cdcs_no" action="" method="post">
						<div  class="form_grp" style="padding:20px 0px; font-size:16px;">
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Manage Team Member</h3>
							<hr>
						</div>
						<div id="add_team_member" class="clearfix">
							<div class="form_grp">
								<div class="blk_head">Team Member</div>
								<div>Start typing the name of the person you want to add to the team</div>
							</div>
							<div class="form_grp">
								<input type="text"  placeholder="Enter Employee Name"/>
							</div>
							<div class="form_grp">
								<div class="blk_head">Project Role</div>
								<div>Select the person role</div>
							</div>
							<div class="row form_grp">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="mrg_btm">
										<input type="radio" name="project_role"/><label>Project Manager</label>
									</div>
									<div class="mrg_btm">
										<input type="radio" name="project_role"/><label>COR/AOR</label>
									</div>
									<div class="mrg_btm">
										<input type="radio" name="project_role"/><label>CO</label>
									</div>
									<div class="mrg_btm">
										<input type="radio" name="project_role"/><label>Financial Advisor</label>
									</div>
									<div class="mrg_btm">
										<input type="radio" name="project_role"/><label>Technical Advisor</label>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="mrg_btm">
										<input type="radio" name="project_role"/><label>Legal Advisor</label>
									</div>
									<div class="mrg_btm">
										<input type="radio" name="project_role"/><label>Communications Advisor</label>
									</div>
									<div class="mrg_btm">
										<input type="radio" name="project_role"/><label>Project Administration</label>
									</div>
									<div class="mrg_btm">
										<input type="radio" name="project_role"/><label>Quality Assurance</label>
									</div>
								</div>
							</div>
							<div class="form_grp">
								<div class="blk_head">Start Date</div>
								<div class="usa-date-of-birth">
								  <div class="usa-form-group usa-form-group-month">
									<label for="date_of_birth_1">Month</label>
									<input class="usa-input-inline" aria-describedby="dobHint" id="date_of_birth_1" name="date_of_birth_1" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="">
								  </div>
								  <div class="usa-form-group usa-form-group-day">
									<label for="date_of_birth_2">Day</label>
									<input class="usa-input-inline" aria-describedby="dobHint" id="date_of_birth_2" name="date_of_birth_2" pattern="0?[1-9]|1[0-9]|2[0-9]|3[01]" type="number" min="1" max="31" value="">
								  </div>
								  <div class="usa-form-group usa-form-group-year">
									<label for="date_of_birth_3">Year</label>
									<input class="usa-input-inline" aria-describedby="dobHint" id="date_of_birth_3" name="date_of_birth_3" pattern="[0-9]{4}" type="number" min="1900" max="2000" value="">
								  </div>
								</div>
							</div>
						</div>
						<div class="seprator"></div>
						<div class="form_grp">
							<button type="button" class="usa-button-outline">Cancel</button> <button type="button" id="proceed">Save & Proceed</button> <button type="button">Save & Exit</button>
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
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script>
		$('#proceed').click(function(){
			window.location = "manage_project_geo_location.php";
		});
	</script>
</body>
</html>