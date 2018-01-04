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
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a href="manage_activity.php">Manage Activity Info</a> &raquo; <a class="active">Add Activity</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include('include/activity_info_leftpanel.php');?>
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal">
						<h4>You are editing activity details</h4>
						<p></p>
						<hr>
						<div class="form-group">
							<div class="blk_head">Title</div>
						</div>
						<div class="form-group">
							<div>Title of the Activity</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form-group">
							<div>Activity Description</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="form_grp" style="padding:10px">
								<div class="blk_head_sm">Operational Planned Start Date</div>
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
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="form_grp" style="padding:10px">
								<div class="blk_head_sm">Operational Planned End Date</div>
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
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="form_grp" style="padding:10px">
								<div class="blk_head_sm">Operational Actual Start Date</div>
								<div class="usa-date-of-birth">
								  <div class="usa-form-group usa-form-group-month">
									<label for="date_of_birth_1">Month</label>
									<input class="usa-input-inline" aria-describedby="dobHint"  id="date_of_birth_1" name="date_of_birth_1" pattern="0?[1-9]|1[012]" type="number" min="1" max="12" value="">
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
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<div class="form_grp" style="padding:10px">
								<div class="blk_head_sm">Operational Actual End Date</div>
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
									<input class="usa-input-inline" aria-describedby="dobHint"  id="date_of_birth_3" name="date_of_birth_3" pattern="[0-9]{4}" type="number" min="1900" max="2000" value="">
								  </div>
								</div>
							</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class=" form-group">
							<div>Funding Mechanism</div>
							<div>
								<table style="width:200px; max-width:200px !important;min-width:200px; margin-top:0px;">
								<tbody>
									<tr><td>AT PS.1</td></tr>
									<tr><td>AT FY 2016 - 2018 PS.1</td></tr>
								</tbody>
								</table>
							</div>
						</div>
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
	<script>
		$("#proceed").click(function(){
			window.location = "manage_activity_team.php";
		});
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>