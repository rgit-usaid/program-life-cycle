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
	<link href="css/sumoselect.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<style>
		.disp-none{
			display: none;
		}
		
		input[type='text'][readonly]{background:#f8f8f8;}
		.close_btn{cursor:pointer;}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a href="activity_list.php">Edit Activity Info</a> &raquo; <a class="active">Activity Program Element</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/activity_info_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal app_cdcs_no" action="" method="post">
						<div  class="form_grp" style="padding:20px 0px; font-size:16px;">
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Manage Activity Program Element</h3>
							<hr>
						</div>
						<div class="form_grp">
							<select class="SlectBox">
								<option value="PEACE AND SECURITY (PS)">PEACE AND SECURITY (PS)</option>
								<option value="Counter-Terrorism (PS.1)">Counter-Terrorism (PS.1)</option>
								<option value="Disrupt Terrorist Networks (PS.1.1)">Disrupt Terrorist Networks (PS.1.1)</option>
								<option value="Counter Violent Extremism (PS.1.2)">Counter Violent Extremism (PS.1.2)</option>
							</select>
						</div>
						<div class="form_grp" style="padding:20px 0px; font-size:16px;">
							<button class="pull-right" type="button" id="edit_program_element">Edit Program Element</button>
						</div>
						<div>
							<table>
								<tr>
									<td style="width:150px">Program Element Code</td>
									<td style="width:150px">Program Element Name</td>
									<td>Percentage</td>
									<td class="center">Action</td>
								</tr>
								<tr class="prgm_elem">
									<td>PS</td>
									<td>PEACE AND SECURITY</td>
									<td style="position:relative">
										<div style="margin-right:5px; display:block">
											<input type="text"  value="10" readonly="readonly" class="score"/>
										</div>	
										<span style="position:absolute;top:30%; right:0;">%</span>
									</td>
									<td class="text-center"><a class="close_btn"><i class="fa fa-close text-danger" style="font-size:20px"></i></a></td>
								</tr>
								<tr class="prgm_elem">
									<td>PS.1</td>
									<td>Counter-Terrorism</td>
									<td style="position:relative">
										<div style="margin-right:5px; display:block">
											<input type="text" value="90" readonly="readonly" class="score"/>
										</div>	
										<span style="position:absolute;top:30%; right:0;">%</span>
									</td>
									<td class="text-center"><a class="close_btn"><i class="fa fa-close text-danger" style="font-size:20px"></i></a></td>
								</tr>
							</table>
						</div>
						<div class="form_grp">
							<canvas id="myChart" width="400" height="150"></canvas>
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
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/jquery.sumoselect.min.js"></script>
	<script type="text/javascript" src="js/chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.min.js"></script>
	<script>
		var ctx = document.getElementById("myChart").getContext('2d');
		var myChart = new Chart(ctx, {
		  type: 'pie',
		  data: {
			labels: ["PEACE AND SECURITY", "Counter-Terrorism"],
			datasets: [{
			  backgroundColor: [
				"#2ecc71",
				"#3498db",
				"#95a5a6",
				"#9b59b6",
				"#f1c40f",
				"#e74c3c",
				"#34495e"
			  ],
			  data: [60, 40]
			}]
		  }
		});
	</script>
	<script>
		$('#show_team_member_blk').click(function(){
			$('#add_team_member').toggleClass('disp-none');
		});
		
		
		$('#show_team_blk').click(function(){
			$('#team_history_blk').toggleClass('disp-none');
		});
		
		$('#proceed').click(function(){
			window.location = "project_finance.php";
		});
		
		$('.SlectBox').SumoSelect({
			placeholder: 'Select Program Element',
			okCancelInMulti: false,
			search : false,
			csvDispCount: 1
		});
		
		$('#edit_program_element').click(function(){
			$('.score').removeAttr('readonly');
		});
		
		$('.close_btn').click(function(){
			if($('.close_btn').length>1){
				$(this).closest('.prgm_elem').remove();
			}
			else{
				alert("Add atleast one program element to activity.");
			}
		});
		
	</script>
<style>
	.chart-cont{min-width:650px}
</style>
</body>
</html>