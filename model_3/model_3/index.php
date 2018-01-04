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
  	$project_arr = requestByCURL($url); 
	
  	$url = API_HOST_URL_PROJECT."api_demo.php?stage";  
  	$project_stage_arr = requestByCURL($url);              
}

###request for remove project================
if(isset($_REQUEST['remove']))
{
	$project_id = trim($_REQUEST['project_id']);
	$url = API_HOST_URL_PROJECT."remove_project.php?project_id=".$project_id."";
    $remove = requestByCURL($url);
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
<title><?php echo TITLE;?></title>
<link rel="stylesheet" href="<?php echo HOST_URL;?>css/jquery.dataTables.min.css">
<link rel="stylesheet" href="<?php echo HOST_URL;?>css/responsive.dataTables.min.css">
<?php include('includes/resources.php');?> 

</head>
<body>
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
	<!--main container start-->
    <div class="main-container usa-content styleguide-content usa-content" id="main-content">		
	  <div class="search_project">
		<a id="add_new_project" href="add_new_project" class="href-btn-blue pull-left"><i class="fa fa-th"></i> Add New Project</a>
		<form id="selected_project" class="usa-search disp-inline pull-right usa-search-small search" autocomplete="off" method="post" action="add_new_project">
        <div role="search">
          <label class="usa-sr-only" for="search-field">Search medium</label>
          <input type="hidden" name="project_id" value="" class="project_id"/>
		  <input type="hidden" name="project_stage_id" value="" class="project_stage_id"/>
		  <input type="hidden" name="details" value="Details">
		  <input id="find_all_project" type="search" name="search">
          <button type="submit" id="find_project" tabindex="-1">
            <span class="usa-search-submit-text"></span>
          </button>
		  <div class="ajax_data">
					
		  </div>
        </div>
      </form>
	  <div class="clear"></div>
	  </div>
	  <div><p>Your dashboard displays projects for the Operating Unit you have been assigned to in HR Connect.</p></div>
	  <div class="extra_ht"></div><div class="extra_ht"></div>
	  
	  <div class="clear"></div>
	  <!--project table container start-->
	  <div class="tbl-block">
		<div class="tbl-caption">
		<div class="tbl-content-head">My Projects</div>
		<div class="add_txt">Click on the header to sort results.</div>
		<div class="clear"></div>
	  </div>
	<div class="table-container">
	<table id="projects_table" class="display table table-bordered table-striped" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th class="comm-width">Project Id</th>
			<th>Project Title</th>
			<th class="text-center comm-width">Estimated Fund</th>
			<th class="text-center stage">Stage</th>
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
			<td class="text-center"> 
				 <form method="post" action="add_new_project" class="form-inline disp-inline"> 
					<input type="hidden" name="project_id" class="project_id" value="<?php echo $project_arr['data'][$i]['project_id']; ?>">
					<input type="hidden" name="details"  value="<?php echo $project_arr['data'][$i]['project_id']; ?>">
					<a class="btn btn-warning view_project_details"><i class="fa fa-pencil" aria-hidden="true"></i></a>
				 </form>
				 <form method="post" class="form-inline disp-inline">
					<input type="hidden" name="project_id" value="<?php echo $project_arr['data'][$i]['project_id']; ?>" > 
					<input type="hidden" name="remove"  value="<?php echo $project_arr['data'][$i]['project_id']; ?>">
					<a class="btn btn-danger remove_project"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
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

	$('.view_project_details').click(function(){
		$(this).closest('form').submit();
	});
	$('.remove_project').click(function(){
		if(confirm('Are you sure you want to remove this project?')){
			$(this).closest('form').submit();
		}
	});
	</script>
</body>
</html>
