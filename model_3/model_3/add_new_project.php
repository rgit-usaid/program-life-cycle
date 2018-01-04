<?php include('config/functions.inc.php');
##==validate user====
validate_user();
###request for get single project details using project id ===========
$project_id = '';
if(isset($_REQUEST['details']))
{
	$project_id = trim($_REQUEST['project_id']);
}

if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];	
}

if($project_id!="")
{
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);

    $url = API_HOST_URL_PROJECT."api_demo.php?stage";  
  	$project_stage_arr = requestByCURL($url);
	$project_owner_id = $project_arr['data']['employee_id']; 
	
	$empinfo_url = API_HOST_URL_PROJECT."get_hr_employee.php?employee_id=".$project_owner_id;  
	$empinfo_arr = requestByCURL($empinfo_url);
	$project_stage_id = $project_arr['data']['project_stage_id'];

	$project_act_url = API_HOST_URL_PROJECT."get_all_project_activity.php?project_id=".$project_id;  
	$project_activity_arr = requestByCURL($project_act_url);

}

##==flag of stage document form 
$flag_stage_doc = 0;
if($project_stage_id>2){ ##==if project stage is fall in group '2' than show appraisal document
	$flag_stage_doc = 1;
}

##==if project design plan in view only mode==
if(isset($_REQUEST['view_only_mode'])){
	$flag_stage_doc = $_REQUEST['view_only_mode'];
}

?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico"/>
<link rel="stylesheet" href="<?php echo HOST_URL;?>css/vis.css">
<script src="<?php echo HOST_URL;?>/js/vis.js"></script>
<title><?php echo TITLE;?></title>
<?php include('includes/resources.php');?>
</head>
<?php if($flag_stage_doc==1){?> 
	<?php include('manage_project_approval_document.php');?>
<?php } else {?>
	<?php include('manage_project_design_plan.php');?>
<?php }?>
<script>
$('.project_purpose,.project_description').focus(function(){
	var val=$(this).val();
	if($(this).hasClass("project_purpose")){
		$('#myModal').find(".modal-title").text("Project Purpose");
		
	}
	else if($(this).hasClass("project_description")){
		$('#myModal').find(".modal-title").text("Project Description");
		
	}
	$('#myModal').find(".msg").text(val);
	$('#myModal').modal("show");
});
	
$('.model_close').click(function(){
	var val=$('#myModal').find(".msg").val();
	if($("#myModal").find(".modal-title").text()=="Project Purpose"){
		$(".project_purpose").val(val);
	}
	else if($("#myModal").find(".modal-title").text()=="Project Description"){
		$(".project_description").val(val);
	}
	
	
});


</script>
</html>
