<?php include('config/functions.inc.php');
##==validate user====
validate_user();

## insert geo location in geo archive table  ====================
function insertGeoLocationData($project_id)
{
	global $mysqli;
	if($project_id!='')
	{
		$url = API_HOST_URL_PROJECT."get_all_project_geo_location.php?project_id=".$project_id;  
  		$project_geo_arr = requestByCURL($url);
		if(count($project_geo_arr['data'])>0)
		{
			$insert_archive_geo = "insert into usaid_archive_geo set
			 project_id='".$project_id."',
			 modified_by='".$_SESSION['first_last_name']."'"; 
			$result_archive_geo = $mysqli->query($insert_archive_geo);
			if($result_archive_geo)
			{
				$archive_id = $mysqli->insert_id;
				for($i=0; $i<count($project_geo_arr['data']); $i++)
				{
					$insert_archive_project_geo = "insert into usaid_archive_project_geo set
						 archive_geo_id='".$archive_id."',
						 project_id='".$project_geo_arr['data'][$i]['project_id']."',
						 project_activity_id='".$project_geo_arr['data'][$i]['project_activity_id']."',
						 address='".$project_geo_arr['data'][$i]['address']."',
						 latitude='".$project_geo_arr['data'][$i]['latitude']."',
						 longitude='".$project_geo_arr['data'][$i]['longitude']."',
						 location_type='".$project_geo_arr['data'][$i]['location_type']."',
						 country='".$project_geo_arr['data'][$i]['country']."',
						 precision_code='".$project_geo_arr['data'][$i]['precision_code']."',
						 centrally_managed='".$project_geo_arr['data'][$i]['centrally_managed']."',
						 impacted_area='".$project_geo_arr['data'][$i]['impacted_area']."',
						 added_on='".dateFormat($project_geo_arr['data'][$i]['added_on'])."'";
					$result_archive_project_geo = $mysqli->query($insert_archive_project_geo);
					
				}
			}  
		}
		
	}
} 


###request for get single project details using project id ===========
$project_id = '';
if(isset($_REQUEST['details']))
{
	$project_id = trim($_REQUEST['project_id']);
	$_SESSION['project_id'] = $project_id; 
}

if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];	
}


##==delete location==
if(isset($_REQUEST['delete_location']) && isset($_REQUEST['project_id']) && $_REQUEST['project_id']!="" && isset($_REQUEST['geo_location_id']) && $_REQUEST['geo_location_id']!="")
{
	$_SESSION['form_msg'] = array();
	$geo_location_id = $_REQUEST['geo_location_id'];
	$project_id = $_REQUEST['project_id'];
	### function for get archive before delete==============
	insertGeoLocationData($project_id);
	$del_loc ="DELETE FROM usaid_project_geo WHERE id='".$geo_location_id."' and project_id='".$project_id."'";
	$exe_loc = $mysqli->query($del_loc);
	if($exe_loc){
		$_SESSION['form_msg']['msg_type'] ="success";
		$_SESSION['form_msg']['msg'] = "Geo locataion has deleted successfully";
		header("Location:add_project_geo_location");		
	}
	else{
		$_SESSION['form_msg']['msg'] ="error";
		$_SESSION['form_msg']['msg'] ="Something went wrong.";
	}
}

##==get detail of project===
if($project_id!="")
{
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);

    $url = API_HOST_URL_PROJECT."api_demo.php?stage";  
  	$project_stage_arr = requestByCURL($url); 
	
	$url = API_HOST_URL_PROJECT."get_all_project_activity.php?project_id=".$project_id;  
  	$project_activity_arr = requestByCURL($url); 
	
	$url = API_HOST_URL_PROJECT."get_all_project_geo_location.php?project_id=".$project_id;  
  	$project_geo_arr = requestByCURL($url); 
	
	$project_stage_id = '';
	$environmental_threshold = '';
	$gender_threshold = '';
	
	if(isset($_REQUEST['project_stage_id'])) $project_stage_id = $_REQUEST['project_stage_id'];
	if(isset($project_arr)) {
		$project_title = $project_arr['data']['title'];
		$project_stage_id = $project_arr['data']['project_stage_id'];
		$environmental_threshold = $project_arr['data']['environmental_threshold'];
		$gender_threshold = $project_arr['data']['gender_threshold'];
		$team_marker=$project_arr['data']['team_marker'];
	} 
}
else{
	header("Location:home");
}


##==add location==
if(isset($_REQUEST['add_location']) && isset($_REQUEST['project_id']) && $_REQUEST['project_id']!="")
{
	$_SESSION['form_msg'] = array();
	$project_id = trim($_REQUEST['project_id']);
	
	$activity_id="project_activity_id=NULL";
	if($_REQUEST['project_activity_id']!=""){
		$activity_id="project_activity_id='".$_REQUEST['project_activity_id']."'";	
	}
	
	$address = $mysqli->real_escape_string(trim($_REQUEST['address']));
	$latitude = trim($_REQUEST['latitude']);
	$longitude = trim($_REQUEST['longitude']);
	
	$location_type="location_type=NULL";
	if($_REQUEST['location_type']!=""){
		$location_type="location_type='".$mysqli->real_escape_string($_REQUEST['location_type'])."'";	
	}
	
	$country="country=NULL";
	if($_REQUEST['country']!=""){
		$country="country='".$_REQUEST['country']."'";	
	}
	
	$precision_code="precision_code=NULL";
	if($_REQUEST['precision_code']!=""){
		$precision_code="precision_code='".$mysqli->real_escape_string($_REQUEST['precision_code'])."'";	
	}
	
	
	$centrally_managed="centrally_managed='Unconfirmed'";
	if(isset($_REQUEST['centrally_managed']) && $_REQUEST['centrally_managed']=="Yes"){
		$centrally_managed="centrally_managed='Confirmed'";	
	}
	
	$impacted_area="impacted_area='No'";
	if(isset($_REQUEST['impacted_area']) && $_REQUEST['impacted_area']=="Yes"){
		$impacted_area="impacted_area='Yes'";	
	}
	
	if($_REQUEST['geo_location_id']==""){
	
		insertGeoLocationData($project_id); // call for insert archive geo location 
		$insert_project_geo = "insert into usaid_project_geo set project_id = '".$project_id."', ".$activity_id.", address='".$address."', latitude='".$latitude."', longitude='".$longitude."' , ".$location_type.", ".$country.", ".$precision_code.", ".$centrally_managed.", ".$impacted_area;
		$result_project_geo = $mysqli->query($insert_project_geo);
		
		if($result_project_geo)
		{
			$_SESSION['form_msg']['msg_type'] ="success";
			$_SESSION['form_msg']['msg'] = "Congratulation geo locataion has added successfully";
			header("Location:add_project_geo_location");
		}
		else
		{
			$_SESSION['form_msg']['msg_type'] ="error";
			$_SESSION['form_msg']['msg'] = "Error";
		}
	}
	else{
		insertGeoLocationData($project_id); // call for insert archive geo location 
		$update_project_geo = "update usaid_project_geo set project_id = '".$project_id."', ".$activity_id.", address='".$address."', latitude='".$latitude."', longitude='".$longitude."' , ".$location_type.", ".$country.", ".$precision_code.", ".$centrally_managed.", ".$impacted_area." WHERE id=".$_REQUEST['geo_location_id'];
		$result_project_geo = $mysqli->query($update_project_geo);
	
		if($result_project_geo)
		{
			$_SESSION['form_msg']['msg_type'] ="success";
			$_SESSION['form_msg']['msg'] = "Congratulation geo locataion has updated successfully";
			header("Location:add_project_geo_location");
		}
		else
		{
			$_SESSION['form_msg']['msg_type'] ="error";
			$_SESSION['form_msg']['msg'] = "Error";
		}
	}
}

##==edit project review==
if(isset($_REQUEST['edit_location']) && isset($_REQUEST['project_id']) && $_REQUEST['project_id']!=""){
	$geo_location_id = $_REQUEST['geo_location_id'];
	$project_id = $_REQUEST['project_id'];
	$edit_mode = "edit_mode";
	
	##==get project_review_info
	$url = API_HOST_URL_PROJECT."get_project_geo_location.php?project_id=".$project_id."&geo_location_id=".$geo_location_id; 
    $project_geo_location_arr = requestByCURL($url);
	$project_geo_location_id = $project_geo_location_arr['data']['geo_location_id'];
	$project_activity_id = $project_geo_location_arr['data']['project_activity_id'];
	$address = $project_geo_location_arr['data']['address'];
	$latitude = $project_geo_location_arr['data']['latitude'];
	$longitude = $project_geo_location_arr['data']['longitude'];
	$location_type = $project_geo_location_arr['data']['location_type'];
	$precision_code = $project_geo_location_arr['data']['precision_code'];
	$centrally_managed = $project_geo_location_arr['data']['centrally_managed'];
	$impacted_area = $project_geo_location_arr['data']['impacted_area'];
	$country = $project_geo_location_arr['data']['country'];
}
?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-AMP</title>
<style>
 #map-canvas { height: 512px; width:512px;}
 .btnstyle .back_button {
    margin-bottom: 20px;
}
.btnstyle,.back_button{
margin-bottom:20px;
}
</style>
<?php include('includes/resources.php');?>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBhh60u98hU0VtoeivLC7w66dZC2CET778&libraries=places"></script>
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<div>
			<!--project overview start-->
			<?php include('includes/project_header.php');?>	 
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head">Manage Geo Location</div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="view_project_geo_location_archive.php">Geo Coding Change Log</a>
		</div>
			<div class="project-detail-blk table-container">
				<div id="submission_msg" class="form-msg <?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg_type'];}?>">
					<?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg'];}?>
				</div>
				<div class="form-blk">
					<!--add team member block start-->
					<div id='map-canvas' style='width: 100%; height: 400px'/>
					<!--add team member block end-->
				</div>
				<div class="form-blk">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div style="margin-top:20px">
								<a id="show_list" class="btn btn-green">Show Geo-Location List</a>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 loc_indicator_marker disp-none">
							<div class="text-right" style="margin-top:20px;">
							<img src="<?php echo HOST_URL;?>/img/map-marker-red.png" title="Project" width="20"> <span class="bold">&nbsp;Project Implementation Location</span> 
							<img src="<?php echo HOST_URL;?>/img/map-marker-blue.png" title="Activity" width="20"> <span class="bold">&nbsp;Activity Implementation Location</span>
							</div>
							<div class="text-right" style="margin-top:20px;">
								<img src="<?php echo HOST_URL;?>/img/map-marker-lightred.png" title="Project" width="20"> <span class="bold">&nbsp;Project Impacted Location</span> 
								<img src="<?php echo HOST_URL;?>/img/map-marker-skyblue.png" title="Activity" width="20"> <span class="bold">&nbsp;Activity Impacted Location</span>
							</div>
						</div>
					</div>
					<div class="row location_list disp-none">
						 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						 	<div class="extra_ht"></div><div class="extra_ht"></div>
						 	<table class="table table-bordered table-striped review-listing">
								<tr class="head-gray">
									<td>Location</td>
									<td>Activity</td>
									<td>Latitude</td>
									<td>Longitude</td>
									<td>Location Type</td>
									<td>Precision Code</td>
									<td>Country</td>
									<td>Action</td>
								</tr>
								<?php if(count($project_geo_arr['data'])>0){
									$lat_lng = array();
									for($i=0; $i<count($project_geo_arr['data']); $i++){
										$activity='';
										if($project_geo_arr['data'][$i]['project_activity_id']!=""){
											$url = API_HOST_URL_PROJECT."get_project_activity.php?project_id=".$project_id."&activity_id=".$project_geo_arr['data'][$i]['project_activity_id'];  
    										$project_act_arr = requestByCURL($url);
											$activity= $project_act_arr['data']['title'];
										}
										if($project_geo_arr['data'][$i]['project_activity_id']=="" && $project_geo_arr['data'][$i]['impacted_area']=="Yes"){
											$lat_lng['project'][$project_id]['impacted'][$i]['lat'] =  $project_geo_arr['data'][$i]['latitude'];
											$lat_lng['project'][$project_id]['impacted'][$i]['lng'] =  $project_geo_arr['data'][$i]['longitude'];
										}
										else if($project_geo_arr['data'][$i]['project_activity_id']=="" && $project_geo_arr['data'][$i]['impacted_area']=="No"){
											$lat_lng['project'][$project_id]['not_impacted'][$i]['lat'] =  $project_geo_arr['data'][$i]['latitude'];
											$lat_lng['project'][$project_id]['not_impacted'][$i]['lng'] =  $project_geo_arr['data'][$i]['longitude'];
										}
									 	else if($project_geo_arr['data'][$i]['project_activity_id']!=""){
											if(!array_key_exists($project_geo_arr['data'][$i]['project_activity_id'],$lat_lng['activity'])){
												$lat_lng['activity'][$project_geo_arr['data'][$i]['project_activity_id']] = array();
											}
											
											if($project_geo_arr['data'][$i]['project_activity_id']!="" && $project_geo_arr['data'][$i]['impacted_area']=="Yes"){
												$lat_lng['activity'][$project_geo_arr['data'][$i]['project_activity_id']]['impacted'][$i]['lat'] =  $project_geo_arr['data'][$i]['latitude'];
												$lat_lng['activity'][$project_geo_arr['data'][$i]['project_activity_id']]['impacted'][$i]['lng'] =  $project_geo_arr['data'][$i]['longitude'];
											}
											if($project_geo_arr['data'][$i]['project_activity_id']!="" && $project_geo_arr['data'][$i]['impacted_area']=="No"){
												$lat_lng['activity'][$project_geo_arr['data'][$i]['project_activity_id']]['not_impacted'][$i]['lat'] =  $project_geo_arr['data'][$i]['latitude'];
												$lat_lng['activity'][$project_geo_arr['data'][$i]['project_activity_id']]['not_impacted'][$i]['lng'] =  $project_geo_arr['data'][$i]['longitude'];
											}
										}
									?>
									<tr>
										<td>
										<?php if($project_geo_arr['data'][$i]['project_activity_id']=="" && $project_geo_arr['data'][$i]['impacted_area']=="No"){?>
											<img src="<?php echo HOST_URL;?>/img/map-marker-red.png" title="Project" width="20">
										<?php } else if($project_geo_arr['data'][$i]['project_activity_id']=="" && $project_geo_arr['data'][$i]['impacted_area']=="Yes"){?>
											<img src="<?php echo HOST_URL;?>/img/map-marker-lightred.png" title="Activity" width="20">
										<?php } else if($project_geo_arr['data'][$i]['project_activity_id']!="" && $project_geo_arr['data'][$i]['impacted_area']=="No"){?>
											<img src="<?php echo HOST_URL;?>/img/map-marker-blue.png" title="Activity" width="20">
										<?php } else if($project_geo_arr['data'][$i]['project_activity_id']!="" && $project_geo_arr['data'][$i]['impacted_area']=="Yes"){?>
											<img src="<?php echo HOST_URL;?>/img/map-marker-skyblue.png" title="Activity" width="20">
										<?php }?>
										<?php echo $project_geo_arr['data'][$i]['address']?></td>
										<td><?php echo $activity;?></td>
										<td><?php echo $project_geo_arr['data'][$i]['latitude']?></td>
										<td><?php echo $project_geo_arr['data'][$i]['longitude']?></td>
										<td><?php echo $project_geo_arr['data'][$i]['location_type']?></td>
										<td><?php echo $project_geo_arr['data'][$i]['precision_code']?></td>
										<td><?php echo $project_geo_arr['data'][$i]['country']?></td>
										<td class="text-center">
										<form class="edit_location_form disp-inline" method="post">
											<input type="hidden" value="edit_location" name="edit_location"/>
											<input type="hidden" value="<?php echo $project_geo_arr['data'][$i]['project_id'];?>" name="project_id"/>
											<input type="hidden" value="<?php echo $project_geo_arr['data'][$i]['geo_location_id'];?>" name="geo_location_id"/>
											<a class="btn btn-warning edit_location" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>
										</form>
										<form class="delete_location_form disp-inline" method="post">
											<input type="hidden" value="delete_location" name="delete_location"/>
											<input type="hidden" value="<?php echo $project_geo_arr['data'][$i]['project_id'];?>" name="project_id"/>
											<input type="hidden" value="<?php echo $project_geo_arr['data'][$i]['geo_location_id'];?>" name="geo_location_id"/>
											<a class="btn btn-danger delete_location" title="Remove"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
										</form>
										</td>
									</tr>
									<?php }} else {?>
									<tr class="not_row_found text-warning bold">
										<td colspan="8">No location found for this project</td>
									</tr>
								<?php }?>
							</table>
						 </div>
					</div>
				</div>
				<div class="gray-line"></div>
				<div class="form-blk geo-loc-blk">
					 <div class="row">
						 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							 <header><h2>Search</h2></header>
							  <div class="row">
								 <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									 <div>The name of location you would like to search for this can be a town, city or country.</div><div class="extra_ht"></div>
									 <div class="input-group">
										<input type="text" class="form-control"  id="txtPlaces" placeholder="Enter a location" aria-describedby="basic-addon2">
										<span class="input-group-addon btn btn-blue" id="add_google">Search</span>
									  </div>
								  </div>
								  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center" style="font-size:24px;color:#ff5500">
								   		<div style="padding:5px; background:#ddd; width:80px; display:inline-block; margin:auto">
											OR 
								   		</div>
								   </div>
								  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<div>
											Find location by latitude longitude.
											<div style="font-size:12px; font-style:italic" class="text-danger">Search eg: 40.714224,-73.961452</div>
										</div><div class="extra_ht"></div>	
										<div id="floating-panel" class="input-group">
										  <input id="latlng" type="text" value="" class="form-control" style="width:auto" placeholder="Enter Lat-Lng">
										  <input id="submit" type="button" value="Reverse Geocode" class="btn btn-blue" style="display:table-cell">
										</div>
								   </div>
							  </div>
							  <div class="extra_ht"></div><div class="extra_ht"></div>
						</div>
					 </div>
					 <div class="gray-line"></div>
					 <form id="add_geo_loc" method="post">
					 	<input type="hidden" class="project_id" name="project_id" value="<?php echo $project_id;?>"/>
						<div class="row">
					 	<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							 <header><h3>Address</h3></header>
							 <div>Confirmation of the full address,<br/> you should check this is accurate</div>
							<div>
								<textarea class="form-control" id="geo_address" name="address"><?php if(isset($edit_mode)) { echo $address;}?></textarea>
							</div>
						</div>
						<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							<header><h3>Latitude</h3></header>
							<div>Coordinates</div><br/>
							<div>
								<textarea class="form-control" id="geo_latitude" name="latitude"><?php if(isset($edit_mode)) { echo $latitude;}?></textarea>
							</div>
						</div>
						<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							<header><h3>Longitude</h3></header>
							<div>Coordinates</div><br/>
							<div>
								<textarea class="form-control" id="geo_longitude" name="longitude"><?php if(isset($edit_mode)) { echo $longitude;}?></textarea>
							</div>
						</div>
						<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							 <header><h3>Precision</h3></header>
							 <input type="text" name="precision_code" class="form-control" value="<?php if(isset($edit_mode)) { echo $precision_code;}?>"/>
						</div>
						<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							 <header><h3>Location Type</h3></header>
							 <input type="text" name="location_type" class="form-control" value="<?php if(isset($edit_mode)) { echo $location_type;}?>"/>
						</div>
						<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							 <header><h3>Country</h3></header>
							 <input type="text" name="country" class="form-control" value="<?php if(isset($edit_mode)) { echo $country;}?>"/>
						</div>
						<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							 <header><h3>Activity</h3></header>
							<div>
								<select class="form-control" name="project_activity_id">
									<option value="">Select Activity</option>
									<?php 
										$p_activity = $project_activity_arr['data'];
										if(count($p_activity)>0){
										for($i=0; $i<count($p_activity); $i++){ 
										$activity_id = $p_activity[$i]['activity_id'];
										?>
											<option value="<?php echo $activity_id;?>" <?php if(isset($edit_mode) && $activity_id==$project_activity_id) {?> selected="selected" <?php }?>><?php echo $p_activity[$i]['title'];?></option>
									<?php }}?>
								</select>
							</div>
						</div>
						<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							 <header><h3>Centrally Managed</h3></header>
							 <input type="checkbox" name="centrally_managed" value="Yes" <?php if(isset($edit_mode) && $centrally_managed=="Confirmed") {?> checked="checked" <?php }?> id="centrally_managed"/>
						</div>
						<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
							 <header><label><input type="radio" name="impacted_area" value="No" <?php if((isset($edit_mode) && $impacted_area=="No") || !isset($edit_mode)) {?> checked="checked" <?php }?>/> <h3 style="display:inline-block">Implementation Area</h3></label></header>
							 
							 <header><label><input type="radio" name="impacted_area" value="Yes" <?php if(isset($edit_mode) && $impacted_area=="Yes" ) {?> checked="checked" <?php }?>/> <h3 style="display:inline-block">Area of Impact</h3></label></header>
							 
						</div>
					 </div>
					 	<div class="row">
							<div class="col-lg-4 col-md-3 col-sm-6 col-xs-12">
					 			<div class="extra_ht"></div><div class="extra_ht"></div><div class="extra_ht"></div>
								<input type="hidden" name="cancel_location" value="add_location"/>
								<input type="button" class="btn btn-green" value="Cancel" id="cancel_geo_loc"/>
								
								
								<input type="hidden" name="geo_location_id" value="<?php if(isset($edit_mode)){ echo $project_geo_location_id;}?>" id="geo_location_id"/>
								<input type="hidden" name="add_location" value="add_location"/>
								<input type="button" class="btn btn-blue" value="Save" id="save_geo_loc"/> <div class="form-msg disp-inline"></div>	
					 		</div>
						</div>
					 </form>
				</div>				
			</div>
			<!--add new project end-->
     	  </div>
		</div>
		<!--add new project start-->
	</div>
	</div>
	<!--main container end-->
	<?php
     #### Logic for creating polygon using coordinates===	
    function get_array_column($input_array,$col_name)
    {
      $col_array = array();	
      for($i=0;$i<count($input_array);$i++)
      {
        $col_array[] = $input_array[$i][$col_name];
      }	
      return($col_array);
    }

    function get_polygon_coordinate($input_arr)
    {
    	$final = array();
		$extra = array();
	   if(count($input_arr) > 3)
	      {	
	          $lat = get_array_column($input_arr,'lat');
	          $tmp_lat = get_array_column($input_arr,'lat');
	          sort($lat);
		      foreach ($lat as $key => $value)
		       {
		     	$key = array_search($value, $tmp_lat);
		     	$tmp[] = $input_arr[$key]; 
			       }
		       $final[] = $tmp[0];
		       $k=0;
		      for($i=1;$i<count($tmp);$i++)
		       { 
		            if($tmp[$i]['lng'] >= $tmp[$k]['lng'])
		            {
		              $final[] = $tmp[$i];
		              $k = $i;
		            }
		             else
		              $extra[] = $tmp[$i];
		       }
		       $lng = get_array_column($extra,'lng'); 
               $max_lng = max($lng);
               $lng_key = array_search($max_lng,$lng);
               $final[] = $extra[$lng_key];
               unset($extra[$lng_key]);
               $new_extra = array_values($extra);
            
              $new_lat = get_array_column($new_extra,'lat');
              $tmp_new_lat = get_array_column($new_extra,'lat');
              rsort($new_lat); 
              foreach ($new_lat as $key => $value)
		       {
		     	$rev_key = array_search($value, $tmp_new_lat);
		     	$new_tmp[] = $new_extra[$rev_key]; 
			     }
			   $p=1;
			   for($m=0;$m<count($new_tmp);$m++)
			   {
			     if($new_tmp[$m]['lng']>=$new_tmp[$p]['lng'])
			     {
			        $rev_sample_arr[] = $new_tmp[$m];
			        $p = $m;
			     }
			      else
			      {
                    $rev_extra_arr[] = $new_tmp[$m];
			      }
			           	
			   }
              $rev_sample_arr_lng = get_array_column($rev_sample_arr,'lng');
              $tmp_rev_sample_arr_lng = get_array_column($rev_sample_arr,'lng');
              rsort($rev_sample_arr_lng);
              foreach ($rev_sample_arr_lng as $key => $value) {
              	 $final_rev_key = array_search($value,$tmp_rev_sample_arr_lng);
               	 $final[] = $rev_sample_arr[$final_rev_key];
               } 


			   foreach($rev_extra_arr as $key=>$value)
			   {
			   	$final[] = $rev_extra_arr[$key];
			   }
	         return($final);
	     }
          else
 	       return($input_arr); 
     }
	 include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script src="<?php echo HOST_URL?>js/manage_team_member.js"></script>	
<script type='text/javascript'>
$('#show_list').click(function(){
	if($('.location_list').hasClass('disp-none')){
		$(this).text("Hide Geo-Location List");
		$(this).removeClass('btn-green').addClass('btn-blue');
		$('.location_list,.loc_indicator_marker').removeClass('disp-none');
	}
	else{
		$(this).text("Show Geo-Location List");
		$(this).removeClass('btn-blue').addClass('btn-green');
		$('.location_list,.loc_indicator_marker').addClass('disp-none');
	}
});
setTimeout(function(){
	$('#submission_msg').html("");
},10000);
	
var locations = [];
var dummy_search_locations = [];
var map;
var markers = [];

</script>

<script>

function init(focus_lat,focus_long){
	map = new google.maps.Map(document.getElementById('map-canvas'), {
	zoom: 10,
	center: new google.maps.LatLng(focus_lat, focus_long)
});
<?php if(count($project_geo_arr['data'])>0){  // draw polygon if data exists?>
    <?php foreach($lat_lng as $key => $obj){
	//if project location exists
	if($key=="project"){
	foreach($obj as $pKey => $pObj){
		//if project not_imapacted location exists
		if(array_key_exists('not_impacted',$pObj)){
           $sorted_pObj = array_values($pObj['not_impacted']);	
           $final = get_polygon_coordinate($sorted_pObj);
			?>
		var triangleCoords = [
			<?php foreach($final as $locKey => $locObj){?>	
			{lat: <?php echo $locObj['lat']?>, lng: <?php echo $locObj['lng']?>},
			<?php }?>
		];
		 var bermudaTriangle = new google.maps.Polygon({
			  paths: triangleCoords,
			  strokeColor: '#FF0000',
			  strokeOpacity: 0.9,
			  strokeWeight: 2,
			  fillColor: '#FF6666',
			  fillOpacity: 0.5
		  });
		  bermudaTriangle.setMap(map);	
	<?php } 
		//if project imapacted location exists
		if(array_key_exists('impacted',$pObj)){
            $sorted_pObj = array_values($pObj['impacted']);	
            $final = get_polygon_coordinate($sorted_pObj);
			?>
		var triangleCoords = [
			<?php foreach($final as $locKey => $locObj){?>	
			{lat: <?php echo $locObj['lat']?>, lng: <?php echo $locObj['lng']?>},
			<?php }?>
		];
		 var bermudaTriangle = new google.maps.Polygon({
			  paths: triangleCoords,
			  strokeColor: '#FF0000',
			  strokeOpacity: 0.9,
			  strokeWeight: 2,
			  fillColor: '#FF0000',
			  fillOpacity: 0.10
		  });
		  bermudaTriangle.setMap(map);	
	<?php  }}}
	//if project location exists
	if($key=="activity"){
	foreach($obj as $pKey => $pObj){
		//if project not_imapacted location exists
		if(array_key_exists('not_impacted',$pObj)){
            $sorted_pObj = array_values($pObj['not_impacted']);	
            $final = get_polygon_coordinate($sorted_pObj);
			?>
		var triangleCoords = [
			<?php foreach($final as $locKey => $locObj){?>	
			{lat: <?php echo $locObj['lat']?>, lng: <?php echo $locObj['lng']?>},
			<?php }?>
		];
		 var bermudaTriangle = new google.maps.Polygon({
			  paths: triangleCoords,
			  strokeColor: '#285494',
			  strokeOpacity: 0.9,
			  strokeWeight: 2,
			  fillColor: '#6887B4',
			  fillOpacity: 0.5
		  });
		  bermudaTriangle.setMap(map);	
	<?php } 
		//if project imapacted location exists
		if(array_key_exists('impacted',$pObj)){
            $sorted_pObj = array_values($pObj['impacted']);	
            $final = get_polygon_coordinate($sorted_pObj);
			?>
		var triangleCoords = [
			<?php foreach($final as $locKey => $locObj){?>	
			{lat: <?php echo $locObj['lat']?>, lng: <?php echo $locObj['lng']?>},
			<?php }?>
		];
		 var bermudaTriangle = new google.maps.Polygon({
			  paths: triangleCoords,
			  strokeColor: '#285494',
			  strokeOpacity: 0.9,
			  strokeWeight: 2,
			  fillColor: '#285494',
			  fillOpacity: 0.10
		  });
		  bermudaTriangle.setMap(map);	
	<?php  }}}?>
	<?php }?>
 
  // Add a listener for the click event.
  //bermudaTriangle.addListener('click', showArrays);
  infoWindow = new google.maps.InfoWindow;
<?php }?>
  
	  				
  var num_markers = locations.length;
  for (var i = 0; i < num_markers; i++) {  
    markers[i] = new google.maps.Marker({
      position: {lat:locations[i][1], lng:locations[i][2]},
      map: map,
      html: locations[i][0],
      id: i,
	  icon: locations[i][4],
    });
     
	
	  	  
    google.maps.event.addListener(markers[i], 'click', function(event){
      var latitude = event.latLng.lat();
      var longitude = event.latLng.lng();
	  var contentString = "<div style='padding:10px'>";
	      contentString = contentString + "<div>"+this.html+"</div>";
		  contentString = contentString + "<div>Latitude : "+latitude+"</div>";
		  contentString = contentString + "<div>Longitude : "+longitude+"</div>";
		  
		  contentString = contentString+"</div>";
	  
	  var infowindow = new google.maps.InfoWindow({
        id: this.id,
        content:contentString,
        position:this.getPosition()
      });
      google.maps.event.addListenerOnce(infowindow, 'closeclick', function(){
        markers[this.id].setVisible(true);
      });
      this.setVisible(false);
      infowindow.open(map);
    });
  }
  
  var num_markers = dummy_search_locations.length;
  for (var i = 0; i < num_markers; i++) {  
    markers[i] = new google.maps.Marker({
      position: {lat:dummy_search_locations[i][1], lng:dummy_search_locations[i][2]},
      map: map,
      html: dummy_search_locations[i][0],
      id: i,
	  icon: dummy_search_locations[i][4],
    });
      
    google.maps.event.addListener(markers[i], 'click', function(){
      var infowindow = new google.maps.InfoWindow({
        id: this.id,
        content:this.html,
        position:this.getPosition()
      });
      google.maps.event.addListenerOnce(infowindow, 'closeclick', function(){
        markers[this.id].setVisible(true);
      });
      this.setVisible(false);
      infowindow.open(map);
    });
  }
}
</script>
<?php if(count($project_geo_arr['data'])>0){
	for($i=0; $i<count($project_geo_arr['data']); $i++){ 
		$activity_title = "";
		if($project_geo_arr['data'][$i]['project_activity_id']!="") { 
			$url = API_HOST_URL_PROJECT."get_project_activity.php?project_id=".$project_id."&activity_id=".$project_geo_arr['data'][$i]['project_activity_id'];; 
			$project_activity_arr = requestByCURL($url);
			$activity_title = $project_activity_arr['data']['title'];
		}
?>
<script>
	var tooltip = "<?php if($project_geo_arr['data'][$i]['project_activity_id']!="") { echo "Activity : ".$activity_title; } else { echo "Project : ".$project_title; } ?>";
	var newLoc = new Array(tooltip,<?php echo $project_geo_arr['data'][$i]['latitude']?>,<?php echo $project_geo_arr['data'][$i]['longitude']?>,6,"<?php if($project_geo_arr['data'][$i]['project_activity_id']=="" && $project_geo_arr['data'][$i]['impacted_area']=="No") { echo HOST_URL."img/map-marker-red.png"; } else if($project_geo_arr['data'][$i]['project_activity_id']=="" && $project_geo_arr['data'][$i]['impacted_area']=="Yes"){ echo HOST_URL."img/map-marker-lightred.png"; } else if($project_geo_arr['data'][$i]['project_activity_id']!="" && $project_geo_arr['data'][$i]['impacted_area']=="No") { echo HOST_URL."img/map-marker-blue.png"; } else if($project_geo_arr['data'][$i]['project_activity_id']!="" && $project_geo_arr['data'][$i]['impacted_area']=="Yes") { echo HOST_URL."img/map-marker-skyblue.png"; } ?>");
	/*var newLoc = new Array(tooltip,<?php echo $project_geo_arr['data'][$i]['latitude']?>,<?php echo $project_geo_arr['data'][$i]['longitude']?>,6,"<?php if($project_geo_arr['data'][$i]['project_activity_id']=="" && $project_geo_arr['data'][$i]['impacted_area']=="No") { echo HOST_URL."img/map-marker-red1.png"; } else if($project_geo_arr['data'][$i]['project_activity_id']!="" && $project_geo_arr['data'][$i]['impacted_area']=="Yes"){ echo HOST_URL."img/map-marker-orange1.png"; } else if($project_geo_arr['data'][$i]['project_activity_id']!="" && $project_geo_arr['data'][$i]['impacted_area']=="No") { echo HOST_URL."img/map-marker-blue1.png"; } else if($project_geo_arr['data'][$i]['project_activity_id']!="" && $project_geo_arr['data'][$i]['impacted_area']=="Yes") { echo HOST_URL."map-marker-yellow1.png"; } ?>");*/
	locations.push(newLoc);
	</script>			
<?php 	} ?>
<script type="text/javascript">
init("<?php echo $project_geo_arr['data'][0]['latitude']?>","<?php echo $project_geo_arr['data'][0]['longitude']?>");
</script>
<?php
}else{?>
<script>
	init(0,0);
</script>
<?php }?>


<script type="text/javascript">
	var geocoder = new google.maps.Geocoder;
	var infowindow_oth = new google.maps.InfoWindow;
		 
	document.getElementById('submit').addEventListener('click', function() {
	   geocodeLatLng(geocoder, map, infowindow_oth);
	});
	
	function geocodeLatLng(geocoder, map, infowindow) {
		dummy_search_locations = [];
		var input = document.getElementById('latlng').value;
		var latlngStr = input.split(',', 2);
		var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
		var address = '';
		var lat= parseFloat(latlngStr[0]);
		var lng = parseFloat(latlngStr[1]);
		geocoder.geocode({'location': latlng}, function(results, status) {
			var newLoc = new Array(results[1].formatted_address,lat,lng,4,"<?php echo HOST_URL;?>img/flag.png");
			dummy_search_locations.push(newLoc);
			address = results[1].formatted_address;
			var marker = new google.maps.Marker({
				id: this.id,
				position: latlng,
				map: map,
				zoom: 10,
				icon: "<?php echo HOST_URL;?>img/flag.png",
		 	});
			$('#latlng').val("");
			$('#geo_address').val(address);
			$('#geo_latitude').val(lat);
			$('#geo_longitude').val(lng);
			init(parseFloat(latlngStr[0]),parseFloat(latlngStr[1]));
		});
	}
	var curr_geo_loc = new Object();
	google.maps.event.addDomListener(window, 'load', function () {
		var places = new google.maps.places.Autocomplete(document.getElementById('txtPlaces'));
		google.maps.event.addListener(places, 'place_changed', function () {
			var place = places.getPlace();
			var address = place.formatted_address;
			var latitude = place.geometry.location.lat();
			var longitude = place.geometry.location.lng();
			curr_geo_loc["address"]=  address;
			curr_geo_loc["latitude"]=  latitude;
			curr_geo_loc["longitude"]=  longitude;
		});
	});
	
	/*--add google map marker and focus on search location--*/
	$('#add_google').click(function(){
		dummy_search_locations = [];
		if(curr_geo_loc["address"]!="" && curr_geo_loc["latitude"]!="" && curr_geo_loc["longitude"]!=""){
			$('#txtPlaces').val("");
			$('#geo_address').val(curr_geo_loc["address"]);
			$('#geo_latitude').val(curr_geo_loc["latitude"]);
			$('#geo_longitude').val(curr_geo_loc["longitude"]);
			var split_add = curr_geo_loc["address"].split(",");
			var tooltip_add = split_add[0];
			var newLoc = new Array(tooltip_add,curr_geo_loc["latitude"],curr_geo_loc["longitude"],4,"<?php echo HOST_URL;?>img/flag.png");
			dummy_search_locations.push(newLoc);
			init(curr_geo_loc["latitude"],curr_geo_loc["longitude"]);
		}
	});
	
	/*--save geo location--*/
	$('#save_geo_loc').click(function(){
		var error = '';
		
		if($('#geo_address').val()=="" || $('#geo_latitude').val()=="" || isNaN($('#geo_latitude').val()) || $('#geo_longitude').val()=="" || isNaN($('#geo_longitude').val())){
			error="error";
			error_msg="Choose a correct location.";
		}
		else{
			$('#add_geo_loc').submit();
		}
	
		if(error=="error"){
			$('#add_geo_loc').find('.form-msg').addClass('error');
			$('#add_geo_loc').find('.form-msg').html(error_msg);
			setTimeout(function(){
				$('#add_geo_loc').find('.form-msg').html("");
			},5000);	
		}
	});
	
	/*delete location*/
	$(document).on('click','.delete_location',function(){
		if(confirm("Are you sure to delete this location?")){
			$(this).closest('.delete_location_form').submit();
		}
	});
	
	/*edit location*/
	$(document).on('click','.edit_location',function(){
		$(this).closest('.edit_location_form').submit();
	});
	
	
	/*edit location*/
	$(document).on('click','#cancel_geo_loc',function(){
		$('#add_geo_loc').find('input[type="text"],textarea,#geo_location_id,select').val("");
		$('#centrally_managed').prop('checked',false);
	});
	
</script>
<?php if(isset($edit_mode)){?>
<script>
init(<?php echo $latitude;?>,<?php echo $longitude;?>);
</script>
<?php }?>
<?php unset($_SESSION['form_msg']);?>
</body>
</html>
