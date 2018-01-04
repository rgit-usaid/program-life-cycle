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
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a href="project_performance.php">Project Peformance</a> &raquo; <a class="active">Project Monitoring</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/project_performance_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal app_cdcs_no" action="" method="post">
						<div  class="form_grp" style="padding:20px 0px; font-size:16px;">
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Manage Project Review</h3>
							<hr>
						</div>
						<div class="form_grp">
							<div class="blk_head">Reviews</div>
						</div>
						<div class="form_grp">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="bold">AR Required?</div>
									<div class="form_grp">
										<input type="radio" name="no" id="yes1"/><label for="yes1">Yes</label>
									</div>
									<div class="form_grp">
										<input type="radio" name="no" id="no1"/><label for="no1">No</label>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div style="padding:40px 0" class="bold text-danger">No Annual Review Required.</div>
								</div>
							</div>
						</div>
						<div class="seprator"></div>
						<div class="form_grp">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="bold">PAR Required?</div>
									<div class="form_grp">
										<input type="radio" name="no" id="yes1"/><label for="yes1">Yes</label>
									</div>
									<div class="form_grp">
										<input type="radio" name="no" id="no1"/><label for="no1">No</label>
									</div>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div style="padding:40px 0" class="bold text-danger">No Project/ Activity Review Required.</div>
								</div>
							</div>
						</div>
						<div class="form_grp">
							<div class="blk_head">Add New Review</div>
						</div>
						<div class="form_grp">
							<div class="bold">Type of Review</div>
							<div>
								<select>
									<option>Select</option>
									<option>Annual Reivew</option>
									<option>Project Activity Reivew</option>
								</select>
							</div>
						</div>
						<div class="form_grp">
							<div class="bold">Overall Score</div>
							<div>
								<input type="text" />
							</div>
						</div>
						<div class="form_grp">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="bold">Review Prompt Date</div>
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
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="bold">Review Prompt Date</div>
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
						<div class="form_grp">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
									<div class="bold">Actual Review Date</div>
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
						<div class="form_grp">
							<div class="blk_head">Output Scoring</div>
							<div>
								<table>
									<tr>
										<td>Description</td>
										<td>Impact Weight (%)</td>
										<td>Perfomance</td>
										<td>Risk</td>
										<td>Action</td>
									</tr>
									<tr>
										<td>
											<textarea></textarea>
										</td>
										<td><input type="text" /></td>
										<td>
											<select style="width:100px">
												<option>Select Performance</option>
												<option>A</option>
												<option>B</option>
												<option>C</option>
												<option>D</option>
											</select>
										</td>
										<td>
											<select style="width:100px">
												<option>Select Risk</option>
												<option>L</option>
												<option>M</option>
												<option>H</option>
											</select>
										</td>
										<td>
											<a class="btn btn-danger">
												<i class="fa fa-trash-o" aria-hidden="true"></i>
											</a>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="form_grp">
							<div class="bold">Documents</div>
							<div style="padding:10px; border:1px dashed #047954; margin-top:10px">
								<div class="blk_head">Submission</div>
								<div class="bold">Submission Comments</div>
								<div><textarea></textarea></div>
							</div>	
						</div>
						<div class="form_grp">
							<div style="padding:10px; border:1px dashed #047954; margin-top:10px">
								<div class="blk_head">Comments</div>
								<div class="bold">Comments</div>
								<div><textarea></textarea></div>
								<div style="margin-top:20px">
									<input type="radio" /><label>Approve</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" /><label>Reject</label>
								</div>
								<div style="margin-top:20px">
									<input type="text" />
								</div>
							</div>	
						</div>
						<div class="form_grp">
							<button type="button" class="usa-button-outline">Cancel</button> <button type="button" id="proceed">Save & Proceed</button> <button type="button">Save & Exit</button>
						</div>
						<div class="form_grp">
							<table>
								<tr>
									<td>Type of Review</td>
									<td>Due Date</td>
									<td>Prompt Date</td>
									<td>Approver</td>	
									<td>Action</td>
								</tr>
								<tr>
									<td colspan="5" class="text-danger bold">
										No review found for this project
									</td>
								</tr>
							</table>
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
		$('#show_team_member_blk').click(function(){
			$('#add_team_member').toggleClass('disp-none');
		});
		
		
		$('#show_team_blk').click(function(){
			$('#team_history_blk').toggleClass('disp-none');
		});
		
		$('#proceed').click(function(){
			window.location = "manage_project_evaluation.php";
		});
	</script>
</body>
</html>