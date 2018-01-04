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
			<?php include 'include/project_appraisal_leftpanel.php'; ?>	
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
							<div class="from_label">Title of Proposed Project</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Project Purpose</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Description</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Context</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Other Levaraged Resources</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Summary of Conclusions and Analyses</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Management Plan</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Financial Plan</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Monitoring, Evaluation and Learning Plan</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Activity Plan</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<div class="from_label">Logic Model, AUPGS(for G2G) and Other Required Annexes</div>
							<div>
								<textarea></textarea>
							</div>
						</div>
						<div class="form_grp">
							<button type="button" class="usa-button-outline">Cancel</button> <button type="button">Save & Proceed</button> <button type="button">Save & Exit</button>
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
	<script type="text/javascript" src="js/main.js"></script>
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