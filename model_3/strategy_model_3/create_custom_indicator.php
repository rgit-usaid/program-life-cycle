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
	<?php include 'include/header.html'; ?>
	<!-- Menu -->
	<div class="menu">
		<div class="container-fluid">
			<div class="col-md-4 text-center">
				<a class="usa-button usa-button-outline-inverse" href="framework_management.php">Framework Management</a>
			</div>
			<div class="col-md-4 text-center">
				<a class="usa-button usa-button-outline-inverse active-class" href="indicator_management.php">Indicator Management</a>
			</div>
			<div class="col-md-4 text-center">
				<a class="usa-button usa-button-outline-inverse" href="#">Development Objective Agreement Objective</a>
			</div>
		</div>
	</div>

	<!-- Create Indicator  -->
	<div class="indicator">
		<div class="container-fluid">
			<div id="content">
				<div class="col-md-3" style="margin-top: 55px;">
					<nav id="nav-in">
						<ul class="usa-sidenav-list">
							<li>
								<a class="usa-current" href="create_custom_indicator.php">Create Custom Indicator</a>
							</li>
							<li>
								<a href="indicator_description.php">Indicator Description</a>
							</li>
							<li>
								<a href="indicator_data_collection.php">Plan for Data Collection</a>
							</li>
							<li>
								<a href="indicator_targets.php">Targets and Baseline</a>
							</li>
							<li>
								<a href="indicator_data_quality.php">Data Quality Issues</a>
							</li>
							<li>
								<a href="indicator_changes.php">Changes to Indicator</a>
							</li>
						</ul>
					</nav>
				</div>
				<div class="col-md-9">
					<div class="main-form">
						<div class="container-fluid">
							<div class="heading">
								<h3>Create Custom Indicator</h3>
							</div>
							<div class="form-indicator">
								<form class="form-horizontal" role="form" method="post" action="">
									<label for="input-type-textarea">Name of Indicator</label>						
									<input id="input-type-text" name="input-type-text" type="text">
									<span style="font-size: 14px; color: #ccc;">Type the name of indicator</span>

									<label for="input-type-textarea">Name of Result Measured</label>						
									<input id="input-type-text" name="input-type-text" type="text">
									<span style="font-size: 14px; color: #ccc;">(DO, IR, sub-IR, Project Purpose, Project
										Outcome, Project Output, etc.)</span>

										<label for="input-type-textarea">Is This a Performance Plan and Report Indicator?</label>						
										<ul class="usa-unstyled-list" style="display: inline-flex;">
											<li>
												<input id="stanton" type="radio" name="historical-figures-2" value="stanton">
												<label for="stanton">No</label>
											</li>
											<li style="margin-left: 50px;">
												<input id="anthony" type="radio" name="historical-figures-2" value="anthony">
												<label for="anthony">Yes</label>
											</li>
										</ul>

										<div class="button_wrapper clear">
											<button class="usa-button-outline" type="button">Cancel</button>
											<button>Save</button>
										</div>
									</form>
								</div>
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