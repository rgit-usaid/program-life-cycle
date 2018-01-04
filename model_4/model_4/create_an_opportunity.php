<?php include('config/functions.inc.php');
$employee_id = $_SESSION['user'];
##function for generate project id ==================
function updateProjectId($id)
{
	global $mysqli;
	$num_str = sprintf("%06d", $id);
	$update_val = "update usaid_project set project_id = '".$num_str."' where id = '".$id."'";
	$result_update = $mysqli->query($update_val);
	if($result_update)return true; else return false;
}
## Add and update create_an_opportunity ================
$error = '';
if(isset($_REQUEST['create_an_opportunity']))
{
	$project_title = $mysqli->real_escape_string(trim($_REQUEST['title'])); 
	$project_purpose_statement = $mysqli->real_escape_string(trim($_REQUEST['project_purpose_statement']));
	$create_an_opportunity = $_REQUEST['create_an_opportunity'];
	
	$originating_operating_unit_id = "originating_operating_unit_id=NULL";
	if($_REQUEST['originating_operating_unit_id']!="")
	{
		$originating_operating_unit_id = "originating_operating_unit_id='".trim($_REQUEST['originating_operating_unit_id'])."'";
	}
	$implementing_operating_unit_id = "implementing_operating_unit_id=NULL";
	if($_REQUEST['implementing_operating_unit_id']!="")
	{
		$implementing_operating_unit_id = "implementing_operating_unit_id='".trim($_REQUEST['implementing_operating_unit_id'])."'";
	}
	
	if($project_title=='')
	{
		$error = 'Title should not be blank';
	} 
		if($error=='')
		{
			 $insert_project = "insert into usaid_project set project_id = '".$project_id."', title='".$project_title."',project_purpose='".$project_purpose_statement."',".$originating_operating_unit_id.",".$implementing_operating_unit_id.", employee_id='".$employee_id."'";
		 		$result_project_data = $mysqli->query($insert_project);
				if($result_project_data)
				{
					$id = $mysqli->insert_id;
					updateProjectId($id);// call function for update project_id 
					if($create_an_opportunity=='exit')
					{
						//$_SESSION['custom_indicator_id']=$project_id_new;
							header("location:index.php");
					}
					else
					{
						$_SESSION['create_project_id']=$project_id_new;
						header("location:create_ir_subir.php");
					}
				}	
		}
	
}
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
	.ajax_data {
		position: absolute;
		box-shadow: 2px 2px 2px #999;
		width: 78%;
		z-index: 99999;
		background: #fff;
		max-height: 200px;
		overflow-y: auto;
		}
	.ajax_data .elem:hover {
    background: #f8f8f8;
	}
	form [type="submit"] {
		padding-left: 1em;
   		padding-right: 1em;
  		display: inline-block;
	}
	</style>
</head>
<body>

	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>

	<div class="container-fluid">
		<ol class="breadcrumb">
			<li><a href=".">Home</a></li>
			<li class="active">Create an Opportunity</li>
		</ol>
		<!-- Left Menu -->
		<div class="col-md-3">
			<?php include 'include/cdcs_leftpanel.php'; ?>	
		</div>
		<div class="col-md-6">
			<div class="wrap">
				<div class="container-fluid">
					<form class="form-horizontal" action="" method="post">
						<h3>Project Details</h3>
						<p>Activites scheduled for concurrent design are located on the Activtiy Tab</p>					
						<div class="form-group">
							<label for="input-type-textarea">Title of proposed project</label>
							<textarea id="input-type-textarea" name="title" class="autoh_textarea"></textarea>
						</div>
						<div class="form-group">
							<label for="input-type-text">Implementing Operating Unit : </label>
							<!--<input id="input-type-text" name="implementing_ou" type="text">-->
							<div class="search_div" style="position:relative">
									<input type="text" name="implementing_operating_unit" class="search_txt form-control" autocomplete="off" onKeyUp="search_val(this,'<?php echo API_HOST_URL_PHOENIX;?>get_all_operating_unit.php')" value="<?php echo $implementing_unitinfo_desc;?>" />
									<input type="hidden" name="implementing_operating_unit_id" class="textarea_id" value="<?php echo $implementing_operating_unit_id;?>"/>
									<div class="ajax_data">
										
									</div>
							</div>
						</div>
						<div class="form-group">
							<label for="input-type-text">Originating Operating Unit :</label>
							<!--<input id="input-type-text" name="originationg_ou" type="text">-->
							<div class="search_div" style="position:relative">
									<input type="text" name="originating_operating_unit" class="search_txt form-control" autocomplete="off" onKeyUp="search_val(this,'<?php echo API_HOST_URL_PHOENIX;?>get_all_operating_unit.php')" value="<?php echo $operating_unitinfo_desc;?>" />
									<input type="hidden" name="originating_operating_unit_id" class="textarea_id" value="<?php echo $originating_operating_unit_id;?>" />
									<div class="ajax_data">
										
									</div>
								</div>
						</div>
						<div class="form-group">
							<label for="input-type-textarea">Project Purpose Statement</label>
							<textarea id="input-type-textarea" name="project_purpose_statement" class="autoh_textarea"></textarea>
						</div>
						<hr>
						<div class="form-group">
							<button class="usa-button-outline" type="reset">Cancel</button>
						<!--	<a href="create_ir_subir.php" class="btn btn-primary save">Save & Proceed</a> -->
							<button class="btn btn-primary save" type="submit" name="create_an_opportunity" value="save">Save & Proceed</button>
							<button class="usa-button-hover" type="submit" name="create_an_opportunity" value="exit">Save & Exit</button>
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
					<p>Creating a simple project design is the process by which USAID defines how it will operationalize a result or set of results in a CDCS or other strategic framework to ensure that efforts are complementary and aligned in support of the strategy. Whereas the strategic planning process defines the strategic approach, the project design process guides its execution. <a href="#" class="readmore">Read more.</a></p>
					<p class="read disp-none">The project design process recognizes that development seeks to influence complex systems and requires integrated tactics to achieve higher level results and sustainability of outcomes. For these reasons, project designs typically incorporate multiple activities such as contracts and cooperative agreements with international organizations, awards to local organizations, and direct agreements with partner governments, as well as non-agreement-based activities such as policy dialogue undertaken directly by USAID staff. Missions should think creatively about how they can most strategically use the broad range of USAID tools to strengthen local systems and engage local actors as the drivers behind long-term, sustainable change.</p>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			$(".readmore").click(function(){
			$(".read").removeClass('disp-none');
			$(".readmore").hide();
			})
		});
		
/*lookahead search element event*/
$(document).on('click','.ajax_data .elem',function(){
	ajax_elem_click($(this));
});
$(document).on('focus','.ajax_data .elem',function(e){
	$(this).addClass('select');
});
$(document).on('blur','.ajax_data .elem',function(){
	$(this).removeClass('select');
});
$(document).on('keydown','.ajax_data .elem',function(e){
	var keycode = e.which || e.keycode; 
	if(keycode==13){
		ajax_elem_click($(this));
	}
});

		
/*lookahead search*/
function search_val(elem, url){
 var name = $(elem).val();
	$.ajax({
		url:url,
		data:{name:name},
		datatype:"jsonp",
		context : elem,
		success:function(data){
			$('.ajax_data').html("");
			var project_arr = data['data'];
			var html ='';
			$.each(project_arr,function(index, project){					
				var actual_val = $(elem).val();
				var re_oth = new RegExp(actual_val,"gi");
				var ptitle = project.operating_unit_description;
				if(ptitle.search(re_oth)!=-1){
					var re = new RegExp(actual_val,"gi");
					var act_project_title= ptitle;
					var operating_unit_description= ptitle.replace(re,function(str) {return '<b>'+str+'</b>'})+' ('+project.operating_unit_id+')';
					html = html+'<div class="elem"  tabindex="0">'+operating_unit_description+'<input type="hidden" value="'+act_project_title+'" name="operating_unit_description" class="operating_unit_description"/><input type="hidden" value="'+project.operating_unit_id+'" name="operating_unit_id" class="operating_unit_id"/><input type="hidden" value="'+project.parent_operating_unit_id+'" name="parent_operating_unit_id" class="parent_operating_unit_id"/></div>';
				}
			});
			$(elem).closest('.search_div').find('.ajax_data').html(html);
		}
	});	
}

function ajax_elem_click(elem){
	var operating_unit_id = $(elem).find('.operating_unit_id').val();
	var operating_unit = $(elem).find('.operating_unit_description').val();
	$(elem).closest('.search_div').find('.textarea_id').val(operating_unit_id);
	$(elem).closest('.search_div').find('.search_txt').val(operating_unit);
	$('.ajax_data').html("")
}
	</script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>