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
			<li><a href=".">Home</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/project_design_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal app_cdcs_no" action="" method="post">
						<div  class="form_grp" style="padding:20px 10px; font-size:16px;">
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Project Details</h3>
							<div>Activites scheduled for concurrent design are located on the Activtiy Tab</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Project Stage</div>
							<div>
								<select id="project_stage">
									<option value="1">Project Purpose Statement</option>
									<option value="2">Project Design Plan</option>
									<option value="3">Project Appraisal Document</option>
									<option value="4">Implementation</option>
									<option value="5">Closed</option>
								</select>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Project Title</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Originating Operating Unit</div>
							<div>
								<input type="text" placeholder="Originating Operating Unit"/>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Implementing Operating Unit</div>
							<div>
								<input type="text" placeholder="Implementing Operating Unit"/>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Estimated of Total USAID Project Funding Needed</div>
							<div>
								<input type="text" placeholder="Estimated of Total USAID Project Funding Needed"/>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Project Purpose</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Plan for Engaging Local Actors</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Plan for Conducting Analyses</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Plan for Possible Use of Govt to Govt</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Proposed Design Cost</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Proposed Design Schedule</div>
							<div>
								<a href="Smartsheet Link" target="_blank">Smartsheet Link</a> <button type="button">Edit Link</button> <button type="button" class="usa-button-gray"><i class="fa fa-toggle-on" aria-hidden="true"></i> View Snapshot</button>
							</div>
						</div>
						<div class="form_grp">
							<button type="button" class="usa-button-outline">Cancel</button> <button type="button">Save</button> <a href="Smartsheet Link" target="_blank">Back to List</a>
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
					<p class="blk">This process lets you convert a Project Purpose Statement into a Project Design Plan or create a new Project Design Plan.</p>				
					<p class="blk">Please choose one of the options on the left.</p>					
				</div>
			</div>
			<div class="third-stage disp-none blk">A Mission must notify its Regional Bureau and PPL of its plans to extend its CDCS at least nine months, but no more than 18 months, before its CDCS expiration date. Notification of an intended extension in less than nine months before the CDCS expiration, due to emergency circumstances will be considered on a case-by-case basis. A request for extension, which is submitted but not approved, cannot serve as a justification for a Mission failing to complete a new CDCS prior to the expiration of its existing CDCS
			</div>				
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script>
		$('#project_stage').change(function(){
			var stage = parseInt($(this).val());
			if(stage>2){
				$('form').attr('action','project_appraisal_document.php');
				$('form').submit();
			}
			else{
				$('form').attr('action','project_purpose_statement.php');
				$('form').submit();
			}	
		});
	</script>
</body>
</html>