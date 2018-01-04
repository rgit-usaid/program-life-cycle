<?php include('config/functions.inc.php');
##==validate user====
validate_user();
global $mysqli;
mkdir(HOST_URL.'artifacts/000047');
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
	
	$url = API_HOST_URL_PROJECT."get_all_project_activity.php?project_id=".$project_id;  
	$all_project_activities_arr = requestByCURL($url);
}


$project_stage_id = '';
$environmental_threshold = '';
$gender_threshold = '';
if(isset($project_arr)) {
	$project_stage_id = $project_arr['data']['project_stage_id'];
	$environmental_threshold = $project_arr['data']['environmental_threshold'];
	$gender_threshold = $project_arr['data']['gender_threshold'];
}


$url = API_HOST_URL_PROJECT."get_all_tags.php";  
$all_tags_arr = requestByCURL($url);
$all_tags = $all_tags_arr['data'];


/*generate_filename*/
function generate_filename($filename){
	$new_filename = '';
	$explode = explode(".",$filename);
	
	$new_filename = $explode[0].strtotime("now").'.'.$explode[1];
	
	return $new_filename;
}

function is_dir_empty($dir) {
  if (!is_readable($dir)) return NULL; 
  $handle = opendir($dir);
  while (false !== ($entry = readdir($handle))) {
    if ($entry != "." && $entry != "..") {
      return FALSE;
    }
  }
  return TRUE;
}

if(isset($_REQUEST['save_document']) && isset($_REQUEST['document_type'])){
	$doc_type = $_REQUEST['document_type'];
	$tags = explode(",",$_REQUEST['tagsinput-typeahead']);
	$file_title = $mysqli->real_escape_string($_REQUEST['document_title']);
	
	/*if document type is Project*/
	if($_REQUEST['document_type']=="Project"){ 
		$doc_type_id = $_REQUEST['project_id'];
		$proj_dir  = DOCUMENT_LOC.$doc_type_id;
		
		if(!file_exists($proj_dir)){ /*if file not exists than create that folder*/
			mkdir($proj_dir,'0777');
		
			$oldmask = umask(0);
			chmod($proj_dir, 0755);
			umask($oldmask);
		}
				
		$filename = $mysqli->real_escape_string(generate_filename($_FILES['document']['name']));
		$file_loc = $doc_type_id.'/'.$filename;
		$uploaded_file = $proj_dir.'/'.$filename; /*uploaded location*/		
	} 
	else if($_REQUEST['document_type']=="Project Activity"){ /*if document type is Project Activity*/
		$doc_type_id = $_REQUEST['project_activity'];
		$proj_dir  = DOCUMENT_LOC.$_REQUEST['project_id'];
		if(!file_exists($proj_dir)){ /*if file not exists than create that folder*/
			mkdir($proj_dir,'0777');
		
			$oldmask = umask(0);
			chmod($proj_dir, 0755);
			umask($oldmask);
		}
		
		
		$proj_act_dir  = DOCUMENT_LOC.$_REQUEST['project_id']."/".$doc_type_id;
		if(!file_exists($proj_act_dir)){ /*if file not exists than create that folder*/
			mkdir($proj_act_dir,'0777');
			
			$oldmask = umask(0);
			chmod($proj_act_dir, 0755);
			umask($oldmask);
		}
		
		$filename = $mysqli->real_escape_string(generate_filename($_FILES['document']['name']));
		$file_loc = $_REQUEST['project_id'].'/'.$doc_type_id.'/'.$filename;
		$uploaded_file = $proj_act_dir.'/'.$filename; /*uploaded location*/
	}
	
	
	if(!@move_uploaded_file($_FILES['document']['tmp_name'], $uploaded_file)){
		$_SESSION['form_msg']["msg_type"] ="error";
		$_SESSION['form_msg']["msg"] ="Some thing went wrong";
	}
	else{
		$ins ="INSERT INTO usaid_documents(link_id,link_type,filename,filepath) VALUES ('".$doc_type_id."','".$doc_type."','".$file_title."','".$file_loc."')";
		$mysqli->query($ins);
		if(!ins){
			$_SESSION['form_msg']["msg_type"] ="error";
			$_SESSION['form_msg']["msg"] ="Some thing went wrong";
		}
		$last_insert_id = $mysqli->insert_id; 
		
		/*====loop in tags===*/
		for($i=0;$i<count($tags);$i++){
			$ins ="INSERT INTO usaid_documents_tags(document_id,tags) VALUES ('".$last_insert_id."','".trim($mysqli->real_escape_string($tags[$i]))."')";
			$mysqli->query($ins);
			if(!ins){
				$_SESSION['form_msg']["msg_type"] ="error";
				$_SESSION['form_msg']["msg"] ="Some thing went wrong";
			}
		}	
	}
	
	if($_SESSION['form_msg']["msg_type"]==""){
		$_SESSION['form_msg']["msg_type"] ="success";
		$_SESSION['form_msg']["msg"] ="File uploaded successfully";
	}
	header("location:manage_key_documents");	
}


if(isset($_REQUEST['delete_document']) && isset($_REQUEST['document_id']) && $_REQUEST['document_id']!=""){
	$uploded_file = DOCUMENT_LOC.$_REQUEST['document_loc'];
	unlink($uploded_file);
	
	$del_tags ="DELETE from usaid_documents_tags WHERE document_id=".$_REQUEST['document_id'];
	$mysqli->query($del_tags);
	
	$del_docs ="DELETE from usaid_documents WHERE id= ".$_REQUEST['document_id'];
	$mysqli->query($del_docs);
	
	if(!$del_docs){
		$_SESSION['form_msg']["msg_type"] ="error";
		$_SESSION['form_msg']["msg"] ="Some thing went wrong";
	}
	
	if($_SESSION['form_msg']["msg_type"]==""){
		$_SESSION['form_msg']["msg_type"] ="success";
		$_SESSION['form_msg']["msg"] ="File delete successfully";
	}
	header("location:manage_key_documents");		
}
?> 
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" type="image/ico" href="img/favicon.ico"/>
	<title>USAID-AMP</title>
	<?php include('includes/resources.php');?>
	<script type="text/javascript" src="<?php echo HOST_URL;?>js/plugin/tags/bootstrap-tagsinput.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo HOST_URL;?>css/plugin/tags/bootstrap-tagsinput.css">
	<script type="text/javascript" src="<?php echo HOST_URL;?>js/plugin/tags/bootstrap3-typeahead.js"></script>
	<style type="text/css">
	  .demo-droppable {
		background: #5bc1de;
		color: #fff;
		padding: 25px 0;
		text-align: center;
		border:dashed 1px #ddd;
	  }
	  .demo-droppable.dragover {
		background: #00CC71;
	  }
	</style>
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<div>
			<?php include('includes/project_header.php');?>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head">Add New Document</div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<!--form block start-->
			<div class="project-detail-blk table-container">
				<div class="container document_form_blk">  
				 <div id="submission_msg" class="form-msg error <?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg_type'];}?>" style="margin-bottom:20px">
					<?php if(isset($_SESSION['form_msg'])) { echo $_SESSION['form_msg']['msg'];}?>
				 </div>
				  <div id="contact" action="" method="post" >
					<h3>Key Documents</h3>
						<form method="post" enctype="multipart/form-data">
						<fieldset class="project_act_blk disp-none1">
							<h4>Select Activity <span class="italic">(Optional)</span></h4>
							<select class="form-control" name="project_activity" id="project_activity_dpw">
								<option value="">Project Level Document</option>
								<?php foreach($all_project_activities_arr['data'] as $key => $activity_obj){?>
								<option value="<?php echo $activity_obj['activity_id'];?>"><?php echo $activity_obj['title'];?></option>
								<?php }?>
							</select>
						</fieldset>
						<!--project document block-->
						<div class="project_doc form_blk">
							<input type="hidden" value="<?php echo $project_id;?>" name="project_id"/>
							<input type="hidden" value="Project" name="document_type" class="document_type"/>
							<fieldset>
								<h4>Document Title</h4>
								<div class="clear">
									<input type="text" class="document_title form-control" name="document_title">
								</div>
							</fieldset>
							<fieldset>
								<h4>Document Tags</h4>
								<div class="clear">
									<input type="text" class="tagsinput-typeahead" name="tagsinput-typeahead">
								</div>
							</fieldset>
							<fieldset>
								<h4>Document</h4>
								<input type="file" name="document">
							</fieldset>
							<div style="height:20px"></div>
							<fieldset>
								  <input name="save_document" type="hidden" value="save_document">
								  <input name="reset" type="reset" class="btn btn-blue" value="Cancel" id="reset_btn">
								  <input name="button" type="submit" class="btn btn-blue" id="submit_form" value="Submit">
								  <div class="clear"></div>
							</fieldset>
						</div>
						</form>
					<!--program element block-->
				  </div>
				</div>
			</div>
			<div class="extra_ht"></div><div class="extra_ht"></div>
			<!--add new project end-->
			<div class="table-container">
				<table id="projects_table" class="display table table-bordered table-striped" cellspacing="0" width="100%">
					<thead>
					<tr>
						<th>Title</th>
						<th>Related To</th>
						<th>Tags</th>
						<th class="text-center comm-width">Download</th>
						<th class="text-center comm-width">Action</th>
					</tr>
					</thead>
					<?php 
						$url = API_HOST_URL_PROJECT."get_all_project_artifacts.php?project_id=".$project_id;  
						$project_art_arr = requestByCURL($url);		
						
						if(count($project_art_arr['data'])>0){
						$temp_doc = array();				
						for($i=0;$i<count($project_art_arr['data']);$i++){
							if(!array_key_exists($project_art_arr['data'][$i]['document_id'],$temp_doc)){
								$temp_doc[$project_art_arr['data'][$i]['document_id']] = array();
								$temp_doc[$project_art_arr['data'][$i]['document_id']]['tags'] = $project_art_arr['data'][$i]['tags'];
							}
							else{
								$temp_doc[$project_art_arr['data'][$i]['document_id']]['tags'] = $temp_doc[$project_art_arr['data'][$i]['document_id']]['tags'].', '.$project_art_arr['data'][$i]['tags'].',';
							}
							
							$temp_doc[$project_art_arr['data'][$i]['document_id']]['id'] = $project_art_arr['data'][$i]['document_id'];
							$temp_doc[$project_art_arr['data'][$i]['document_id']]['project'] = $project_art_arr['data'][$i]['project'];
							$temp_doc[$project_art_arr['data'][$i]['document_id']]['activity'] = $project_art_arr['data'][$i]['activity'];
							$temp_doc[$project_art_arr['data'][$i]['document_id']]['link_type'] = $project_art_arr['data'][$i]['link_type'];
							$temp_doc[$project_art_arr['data'][$i]['document_id']]['filepath'] = $project_art_arr['data'][$i]['filepath'];
							$temp_doc[$project_art_arr['data'][$i]['document_id']]['filename'] = $project_art_arr['data'][$i]['filename'];
							$temp_doc[$project_art_arr['data'][$i]['document_id']]['link_id'] = $project_art_arr['data'][$i]['link_id'];
						}
											
						foreach($temp_doc as $key => $obj){ 
					?>
					<tbody>
						<tr>
							<td><?php echo $obj['filename'];?></td>
							<td><?php echo $obj['link_type'];
							if($obj['link_type']=="Project")
							{
								echo " (".$obj['project'].")";
							}
							else{
								echo " (".$obj['activity'].")";
							}
							?></td>
							<td><?php echo $obj['tags'];?></td>
							<td class="text-center"><a href="<?php echo 'artifacts/'.$obj['filepath'];?>" download><img src="img/download-icon.png"/></a></td>
							<td class="text-center"> 
								 <form method="post" class="form-inline disp-inline">
									<input type="hidden" name="document_loc" value="<?php echo $obj['filepath'];?>"/> 
									<input type="hidden" name="document_id" value="<?php echo $obj['id'];?>"/> 
									<input type="hidden" name="document_type" value="<?php echo $obj['link_type'];?>"/>
									<input type="hidden" name="link_id" value="<?php echo $obj['link_id'];?>"/> 
									<input type="hidden" name="delete_document" value="delete_document"/> 
									<input type="submit" name="remove" value="Remove" class="project_btn" onClick="return window.confirm('Are you sure you want to remove this document?');">
								 </form> 
							</td>
						</tr>
					</tbody>
					<?php }?>
					<?php } else{ ?>
					<tbody>
						<tr>
							<td colspan="5" class="text-danger bold">No data found</td>
						</tr>
					</tbody>
					<?php }?>
				</table>
			</div>
     	  </div>
		</div>
		<!--add new project start-->
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script>
setTimeout(function(){
	$('#submission_msg').addClass("disp-none");
	$('#submission_msg').html("");
},10000);

/*===fill tags==*/
var tags = new Array();	
<?php foreach($all_tags as $key => $tag){?>
var obj = new Object();
obj['name'] = '<?php echo $tag['name'];?>';
tags.push(obj);
<?php }?> 
$('.tagsinput-typeahead').tagsinput({
  typeahead: {
    source: tags.map(function(item) { return item.name }),
    afterSelect: function() {
    	this.$element[0].value = '';
			
    }
  }
}); 

/*submit form on click save btn*/
$('#submit_form').click(function(){
	$(this).closest('form').submit();
});	

/*set document type on change of dropdown*/
$('#project_activity_dpw').change(function(){
	if($(this).val()==""){
		$('.document_type').val('Project');
	}
	else{
		$('.document_type').val('Project Activity');
	}
});

/*reset button*/
$('#reset_btn').click(function(){
	$('.bootstrap-tagsinput').find('span').remove("");
	$('.tagsinput-typeahead,.document_type').val("");
});
</script>
<?php unset($_SESSION['form_msg']);?>
</body>
</html>
