<?php include('config/functions.inc.php');
##==validate user====
validate_user();
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

?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title><?php echo TITLE;?></title>
<style>
 #map-canvas { height: 512px; width:512px;}
 .pointer{
cursor:pointer;}
.btnstyle .back_button{
margin-bottom:20px;
}
.project-detail-blk .form-blk {
    margin-bottom:10px !important;
}
</style>
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
			<div class="extra_ht"></div><div class="extra_ht"></div>	 	 
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head">Geo Location Archive</div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="project-detail-blk table-container">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="add_project_geo_location.php"><button type="button" class="btn btn-primary back_button">Back to  Geo Coding</button></a>
		</div>
				<div id="submission_msg" class="form-msg <?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg_type'];}?>">
					<?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg'];}?>
				</div>
				
				<table id="projects_table" class="display table table-bordered table-striped" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th class="text-center comm-width">Archive Date</th>
						<th class="text-center comm-width">Archive By</th>
						<th class="text-center comm-width">View</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$url = API_HOST_URL_PROJECT."get_all_archive_geo.php?project_id=".$project_id."";  
					$archive_geo_arr = requestByCURL($url);
					if(count($archive_geo_arr['data'])>0)
					{
						for($k=0; $k<count($archive_geo_arr['data']); $k++)
						{  ?>
						<tr>
							<td class="text-center"><?php echo dateTimeFormat($archive_geo_arr['data'][$k]['archive_on']);  ?></td>
							<td class="text-center comm-width"><?php echo $archive_geo_arr['data'][$k]['modified_by']; ?></td>
							<td class="text-center"><i class="fa fa-chevron-circle-down pointer show_table" aria-hidden="true"></i></td>
						</tr>
						<tr class="disp-none">
							<td colspan="3">
							  <div class="form-blk">
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<div class="text-left" style="margin-top:10px;">
											<img src="img/map-marker-lightred.png" title="Project" width="20"> <span class="bold">&nbsp;Project Impacted Location</span> 
											<img src="img/map-marker-skyblue.png" title="Activity" width="20"> <span class="bold">&nbsp;Activity Impacted Location</span>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 loc_indicator_marker">
										<div class="text-right" style="margin-top:10px;">
											<img src="img/map-marker-red.png" title="Project" width="20"> <span class="bold">&nbsp;Project Implementation Location</span> 
											<img src="img/map-marker-blue.png" title="Activity" width="20"> <span class="bold">&nbsp;Activity Implementation Location</span>
										</div>
									</div>
								</div>
								<div class="row location_list">
									 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="extra_ht"></div><div class="extra_ht"></div>
										<table class="table table-bordered table-striped review-listing">
										<tbody>
											<tr class="head-gray">
												<td style="width:220px">Location</td>
												<td>Activity</td>
												<td>Latitude</td>
												<td>Longitude</td>
												<td>Location Type</td>
												<td>Precision Code</td>
												<td>Country</td>
											</tr>
											<?php
											$url = API_HOST_URL_PROJECT."get_all_archive_project_geo_location.php?archive_geo_id=".$archive_geo_arr['data'][$k]['id']."";  
											$archive_project_geo_location_arr = requestByCURL($url);
											for($i=0; $i<count($archive_project_geo_location_arr['data']); $i++)
											{ 
											?>
											<tr>
												<td><?php if($archive_project_geo_location_arr['data'][$i]['project_activity_id']=="" && $archive_project_geo_location_arr['data'][$i]['impacted_area']=="No"){?>
													<img src="<?php echo HOST_URL;?>/img/map-marker-red.png" title="Project" width="20">
												<?php } else if($archive_project_geo_location_arr['data'][$i]['project_activity_id']=="" && $archive_project_geo_location_arr['data'][$i]['impacted_area']=="Yes"){?>
													<img src="<?php echo HOST_URL;?>/img/map-marker-lightred.png" title="Activity" width="20">
												<?php } else if($archive_project_geo_location_arr['data'][$i]['project_activity_id']!="" && $archive_project_geo_location_arr['data'][$i]['impacted_area']=="No"){?>
													<img src="<?php echo HOST_URL;?>/img/map-marker-blue.png" title="Activity" width="20">
												<?php } else if($archive_project_geo_location_arr['data'][$i]['project_activity_id']!="" && $archive_project_geo_location_arr['data'][$i]['impacted_area']=="Yes"){?>
													<img src="<?php echo HOST_URL;?>/img/map-marker-skyblue.png" title="Activity" width="20">
												<?php }?>
												<?php echo $archive_project_geo_location_arr['data'][$i]['address']?></td>
												<td><?php echo $archive_project_geo_location_arr['data'][$i]['project_activity_id']; ?></td>
												<td><?php echo $archive_project_geo_location_arr['data'][$i]['latitude']; ?></td>
												<td><?php echo $archive_project_geo_location_arr['data'][$i]['longitude']; ?></td>
												<td><?php echo $archive_project_geo_location_arr['data'][$i]['location_type']; ?></td>
												<td><?php echo $archive_project_geo_location_arr['data'][$i]['precision_code']; ?></td>
												<td><?php echo $archive_project_geo_location_arr['data'][$i]['country']; ?></td>
											</tr>
											<?php } ?>
											</tbody>
										</table>
									 </div>
								</div>
				</div>   
							</td>
						</tr>
				<?php 	}
					 } else { ?>
						<tr>
							<td colspan="3" align="center">No Archive Data </td>
						</tr>
					<?php }?>		
					</tbody>
				</table>
			<!--add new project end-->
     	  </div>
		</div>
		<!--add new project start-->
	</div>
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script src="<?php echo HOST_URL?>js/manage_team_member.js"></script>	

<script>
$(document).ready(function(){
	$('.show_table').click(function() {
	$(this).closest("tr").next().toggleClass("disp-none");
    if ($(this).hasClass('fa-chevron-circle-down')){
        $(this).removeClass('fa-chevron-circle-down').addClass('fa-chevron-circle-up');
    }
	 else {
         $(this).addClass('fa-chevron-circle-down').removeClass('fa-chevron-circle-up');
      }
});
});
</script>
</body>
</html>
