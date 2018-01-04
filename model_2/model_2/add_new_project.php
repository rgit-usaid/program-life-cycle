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
	$project_id = $project_id;
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);

    $url = API_HOST_URL_PROJECT."api_demo.php?stage";  
  	$project_stage_arr = requestByCURL($url);
	$project_owner_id = $project_arr['data']['employee_id']; 
	
	$empinfo_url = API_HOST_URL_PROJECT."get_hr_employee.php?employee_id=".$project_owner_id;  
	$empinfo_arr = requestByCURL($empinfo_url);
	
	
}

$url = API_HOST_URL_PHOENIX."get_all_operating_unit.php";  
$operating_unit_arr = requestByCURL($url);

$project_stage_id = '';
$environmental_threshold = '';
$gender_threshold = '';
if(isset($_REQUEST['project_stage_id'])) $project_stage_id = $_REQUEST['project_stage_id'];
if(isset($project_arr)) {
	$project_stage_id = $project_arr['data']['project_stage_id'];
	$environmental_threshold = $project_arr['data']['environmental_threshold'];
	$gender_threshold = $project_arr['data']['gender_threshold'];
}

?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico"/>
<link rel="stylesheet" href="<?php echo HOST_URL;?>css/vis.css">
<script src="<?php echo HOST_URL;?>/js/vis.js"></script>
<title>USAID-AMP</title>
<style>
/* custom styles for individual items, load this after vis.css */

.vis-item.green {
  background-color: greenyellow;
  border-color: green;
}

/* create a custom sized dot at the bottom of the red item */
.vis-item.red {
  background-color: red;
  border-color: darkred;
  color: white;
  font-family: monospace;
  box-shadow: 0 0 10px gray;
}
.vis-item.vis-dot.red {
  border-radius: 10px;
  border-width: 10px;
}
.vis-item.vis-line.red {
  border-width: 5px;
}
.vis-item.vis-box.red {
  border-radius: 0;
  border-width: 2px;
  font-size: 24pt;
  font-weight: bold;
}

.vis-item.orange {
  background-color: gold;
  border-color: orange;
}
.vis-item.vis-selected.orange {
  /* custom colors for selected orange items */
  background-color: orange;
  border-color: orangered;
}

.vis-item.magenta {
  background-color: magenta;
  border-color: purple;
  color: white;
}

.vis-item.light-red {
  background-color: #ff7f7f;
  border-color: #b20000;
  color: #b20000;
}

.vis-item.blue {
  background-color: #b2b2ff;
  border-color: #000;
  font-weight:bold;
}

/* our custom classes overrule the styles for selected events,
   so lets define a new style for the selected events */
.vis-item.vis-selected {
  background-color: white;
  border-color: black;
  color: black;
  box-shadow: 0 0 10px gray;
}
.show_btn{
margin-top:12px;
}
#visualization{box-shadow:0px 0px 4px #0080ff;}
</style>
<?php include('includes/resources.php');?>
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
				<div class="tbl-content-head"><?php if($project_id!=""){  echo "You are editing project details";} else echo "Add New Project Details";?></div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="project-detail-blk table-container">
				<div class="form-msg"></div>
				<form id="project_detail_form" action="home" method="post" autocomplete="off">
				<div class="form-blk">
					<header class="row">
					    <div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
							  <h2 class="form-blk-head">Project Details</h2>
						</div>
						<div  class="col-lg-5 col-md-4 col-sm-5 col-xs-12 text-right">
						<a href="view_project_archive">Details change Log</a>
						</div>   
					</header>
					<div class="row project-detail-textarea">
						<div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
							<label class="lg">Project Title</label>
							<textarea class="form-textarea form-control sm project_title autoh_textarea" name="title" rows="1"><?php echo $title;?></textarea>
						</div>
						<div  class="col-lg-5 col-md-4 col-sm-5 col-xs-12">
							<label class="lg">Operating Unit : <span class="normal"></span></label><br/>
							<select class="form-control maxw_350" name="project_operating_unit">
								<option>Select Operating Unit</option>
								<?php foreach($operating_unit_arr['data'] as $key => $operating_unit){?>
									<option value="<?php echo $operating_unit['operating_unit_id']?>" <?php if(isset($project_arr) && $project_arr['data']['operating_unit_id']==$operating_unit['operating_unit_id']){?> selected="selected" <?php }?>><?php echo $operating_unit['operating_unit_description']?> (<?php echo $operating_unit['operating_unit_id'];?>)</option>
								<?php }?>
							</select>
						</div>
					</div>
					<?php
						$project_purpose = '';
						if(isset($_REQUEST['project_purpose'])) $project_purpose = $_REQUEST['project_purpose'];
						if(isset($project_arr)) $project_purpose = $project_arr['data']['project_purpose'];
						
						$smart_sheet_hyperlink = '';
						if(isset($project_arr)) $smart_sheet_hyperlink = $project_arr['data']['smart_sheet_hyperlink'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
							<label class="lg">Project Description</label>
							<textarea class="form-textarea form-control sm autoh_textarea" name="project_purpose" rows="2"><?php echo $project_purpose;?></textarea>
						</div>
						<?php
							$project_stage_id = '';
							if(isset($_REQUEST['project_stage_id'])) $project_stage_id = $_REQUEST['project_stage_id'];
							if(isset($project_arr)) $project_stage_id = $project_arr['data']['project_stage_id'];
							if($project_id!='')
							{
						?>
							<div  class="col-lg-5 col-md-4 col-sm-5 col-xs-12">
								<label class="lg">Project Stage</label><div style="height:5px;"></div>
								<select class="form-control" name="project_stage_id" id="dpw_project_stage_id">
									<?php
		
									for($stage_count=0; $stage_count<count($project_stage_arr['data']); $stage_count++)
									{
									?>
									<option value="<?php echo $project_stage_arr['data'][$stage_count]['stage_id'];?>" <?php if($project_stage_arr['data'][$stage_count]['stage_id']==$project_arr['data']['project_stage_id'])echo 'selected="selected"'; ?>><?php echo $project_stage_arr['data'][$stage_count]['stage_name'];?></option>
									<?php
									}
									?>
								</select>
							</div>
						<?php } ?>
					</div>
					
					<div class="row project-detail-textarea">
						<div  class="col-lg-7 col-md-8 col-sm-7 col-xs-12">
							<label class="lg">Detailed Project Plan </label><br/>
							<input type="hidden" value="<?php echo $smart_sheet_hyperlink;?>" class="actual_smartsheet_link"/>
						   	<input type="text" class="form-control maxw_350  disp-none " id="smart_sheet_ip" value="<?php echo $smart_sheet_hyperlink;?>" name="smart_sheet_hyperlink"/>
							
							<div class="extra_ht"></div>
							
							<a id="smart_sheet_link"  target="_blank" class="bold <?php if($smart_sheet_hyperlink==""){ ?>disp-none <?php }?> " href="<?php echo $smart_sheet_hyperlink;?>">Smartsheet Project Link</a>  
							
							<a class="btn btn-blue disp-none" id="cancel_smartsheet_link">Cancel</a> 
							<a class="btn btn-green" id="edit_smartsheet_link"><?php if($smart_sheet_hyperlink==""){ ?> Add Smartsheet Link <?php } else {?> Edit Link <?php }?></a> 
							
							
							<a id="show_smartsheet_iframe_btn" class="btn btn-warning <?php if($smart_sheet_hyperlink==""){ ?>disp-none<?php }?> "><i class="fa fa-toggle-on" aria-hidden="true"></i> View Snapshot</a>
							<a id="hide_smartsheet_iframe_btn" class="btn btn-green disp-none"><i class="fa fa-toggle-off" aria-hidden="true"></i> Hide Snapshot</a>	
							<div class="extra_ht"></div>
							<?php if($project_id!=''){?>
								<IFRAME id="smart_sheet_iframe" WIDTH=1000 HEIGHT=700 FRAMEBORDER=0  class="disp-none" SRC="<?php echo $smart_sheet_hyperlink;?>"></IFRAME> 
							<?php }?>
						</div>
					</div>
					<!--<div class="ip-outer">
						<input id="profile-image-upload" class="hidden" type="file">
						<div id="profile-image">click here to change profile image</div>
					</div>-->
				</div>
				<?php 
				##==show timeline in edit mode===
				if(isset($project_id) && $project_id!=""){?>
				<div class="form-blk">
					<header><h3 class="form-blk-head">Project Timeline</h3></header>
					<div id="visualization"></div>	
				</div>
				<?php }?>
				<div class="form-blk">
					<header><h3 class="form-blk-head">Operational Details</h3></header>
					<h4 class="form-blk-head">Important dates</h4>
					<div style="height:10px"></div>
					<div>
						<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
							<div>
								<div class="sm-head">Design Record Create Date</div>
								<table class="project_dates">
									<tr class="head">
										<td>Month</td>
										<td>Day</td>
										<td>Year</td>
									</tr>
									<?php
										$explode_date = array();
										$design_record_create_date = '';
										if(isset($_REQUEST['design_record_create_date'])) $design_record_create_date = $_REQUEST['design_record_create_date'];
										if(isset($project_arr)) $design_record_create_date = $project_arr['data']['design_record_create_date'];
										
										$explode_date = explode("/",$design_record_create_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 
									<tr class="cr_date">
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>"/></td>
									</tr>
								</table>
								<input type="hidden" name="design_record_create_date" class="formatted_date"  value="<?php echo $design_record_create_date;?>"/>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
							<div class="calendar-blk">
								<div class="sm-head">Planned Start Date</div>
								<table class="project_dates">
									<tr class="head">
										<td>Month</td>
										<td>Day</td>
										<td>Year</td>
									</tr>
									<?php
										$explode_date = array();
										$planned_start_date = '';
										if(isset($_REQUEST['planned_start_date'])) $planned_start_date = $_REQUEST['planned_start_date'];
										if(isset($project_arr)) $planned_start_date = $project_arr['data']['planned_start_date'];
										
										$explode_date = explode("/",$planned_start_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 	
									<tr class="pls_date">
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>"/></td>
									</tr>
								</table>
								<input type="hidden" name="planned_start_date" class="formatted_date" value="<?php echo $planned_start_date;?>"/>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
							<div class="calendar-blk">
								<div class="sm-head">Actual Start Date</div>
								<table class="project_dates">
									<tr class="head">
										<td>Month</td>
										<td>Day</td>
										<td>Year</td>
									</tr>
									<?php
										$explode_date = array();
										$actual_start_date = '';
										if(isset($_REQUEST['actual_start_date'])) $actual_start_date = $_REQUEST['actual_start_date'];
										if(isset($project_arr)) $actual_start_date = $project_arr['data']['actual_start_date'];
										
										$explode_date = explode("/",$actual_start_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 
									<tr class="acs_date">
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>"/></td>
									</tr>
								</table>
								<input type="hidden" name="actual_start_date" class="formatted_date" value="<?php echo $actual_start_date;?>"/>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
							<div class="calendar-blk">
								<div class="sm-head">Next Review Date</div>
								<table class="project_dates">
									<tr class="head">
										<td>Month</td>
										<td>Day</td>
										<td>Year</td>
									</tr>
									<?php
										$explode_date = array();
										$next_review_date = '';
										if(isset($_REQUEST['next_review_date'])) $next_review_date = $_REQUEST['next_review_date'];
										if(isset($project_arr)) $next_review_date = $project_arr['data']['next_review_date'];
										
										$explode_date = explode("/",$next_review_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 
									<tr class="nex_date"> 
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>"/></td>
									</tr>
								</table>
								<input type="hidden" name="next_review_date" class="formatted_date" value="<?php echo $next_review_date;?>"/>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
							<div class="calendar-blk">
								<div class="sm-head">Planned End Date</div>
								<table class="project_dates">
									<tr class="head">
										<td>Month</td>
										<td>Day</td>
										<td>Year</td>
									</tr>
									<?php
										$explode_date = array();
										$planned_end_date = '';
										if(isset($_REQUEST['planned_end_date'])) $planned_end_date = $_REQUEST['planned_end_date'];
										if(isset($project_arr)) $planned_end_date = $project_arr['data']['planned_end_date'];
										
										$explode_date = explode("/",$planned_end_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 	
									<tr class="ple_date">
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>"/></td>
									</tr>
								</table>
								<input type="hidden" name="planned_end_date" class="formatted_date" value="<?php echo $planned_end_date;?>"/>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-blk">
					<header><h3 class="form-blk-head">Objective Section</h3></header>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 threshold">
							<h4 class="form-blk-head">Gender equality</h4>
								<label <?php if($gender_threshold=="Admin-Exempt"){?>class="select"<?php }?>>
									<input type="radio" name="gender_threshold" value="Admin-Exempt" <?php if($gender_threshold=="Admin-Exempt"){?>checked<?php }?>/> Admin - Exempt
								</label>
							<br/>
								<label <?php if($gender_threshold=="Not Targeted"){?>class="select"<?php }?>>
									<input type="radio" name="gender_threshold" value="Not Targeted" <?php if($gender_threshold=="Not Targeted"){?>checked<?php }?>/> Not Targeted
								</label>
							<br/>
								<label <?php if($gender_threshold=="Multi Org"){?>class="select"<?php }?>>
									<input type="radio" name="gender_threshold" value="Multi Org"  <?php if($gender_threshold=="Multi Org"){?>checked<?php }?>/> Multi Org
								</label>
							<br/>
								<label <?php if($gender_threshold=="Principal"){?>class="select"<?php }?>>
									<input type="radio" name="gender_threshold" value="Principal" <?php if($gender_threshold=="Principal"){?>checked<?php }?>/> Principal
								</label>
							<br/>
								<label <?php if($gender_threshold=="Significant"){?>class="select"<?php }?>>
									<input type="radio" name="gender_threshold" value="Significant"  <?php if($gender_threshold=="Significant"){?>checked<?php }?>/> Significant
								</label>
							<br/>
								<label <?php if($gender_threshold=="Not Selected"){?>class="select"<?php }?>>
									<input type="radio" name="gender_threshold" value="Not Selected" <?php if($gender_threshold=="Not Selected"){?>checked<?php }?>/> Not Selected
								</label>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 threshold">
							<h4 class="form-blk-head">Environment</h4>
								<label <?php if($environmental_threshold=="Admin-Exempt"){?>class="select"<?php }?>>
									<input type="radio" name="environmental_threshold" value="Admin-Exempt" <?php if($environmental_threshold=="Admin-Exempt"){?>checked<?php }?>/> Admin - Exempt
								</label>
							<br/>
								<label <?php if($environmental_threshold=="Not Targeted"){?>class="select"<?php }?>>
									<input type="radio" name="environmental_threshold" value="Not Targeted" <?php if($environmental_threshold=="Not Targeted"){?>checked<?php }?>/> Not Targeted
								</label>
							<br/>
								<label <?php if($environmental_threshold=="Multi Org"){?>class="select"<?php }?>>
									<input type="radio" name="environmental_threshold" value="Multi Org" <?php if($environmental_threshold=="Multi Org"){?>checked<?php }?>/> Multi Org
								</label>
							<br/>
								<label <?php if($environmental_threshold=="Principal"){?>class="select"<?php }?>>
									<input type="radio" name="environmental_threshold" value="Principal" <?php if($environmental_threshold=="Principal"){?>checked<?php }?>/> Principal
								</label>
							<br/>
								<label <?php if($environmental_threshold=="Significant"){?>class="select"<?php }?>>
									<input type="radio" name="environmental_threshold" value="Significant" <?php if($environmental_threshold=="Significant"){?>checked<?php }?>/> Significant
								</label>
							<br/>
								<label <?php if($environmental_threshold=="Not Selected"){?>class="select"<?php }?>>
									<input type="radio" name="environmental_threshold" value="Not Selected" <?php if($environmental_threshold=="Not Selected"){?>checked<?php }?>/> Not Selected
								</label>
							</div>
						</div>
					</div>
				<div>
				<input type="hidden" name="project_id" value="<?php echo $project_id;?>"/>
				<input type="button" value="Save" class="btn btn-blue" id="save_project"/><br/><br/>
				<a href="home" style="text-decoration:underline;">Back to list</a>
			</div>
			</form>
			</div>
			<!--add new project end-->
     	  </div>
		</div>
		<!--add new project start-->
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script src="<?php echo HOST_URL?>js/add_new_project.js"></script>
<script>
$('#save_project').click(function(){
	var form_serialize = $('#project_detail_form').serialize();
	form_serialize= form_serialize+'&add_project=add_project';
	
	if($('#project_detail_form').find('.invalid_ip').length==0){
		$.ajax({
			url:'<?php echo HOST_URL?>ajaxfiles/manage_project.php',
			type:'POST',
			data:form_serialize,
			success:function(data){
				data = JSON.parse(data);
				var create_date = $('.cr_date').find('.year').val()+'-'+$('.cr_date').find('.month').val()+'-'+$('.cr_date').find('.date').val();
				var planned_start_date = $('.pls_date').find('.year').val()+'-'+$('.pls_date').find('.month').val()+'-'+$('.pls_date').find('.date').val();
				var planned_end_date = $('.ple_date').find('.year').val()+'-'+$('.ple_date').find('.month').val()+'-'+$('.ple_date').find('.date').val();
				var actual_start_date = $('.acs_date').find('.year').val()+'-'+$('.acs_date').find('.month').val()+'-'+$('.acs_date').find('.date').val();
				var next_review_date = $('.nex_date').find('.year').val()+'-'+$('.nex_date').find('.month').val()+'-'+$('.nex_date').find('.date').val();
				
				
				// DOM element where the Timeline will be attached
				var container = document.getElementById('visualization');
				
				// Create a DataSet (allows two way data-binding)
				var items = new vis.DataSet([
				{id: 1, content: 'Created', start: create_date},
				{id: 2, content: 'Actual Start Date', start: actual_start_date,'className': 'green'},
				{id: 3, content: 'Planned Start Date', start: planned_start_date},
				{id: 4, content: 'Planned End Date', start: planned_end_date,'className': 'light-red'},
				{id: 5, content: 'Review', start: next_review_date,'className': 'blue'}
				]);
				
				$(container).html("");
				// Configuration for the Timeline
				var options = {};
				
				// Create a Timeline
				var timeline = new vis.Timeline(container, items, options);
				
				//$('#project_detail_form').find('.formatted_date').val("");
				if($('#smart_sheet_iframe').length>0){
					$('#smart_sheet_iframe').addClass('disp-none');
				}
				if(data['msg_type']=="Success"){
					if(data['mode']=="Insert"){
						window.location="home";
					}
					else{
						var project_st = $('#dpw_project_stage_id').val();
						$('.project_name').text($('.project_title').val());
						if($('#smart_sheet_ip').val()!=""){
							$('#smart_sheet_link').attr('href',$('#smart_sheet_ip').val());
							$('#smart_sheet_iframe').attr('src',$('#smart_sheet_ip').val());
							$('#smart_sheet_link,#show_smartsheet_iframe_btn').removeClass('disp-none');
							$('#smart_sheet_iframe,#hide_smartsheet_iframe_btn').addClass('disp-none');
							$('#edit_smartsheet_link').text("Edit Link");
						}
						else{
							$('#smart_sheet_link').attr('href','');
							$('#smart_sheet_iframe').attr('src','');
							$('#smart_sheet_link,#smart_sheet_iframe,#hide_smartsheet_iframe_btn,#show_smartsheet_iframe_btn').addClass('disp-none');
							$('#edit_smartsheet_link').text("Add Smartsheet Link");
						}
						$('.actual_smartsheet_link').val($('#smart_sheet_ip').val());
						$('#edit_smartsheet_link').removeClass('disp-none');
						$('#smart_sheet_ip,#cancel_smartsheet_link').addClass('disp-none');
						/*previous stage */
							$('.project-st-'+project_st).prevUntil('.stage-heading','.project-stage').removeClass('select incomplete').addClass('complete');
						/*next stage */
							$('.project-st-'+project_st).nextUntil('.stage-heading','.project-stage').removeClass('complete select').addClass('incomplete');
						/*current stage */
							$('.project-st-'+project_st).removeClass('complete incomplete').addClass('select');
					}
					$('.form-msg').html(data['msg']);
					$('.form-msg').css({display:'block',color:'green'});
				}
				else{	
					$('#project_detail_form').find('input[type="text"], textarea').val("");
					$('.form-msg').html(data['msg']);
					$('.form-msg').css({display:'block',color:'red'});
				}
				
				setTimeout(function(){
					$('.form-msg').html("");
				},5000);
			}
		});
	}
	else{
		$('.form-msg').html("Something went wrong...");
		$('.form-msg').css({display:'block',color:'red'});
		$(document).scrollTop(0);
		setTimeout(function(){
			$('.form-msg').html("");
		},5000);
	}
});

/*show smartsheet textbox*/
$('#edit_smartsheet_link').click(function(){
	$(this).addClass('disp-none');
	$('#smart_sheet_ip,#cancel_smartsheet_link').removeClass('disp-none');
});

/*show smartsheet textbox*/
$('#cancel_smartsheet_link').click(function(){
	$(this).addClass('disp-none');
	$('#smart_sheet_ip,#cancel_smartsheet_link').addClass('disp-none');
	$('#smart_sheet_ip').val($('.actual_smartsheet_link').val());
	$('#edit_smartsheet_link').removeClass('disp-none');
});

/*show smartsheet iframe*/
$('#show_smartsheet_iframe_btn').click(function(){
	if($('#smart_sheet_iframe').length>0){
		$('#smart_sheet_iframe').removeClass('disp-none');
		$(this).addClass('disp-none');
		$('#hide_smartsheet_iframe_btn').removeClass('disp-none');
	}
});

/*hide smartsheet iframe*/
$('#hide_smartsheet_iframe_btn').click(function(){
	if($('#smart_sheet_iframe').length>0){
		$('#smart_sheet_iframe').addClass('disp-none');
		$(this).addClass('disp-none');
		$('#show_smartsheet_iframe_btn').removeClass('disp-none');
	}
});


/*$('.prj_textarea').focus(function({
	alert('dd');
	//(this).animate({height:'400px'});
});*/
</script>
<?php 
##==show timeline in edit mode===
if(isset($project_id) && $project_id!=""){?>
<script type="text/javascript">
  // DOM element where the Timeline will be attached
  var container = document.getElementById('visualization');

  // Create a DataSet (allows two way data-binding)
  var items = new vis.DataSet([
    {id: 1, content: 'Created', start: '<?php echo date("Y-m-d",strtotime($design_record_create_date));?>'},
    {id: 2, content: 'Actual Start Date', start: '<?php echo date("Y-m-d",strtotime($actual_start_date));?>','className': 'green'},
    {id: 3, content: 'Planned Start Date', start: '<?php echo date("Y-m-d",strtotime($planned_start_date));?>'},
    {id: 4, content: 'Planned End Date', start: '<?php echo date("Y-m-d",strtotime($planned_end_date));?>','className': 'light-red'},
    {id: 5, content: 'Review', start: '<?php echo date("Y-m-d",strtotime($next_review_date));?>','className': 'blue'}
  ]);

  // Configuration for the Timeline
  var options = {};

  // Create a Timeline
  var timeline = new vis.Timeline(container, items, options);
</script>
<?php }?>
</body>
</html>
