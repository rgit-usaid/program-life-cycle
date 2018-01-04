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
	<link href="css/plugin/tags/bootstrap-tagsinput.css" rel="stylesheet" type="text/css">
	<link href="css/plugin/typeaheadsearch/styles.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/plugin/tags/bootstrap-tagsinput.js"></script>
	<script type="text/javascript" src="js/plugin/tags/bootstrap3-typeahead.js"></script>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a class="active">Project Document</a></li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/project_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap" style="min-height: 200px;">
				<div class="container-fluid">
					<form class="form-horizontal app_cdcs_no" action="" method="post">
						<div  class="form_grp" style="padding:20px 0px; font-size:16px;">
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Manage Project Document</h3>
							<hr>
						</div>
						<div class="form_grp">
							<div class="blk_head">Key Documents</div>
						</div>
						<div class="form_grp">
							<div>Select Activity (Optional)</div>
							<div>
								<select>
									<option>Project Level Document</option>
									<option value="000003-001">Provide potable water for southwestern Bangladesh - this activity will be located in the Ishwaripur region.</option>
								</select>
							</div>
						</div>
						<div class="form_grp">
							<div>Document Title</div>
							<div>
								<textarea></textarea>		
							</div>
						</div>
						<div class="form_grp">
							<div>Document Tags</div>
							<div>
								<textarea class="tagsinput-typeahead"></textarea>		
							</div>
						</div>
						<div class="form_grp">
							<div>Document</div>
							<div>
								<input type="file"/>
							</div>
						</div>
						<div class="form_grp">
							<button type="button" class="usa-button-outline">Cancel</button> <button type="button" id="proceed">Save & Proceed</button> <button type="button">Save & Exit</button>
						</div>
						<div style="margin-top:20px">
							<div class="blk_head">Project Evaluation List</div>
						</div>
						<div class="form_grp">
							<table>
								<tr>
									<td>Title</td>
									<td>Related To</td>
									<td>Tags</td>
									<td>Download</td>	
									<td>Action</td>
								</tr>
								<tr>
									<td>Water Pollution</td>
									<td>Project (Access To Potable Water In South-Western Coastal Areas Where The Water Table Is Polluted)</td>
									<td>water pollution, potable</td>
									<td><a href="#"><img src="img/download-icon.png" class="center-block"/></a></td>	
									<td><a class="btn btn-danger remove_document"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
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
			window.location = "existing_project.php";
		});
		
		/*===fill tags==*/
		var tags = new Array();	
		var obj = new Object();
		obj['name'] = 'Project';
		tags.push(obj);
		var obj = new Object();
		obj['name'] = 'Activity';
		tags.push(obj);
		var obj = new Object();
		obj['name'] = 'Evaluation';
		tags.push(obj);
		var obj = new Object();
		obj['name'] = 'Program Element';
		tags.push(obj);
		 
		$('.tagsinput-typeahead').tagsinput({
		  typeahead: {
			source: tags.map(function(item) { return item.name }),
			afterSelect: function() {
				this.$element[0].value = '';
					
			}
		  }
		}); 

	</script>
</body>
</html>