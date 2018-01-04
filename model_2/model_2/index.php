<?php include('config/functions.inc.php');
##==unset proejct_id===
unset($_SESSION['project_id']);
if(isset($_POST['username']) && $_POST['username']!=""){
	$_SESSION['user'] =$_REQUEST['employee_id'];
	$_SESSION['first_last_name'] =$_REQUEST['first_last_name'];
}

if((!isset($_SESSION['user']))){
	header("Location:login");
}

##get all project details from api==========
function _isCurl()
{
    return function_exists('curl_version');
}

if(isset($_REQUEST['employee_id'])){
	$employee_id=$_REQUEST['employee_id'];
}
else{
	$employee_id=$_SESSION['user'];
}
###if curl is enable then get all projects==================    
if (_iscurl())
{
    //curl is enabled
    $url = API_HOST_URL_PROJECT."get_employee_project.php?employee_id=".$employee_id;  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
  	$project_arr = json_decode($output,true); 
  	$url = SITE_PATH."usaid/api_demo.php?stage";  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output_stage = curl_exec($ch);
    curl_close($ch);
  	$project_stage_arr = json_decode($output_stage,true);              
}
else{
     //
}

###request for remove project================
if(isset($_REQUEST['remove']))
{
	$project_id = trim($_REQUEST['project_id']);
	$url = API_HOST_URL_PROJECT."remove_project.php?project_id=".$project_id."";  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output_remove = curl_exec($ch);
    curl_close($ch);
    $remove = json_decode($output_remove,true);
  	if($remove['status']==200)
  	{  
  		header("location:home");	
  	} 
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
	  <div class="search_project">
		<a id="find_project" class="btn btn-blue">Find Projects <i class="fa fa-search"></i></a> 
			<div id="search_div" style="position:relative; width:500px">
				<input type="text" name="find_all_project" id="find_all_project" class="form-control" autocomplete="off"/>
				<div class="ajax_data">
					
				</div>
			</div>
		<a id="add_new_project" href="add_new_project" class="btn btn-blue pull-right">Add New Project <i class="fa fa-th"></i></a>
	  </div>
	  <form id="selected_project" method="post" action="add_new_project">
		<input type="hidden" name="project_id" value="" class="project_id"/>
		<input type="hidden" name="project_stage_id" value="" class="project_stage_id"/>
		<input type="hidden" name="details" value="Details">
	  </form>
	  <div class="add_txt">Your dashboard will display projects you have added or where you are a team member.</div>
	  <div class="add_txt">Click on the header to sort results.</div>
	  <div class="clear"></div>
	  <!--project table container start-->
	  <div class="tbl-block">
		<div class="tbl-caption">
		<div class="tbl-content-head">My Projects</div>
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
			<th class="comm-width">Project Id</th>
			<th>Project Title</th>
			<th class="text-center comm-width">Approved Budget</th>
			<th class="text-center stage">Stage</th>
			<th class="text-center comm-width">Next Review</th>
			<th class="text-center comm-width">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$p_stages = $project_stage_arr['data'];
		
		$p_color=array("#e4e2e0","#fff1d2","#f9dede","#e1f3f8","#e7f4e4","#94bfa2","#4aa564");
		$p_st_wdth=array("10%","20%","30%","50%","60%","85%","90%");
		$p_publish="yes";
		 
		for($i=0; $i<count($project_arr['data']); $i++){
			$rand_val = rand(0,6);
			$p_budget=rand(10000,99999999);
		?>
		<tr>
			<td><?php echo $project_arr['data'][$i]['project_id'];?></td>
			<td><?php echo $project_arr['data'][$i]['title'];?></td>
			<td class="text-right">$<?php echo number_format($p_budget);?></td>
			<td class="text-center" style="max-width:300px; position:relative; z-index:9999; "><div style="position:absolute;width:<?php echo $project_arr['data'][$i]['stage_percentage'].'%';?>; background:#a5d3d1; z-index:-1;">&nbsp;</div><?php echo $project_arr['data'][$i]['stage_name'];?></td>
			<td class="text-right" title="MM/DD/YYYY"><?php echo $project_arr['data'][$i]['next_review_date'];?></td>
			<td class="text-center"> 
				 <form method="post" action="add_new_project" class="form-inline disp-inline"> 
					<input type="hidden" name="project_id" class="project_id" value="<?php echo $project_arr['data'][$i]['project_id']; ?>">
					<input type="submit" name="details" value="Details" class="project_btn">
				 </form>
				 |
				 <form method="post" class="form-inline disp-inline">
					<input type="hidden" name="project_id" value="<?php echo $project_arr['data'][$i]['project_id']; ?>" > 
					<input type="submit" name="remove" value="Remove" class="project_btn" onClick="return window.confirm('Are you sure you want to remove this project');">
				 </form> 
			</td>
		</tr>
		<?php $pid=$pid+1; }?>
	</tbody>
	</table>
	</div>
	</div>
	  <!--project table container end-->
    </div>
	<?php include('includes/footer.php');?>
	<script src="<?php echo HOST_URL?>js/main.js"></script>
	<script src="<?php echo HOST_URL?>js/home.js"></script>
	<script src="<?php echo HOST_URL;?>js/jquery.dataTables.min.js"></script>
	<script src="<?php echo HOST_URL;?>js/dataTables.responsive.js"></script>
	<script type="text/javascript" language="javascript" class="init">
	$(document).ready(function() {
		$('#projects_table').DataTable({
			 responsive: true
		});
	});

	
	/*toggle search input box*/
	$('#find_project').click(function(){
		if($('#search_div').css('display')=="none"){
			$('#search_div').css({display:'inline-block'});
		}
		else{
			$('#search_div').css({display:'none'});
		}
	});

	</script>
</body>
</html>
