<?php include('config/functions.inc.php');
##==validate user====
validate_user();

###request for get single project details using project id ===========
$project_id = '';
$activity_id = '';
if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];
}

if(isset($_SESSION['project_id']) && isset($_REQUEST['activity_id']))
{	
	$_SESSION['activity_id'] = $activity_id = $_REQUEST['activity_id'];
}

if(isset($_SESSION['project_id']) && isset($_SESSION['activity_id']))
{	
	$activity_id = $_SESSION['activity_id'];
}


if($project_id!="" && $activity_id!=""){
	
	$url = API_HOST_URL_PROJECT."get_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id;  
    $project_activity_arr = requestByCURL($url);
	
	//$url = "http://localhost/usaid/api/get_archive_activity_pe.php?activity_id=000051-001";
	$url = API_HOST_URL_PROJECT."get_archive_activity_pe.php?activity_id=".$activity_id."";  
	$archive_activity_pe_arr = requestByCURL($url);
	
}

$page_type="activity_pages";
?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-AMP</title>
<?php include('includes/resources.php');?>

</head>
<style>
.pointer{
cursor:pointer;}
.btnstyle .back_button{
margin-bottom:20px;
}
.tablegap {
    margin: 20px;
    border: 1px solid gray;
    padding: 10px;
}
</style>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<div>
			<?php include('includes/activity_header.php');?>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head"><?php if($activity_id!=""){  echo "You are viewing program element archive";} else echo "Add New Activity Details";?></div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right btnstyle">
		<a href="add_activity_program_element.php"><button type="button" class="btn btn-primary back_button">Back to Program Element</button></a>
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
					if(count($archive_activity_pe_arr['data'])>0)
					{
						for($k=0; $k<count($archive_activity_pe_arr['data']); $k++)
						{  ?>
						<tr>
							<td align="center"><?php echo dateTimeFormat($archive_activity_pe_arr['data'][$k]['archive_on']);  ?></td>
							<td class="text-center comm-width"><?php echo $archive_activity_pe_arr['data'][$k]['modified_by']; ?></td>
							<td class="text-center text-center"><i class="fa fa-chevron-circle-down pointer show_table" aria-hidden="true"></i></td>
						</tr>
						<tr class="disp-none">
						<td colspan="3">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right" style="border:1px solid #a9a9a9;">
										<table class="prgm_elems_info table  table-striped text-left" style="margin-top:30px;">
											<tbody>
											<tr class="prgm_elem_info">
												<th class="elem_label">Program Element Code</th>
												<th class="elem_label">Program Element Name</th>
												<th class="elem_ip">Percentage</th>
											</tr>
									<?php 
										//$url = "http://localhost/usaid/api/get_archive_activity_program_element.php?archive_id=".$archive_activity_pe_arr['data'][$k]['id']."";
										$url = API_HOST_URL_PROJECT."get_archive_activity_program_element.php?archive_id=".$archive_activity_pe_arr['data'][$k]['id']."";  
										$archive_program_element_arr = requestByCURL($url);
										for($i=0; $i<count($archive_program_element_arr['data']); $i++)
										{ 
									?>		
											<tr class="prgm_elem_info saved_data">
												<td class="elem_label"><?php echo $archive_program_element_arr['data'][$i]['program_element_code']; ?></td>
												<td class="elem_label"><?php echo $archive_program_element_arr['data'][$i]['program_element_name']; ?></td>
												<td class="elem_ip"><?php echo $archive_program_element_arr['data'][$i]['percentage']; ?></td>
											</tr>
									<?php } ?>		
										</tbody>
						    		</table>
								</div>
							</td>
						</tr> 
					<?php } 
					} else { ?>	
						<tr>
							<td colspan="3" align="center">No Archive Data </td>
							
						</tr>
						<?php } ?>
					</tbody>
				</table>
		</div>
		<!--add new project start-->
	</div>
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
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
