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
					<div style="padding:10px; font-size:16px">I want to convert a Project Purpose Statement into a Project Design Plan.</div>
					<p></p>
					<hr>
					<form action="project_purpose_statement.php" method="post">
					<div class="form_grp">
						<div class="from_label">Enter the Project ID or Choose a project from a list of all Projects</div>
						<div>
							<select id="choose_project">
								<option value="">Select</option>
								<option value="Project ID">Project ID</option>
								<option value="Choose Project">Choose Project</option>
							</select>
						</div>
					</div>
					<div class="form_grp project_by_id disp-none">
						<div class="from_label">Enter Project ID</div>
						<div><input type="text" placeholder="Project ID"/></div>
					</div>
					<div class="form_grp project_search disp-none">
						<div class="from_label">Choose Project</div>
						<div>
							<select>
								<option value="">Select</option>
								<option value="">Access To Potable Water In South-Western Coastal Areas Where The Water Table Is Polluted (000003)</option>
								<option value="">Primary School Education Capacity Building For At-risk Rural Populations (000006)</option>
							</select>
						</div>
					</div>
					<div class="form_grp project_proceed disp-none">
						<button class="usa-button">Proceed</button>
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
		$('#choose_project').change(function(){
			
			if($(this).val()!="" && $(this).val()=="Project ID"){
				$('.project_search').addClass('disp-none');
				$('.project_by_id,.project_design_plan').removeClass('disp-none');
			}
			else if($(this).val()!="" && $(this).val()=="Choose Project"){
				$('.project_by_id').addClass('disp-none');
				$('.project_search,.project_design_plan').removeClass('disp-none');
			}
			$('.project_proceed').removeClass('disp-none');
		});
	</script>
</body>
</html>