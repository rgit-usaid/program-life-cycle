<?php include('config/functions.inc.php');
##==validate user====
validate_user();

###request for get single project details using project id ===========
$project_id = '';
if(isset($_REQUEST['project_id'])){
	$_SESSION['project_id'] = $_REQUEST['project_id'];
}

if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];
}

###request for remove project================
if(isset($_REQUEST['remove']))
{
	$project_id = trim($_REQUEST['project_id']);
	$activity_id = trim($_REQUEST['activity_id']);
	$url = API_HOST_URL_PROJECT."remove_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id; 
    $remove = requestByCURL($url);
	if($remove['status']==200)
  	{  
  		header("location:list_activity");	
  	} 
}


if($project_id!="")
{
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);
	$operating_unit_id  = $project_arr['data']['operating_unit_id'];
	 
	$operating_unitinfo_url = API_HOST_URL_PHOENIX."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;  
	$operating_unitinfo = requestByCURL($operating_unitinfo_url);
	
    $url = API_HOST_URL_PROJECT."api_demo.php?stage";  
  	$project_stage_arr = requestByCURL($url);
	
	$url = API_HOST_URL_PROJECT."get_all_project_activity.php?project_id=".$project_id;  
  	$project_activity_arr = requestByCURL($url);
}

$project_stage_id = '';
$environmental_threshold = '';
$gender_threshold = '';
if(isset($project_arr)) {
	$project_stage_id = $project_arr['data']['project_stage_id'];
	$environmental_threshold = $project_arr['data']['environmental_threshold'];
	$gender_threshold = $project_arr['data']['gender_threshold'];
}

?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-AMP</title>
<link rel="stylesheet" href="<?php echo HOST_URL;?>css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo HOST_URL;?>css/responsive.dataTables.min.css">
<link href="<?php echo HOST_URL;?>css/plugin/typeaheadsearch/styles.css" rel="stylesheet" />
<?php include('includes/resources.php');?> 

</head>
<body>
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
	<!--main container start-->
    <div class="main-container container-fluid" id="main-content">		
	  <?php include('includes/project_header.php');?>
	  <a id="add_new_project" href="add_new_activity" class="btn btn-blue pull-right">Add New Activity <i class="fa fa-th"></i></a>
	  <div class="clear" style="height:10px;"></div>
	  <!--project table container start-->
	  <div class="tbl-block">
		<div class="tbl-caption">
		<div class="tbl-content-head">Project Activity</div>
		<div class="tbl-content-btn">
			<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
			<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
		</div>
		<div class="clear"></div>
	</div>
	<div class="table-container">
	<table id="projects_table" class="display table table-bordered table-striped" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th class="comm-width">Activity Id</th>
			<th class="text-center stage">Activity Title</th>
			<th class="text-left">Funding Type</th>
			<th class="text-center stage">Benefiting Country</th>
			<th class="text-center stage">Budget Center</th>
			<th class="text-center comm-width">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$p_activity = $project_activity_arr['data'];
		if(count($p_activity)>0){
			for($i=0; $i<count($p_activity); $i++){
			$activity_id = $p_activity[$i]['activity_id'];
			
			
			$url = API_HOST_URL_PHOENIX."get_unique_obligate_fund_by_type.php?ledger_type_id=".$activity_id."&ledger_type=Project_Activity";  
			$activity_fund_strip  = requestByCURL($url);
			$fund_strip='';
			if(count($activity_fund_strip['data'])>0){
				for($j=0;$j<count($activity_fund_strip['data']);$j++){
					$fund_strip.= $activity_fund_strip['data'][$j]['fund_code'];
					$b_year =  $activity_fund_strip['data'][$j]['fund_beginning_fiscal_year'];
					$e_year =  $activity_fund_strip['data'][$j]['fund_ending_fiscal_year'];
					$pe_id =  $activity_fund_strip['data'][$j]['program_element_id'];
					$fund_id =  $activity_fund_strip['data'][$j]['fund_id'];
					
					if($b_year!="" && $e_year!=""){
						$fund_strip.=' FY '.$b_year.' - '.$e_year;
					}
					
					$fund_strip.=' '.$pe_id.', ';
				}
			}
			$fund_strip = substr_replace($fund_strip,"","-2");
		?>
		
		<tr>
			<td><?php echo $p_activity[$i]['activity_id'];?></td>
			<td class="text-center"><?php echo $p_activity[$i]['title'];?></td>
			<td class="text-left"><?php echo $fund_strip;?></td>
			<td class="text-center"><?php echo $p_activity[$i]['activity_benefitting_country'];?></td>
			<td class="text-center"><?php echo $operating_unitinfo['data']['operating_unit_abbreviation'];?> (<?php echo $operating_unitinfo['data']['operating_unit_description'];?>)</td>
			<td class="text-center"> 
				 <form method="post" action="add_new_activity" class="form-inline disp-inline"> 
					<input type="hidden" name="project_id" class="project_id" value="<?php echo $project_id;?>">
					<input type="hidden" name="activity_id" class="activity_id" value="<?php echo $activity_id;?>">
					<input type="submit" name="details" value="Details" class="project_btn">
				 </form>
				 |
				 <form method="post" class="form-inline disp-inline">
					<input type="hidden" name="project_id" value="<?php echo $project_id;?>" > 
					<input type="hidden" name="activity_id" class="activity_id" value="<?php echo $activity_id;?>">
					<input type="submit" name="remove" value="Remove" class="project_btn" onClick="return window.confirm('Are you sure you want to remove this project activity');">
				 </form> 
			</td>
		</tr>
		<?php }}?>
	</tbody>
	</table>
	</div>
	</div>
	  <!--project table container end-->
    </div>
	<?php include('includes/footer.php');?>
	<script src="<?php echo HOST_URL?>js/main.js"></script>
	<script src="<?php echo HOST_URL;?>js/jquery.dataTables.min.js"></script>
	<script src="<?php echo HOST_URL;?>js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" class="init">
	$(document).ready(function() {
		$('#projects_table').DataTable({
			 responsive: true
		});
	});
	</script>
</body>
</html>
