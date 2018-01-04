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
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAwDFe0mtkdiw7LyNxJlkEP5Nzm4HLk6AQ"></script>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a> &raquo; <a href="existing_project.php">Existing Project</a> &raquo; <a href="project_info.php">Edit Project Info</a> &raquo; <a class="active">Geo Location</a></li>
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
							<h3 class="form-blk-head" style="margin-top:0px; font-weight:bold;">Manage Geo Location</h3>
							<hr>
						</div>
						<div>
							<div id="map-noida" style="height: 400px; margin-bottom: 20px; position: relative; overflow: hidden;"></div>
						</div>
						<div>
							<table>
								<tr>
									<td class="bold">Location</td>
									<td class="bold">Latitude</td>
									<td class="bold">Longitude</td>
									<td class="bold">Location Type</td>
									<td class="bold">Precision Code</td>
									<td class="bold">Country</td>
									<td class="bold">Action</td>
								</tr>
								<tr>
									<td>Shyamnagar, Bangladesh</td>
									<td>22.3373001</td>
									<td>89.1086477</td>
									<td>Regional</td>
									<td>Moderate</td>
									<td>Bangladesh</td>
									<td class="text-center">
									<form class="edit_location_form disp-inline" method="post">
										<input type="hidden" value="edit_location" name="edit_location"/>
										<input type="hidden" value="000003" name="project_id"/>
										<input type="hidden" value="8" name="geo_location_id"/>
										<a class="btn btn-warning edit_location" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
									</form>
									<div style="height:5px"></div>
									<form class="delete_location_form disp-inline" method="post">
										<input type="hidden" value="delete_location" name="delete_location"/>
										<input type="hidden" value="000003" name="project_id"/>
										<input type="hidden" value="8" name="geo_location_id"/>
										<a class="btn btn-danger delete_location" title="Remove"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
									</form>
									</td>
								</tr>
							</table>	
						</div>
						<div class="seprator"></div>
						<div class="form_grp">
							<div class="blk_head">Search</div>
							<div>
								The name of location you would like to search for this can be a town, city or country.
							</div>
							<div>
								<input type="text" placeholder="Enter a location"/>
							</div>
						</div>
						<div class="seprator"></div>
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
								<div class="blk_head_sm">Address</div>
								<div style="height:70px">
									Confirmation of the full address,you should check this is accurate
								</div>
								<div>
									<textarea></textarea>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
								<div class="blk_head_sm">Latitude</div>
								<div style="height:70px">
									Coordinates
								</div>
								<div>
									<textarea></textarea>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
								<div class="blk_head_sm">Longitude</div>
								<div style="height:70px">
									Coordinates
								</div>
								<div>
									<textarea></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
								<div class="blk_head_sm">Precision</div>
								<div>
									<input type="text" />
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
								<div class="blk_head_sm">Location Type</div>
								<div>
									<input type="text" />
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
								<div class="blk_head_sm">Country</div>
								<div>
									<input type="text" />
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
								<div class="blk_head_sm">Centrally Managed</div>
								<div>
									<input type="text" />
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
								<div class="blk_head_sm">Activity</div>
								<div>
									<select>
										<option>Select Activity</option>
									</select>
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
								
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
			<div class="third-stage disp-none blk">A Mission must notify its Regional Bureau and PPL of its plans to extend its CDCS at least nine months, but no more than 18 months, before its CDCS expiration date. Notification of an intended extension in less than nine months before the CDCS expiration, due to emergency circumstances will be considered on a case-by-case basis. A request for extension, which is submitted but not approved, cannot serve as a justification for a Mission failing to complete a new CDCS prior to the expiration of its existing CDCS
			</div>				
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script>
		$('#proceed').click(function(){
			window.location = "manage_project_strategy.php";
		});
		
		var myCenter_noida=new google.maps.LatLng(28.6129155,77.3874232);
		var map;
		function initMap() {
		map = new google.maps.Map(document.getElementById('map-noida'), {
		  center: {lat: -34.397, lng: 150.644},
		  zoom: 8
		});
		}
		 initMap();
	</script>
</body>
</html>