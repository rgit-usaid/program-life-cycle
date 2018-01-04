<body class="page-ui-components">
	<!-- Modal -->
  	 <?php include('includes/project_detail_popup.php');?>
	
	<link href="<?php echo HOST_URL;?>css/timeline.css" type="text/css" rel="stylesheet"/>	
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<div>
			<?php include('includes/project_header.php');?>
			<div class="extra_ht"></div><div class="extra_ht"></div>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head">Project Appraisal Document</div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="project-detail-blk table-container">
				<div class="form-msg usa-alert disp-none">
    				<div class="usa-alert-body">
      				<h3 class="usa-alert-heading"></h3>
     				<p class="usa-alert-text"></p>
    				</div>
			  	</div>
				<div class="form-blk">
					<form action="add_new_project" method="post">
					<div class="row project-detail-textarea">
						<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<label>Project Design Plan is here</label><br/>
							<a id="view_design_plan" class="pointer"><i class="fa fa-link" aria-hidden="true"></i> Project Design Plan</a>
							<input type="hidden" value="2" name="view_only_mode"/>
						</div>
						<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
							<a href="view_project_appraisal_archive.php">Project appraisal Change Log</a>
						</div>
					</div>
					</form>
					<form id="project_detail_form" action="home" method="post" autocomplete="off">				
					<div class="row project-detail-textarea">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<label>Title</label>
							<textarea class="form-textarea sm project_title autoh_textarea" name="title" rows="1" placeholder="Project Title"><?php echo $title;?></textarea>
						</div>
						<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<?php if($project_id!=""){ ?>
							<div class="pull-right">
							<label>Project Stage</label><br/>
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
							<div class="clear"></div>
							<?php } ?>
						</div>
					</div>
					<?php
						$project_purpose = '';
						if(isset($project_arr)) $project_purpose = $project_arr['data']['project_purpose'];
					?>
					<div class="row project-detail-textarea">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Purpose</label>
							<textarea class="form-textarea sm project_title autoh_textarea project_purpose"  name="project_purpose" rows="1" placeholder="Purpose"><?php echo $project_purpose;?></textarea>
						</div>
					</div>
					<?php
						$project_description = '';
						if(isset($project_arr)) $project_description = $project_arr['data']['project_description'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Description</label>
							<textarea class="form-textarea sm autoh_textarea project_description" name="project_description" rows="2" placeholder="Project Description"><?php echo $project_description;?></textarea>
						</div>
					</div>
					<?php
						$context = '';
						if(isset($project_arr)) $context = $project_arr['data']['context'];
					?>
					<div class="row project-detail-textarea">	
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Context</label>
							<textarea class="form-textarea sm autoh_textarea " name="context" rows="2" placeholder="Project Context"><?php echo $context;?></textarea>
						</div>
					</div>
					<?php
						$leveraged_resources = '';
						if(isset($project_arr)) $leveraged_resources = $project_arr['data']['leveraged_resources'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Other Levaraged Resources</label>
							<textarea class="form-textarea sm autoh_textarea" name="leveraged_resources" rows="2" placeholder="Project Other Levaraged Resources"><?php echo $leveraged_resources;?></textarea>
						</div>
					</div>
					<?php
						$conclusions_and_analyses_summary = '';
						if(isset($project_arr)) $conclusions_and_analyses_summary = $project_arr['data']['conclusions_and_analyses_summary'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Summary of Conclusions and Analyses</label>
							<textarea class="form-textarea sm autoh_textarea" name="conclusions_and_analyses_summary" rows="2" placeholder="Summary of Conclusions and Analyses"><?php echo $conclusions_and_analyses_summary;?></textarea>
						</div>
					</div>
					<?php
						$management_plan = '';
						if(isset($project_arr)) $management_plan = $project_arr['data']['management_plan'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Management Plan</label>
							<textarea class="form-textarea sm autoh_textarea" name="management_plan" rows="2" placeholder="Management Plan"><?php echo $management_plan;?></textarea>
						</div>
					</div>
					<?php
						$financial_plan = '';
						if(isset($project_arr)) $financial_plan = $project_arr['data']['financial_plan'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Financial Plan</label>
							<textarea class="form-textarea sm autoh_textarea" name="financial_plan" rows="2" placeholder="Financial Plan"><?php echo $financial_plan;?></textarea>
						</div>
					</div>
					<?php
						$monitoring_evaluation_and_learning_plan = '';
						if(isset($project_arr)) $monitoring_evaluation_and_learning_plan = $project_arr['data']['monitoring_evaluation_and_learning_plan'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Monitoring, Evaluation and Learning Plan</label>
							<textarea class="form-textarea sm autoh_textarea" name="monitoring_evaluation_and_learning_plan" rows="2" placeholder="Project Summary of Conclusions And Analyses"><?php echo $monitoring_evaluation_and_learning_plan;?></textarea>
						</div>
					</div>
					<?php
						$activity_plan = '';
						if(isset($project_arr)) $activity_plan = $project_arr['data']['activity_plan'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Activity Plan</label>
							<textarea class="form-textarea sm autoh_textarea" name="activity_plan" rows="2" placeholder="Is this already addressed in the Activity tab?"><?php echo $activity_plan;?></textarea>
						</div>
					</div>
					<?php
						$logical_framework_discretion = '';
						if(isset($project_arr)) $logical_framework_discretion = $project_arr['data']['logical_framework_discretion'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Logic Model, AUPGS(for G2G) and Other Required Annexes</label>
							<textarea class="form-textarea sm autoh_textarea" name="logical_framework_discretion" rows="2" placeholder="Artifacts"><?php echo $logical_framework_discretion;?></textarea>
						</div>
					</div>
					<div class="row project-detail-textarea">
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
							<div class="calendar-blk">
								<div class="sm-head">Planned Start Date</div>
								<table class="project_dates no-bdr">
									<tr class="head">
										<td>Month</td>
										<td>Day</td>
										<td>Year</td>
									</tr>
									<?php
										$explode_date = array();
										$planned_start_date = '';
										if(isset($project_arr)) $planned_start_date = $project_arr['data']['planned_start_date'];
										
										$explode_date = explode("/",$planned_start_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 	
									<tr class="pls_date">
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>" placeholder="MM"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>" placeholder="DD"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>" placeholder="YYYY"/></td>
									</tr>
								</table>
								<input type="hidden" name="planned_start_date" class="formatted_date" value="<?php echo $planned_start_date;?>"/>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
							<div class="calendar-blk">
								<div class="sm-head">Planned End Date</div>
								<table class="project_dates no-bdr">
									<tr class="head">
										<td>Month</td>
										<td>Day</td>
										<td>Year</td>
									</tr>
									<?php
										$explode_date = array();
										$planned_end_date = '';
										if(isset($project_arr)) $planned_end_date = $project_arr['data']['planned_end_date'];
										
										$explode_date = explode("/",$planned_end_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 	
									<tr class="ple_date">
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>" placeholder="MM"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>" placeholder="DD"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>" placeholder="YYYY"/></td>
									</tr>
								</table>
								<input type="hidden" name="planned_end_date" class="formatted_date" value="<?php echo $planned_end_date;?>"/>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 date_blk">
							<div class="calendar-blk">
								<div class="sm-head">Project Review Date</div>
								<table class="project_dates no-bdr">
									<tr class="head">
										<td>Month</td>
										<td>Day</td>
										<td>Year</td>
									</tr>
									<?php
										$explode_date = array();
										$next_review_date = '';
										if(isset($project_arr)) $next_review_date = $project_arr['data']['next_review_date'];
										
										$explode_date = explode("/",$next_review_date);
										$month = trim($explode_date[0]);
										$date = trim($explode_date[1]);
										$year = trim($explode_date[2]);
									?> 
									<tr class="nex_date"> 
										<td><input type='text' class="form-control month date_ip only_num" value="<?php echo $month?>" placeholder="MM"/></td>
										<td><input type='text' class="form-control date date_ip only_num" value="<?php echo $date?>" placeholder="DD"/></td>
										<td><input type='text' class="form-control year date_ip only_num" value="<?php echo $year?>" placeholder="YYYY"/></td>
									</tr>
								</table>
								<input type="hidden" name="next_review_date" class="formatted_date" value="<?php echo $next_review_date;?>"/>
							</div>
						</div>
					</div>
					<?php 
					##==show timeline in edit mode===
					if(isset($project_id) && $project_id!=""){?>
					<div class="form-blk">
						<header><h3 class="form-blk-head">Project Timeline</h3></header>
						<div id="visualization"></div>	
					</div>
					<?php }?>
						<input type="hidden" name="project_id" value="<?php echo $project_id;?>"/>
						<button class="usa-button-outline reset_form" type="button">Cancel</button>
						<button class="usa-button-active" id="save_project" type="button">Save</button>
						<a href="home" style="text-decoration:underline;">Back to list</a>
					</form>
				</div>

				<div>
				
			</div>
			
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
var project_activity_arr = new Array();
$('#redirect_to_design_plan').click(function(){
	$('#redirect_to_design_plan_form').submit();
});
$('#save_project').click(function(){
	var form_serialize = $('#project_detail_form').serialize();
	form_serialize= form_serialize+'&manage_project_appraisal_doc=manage_project_appraisal_doc';
	var error = false;
	var error_msg ="Something went wrong..";
	$(window).scrollTop(0);
	
	/*validate planned start date*/
	if(!validate_date($('.pls_date').find('.month').val(),$('.pls_date').find('.date').val(),$('.pls_date').find('.year').val())){
		error = true;
		error_msg = "Invalid Planned Start Date";
	}
	
	/*validate planned end date*/
	if(!validate_date($('.ple_date').find('.month').val(),$('.ple_date').find('.date').val(),$('.ple_date').find('.year').val())){
		error = true;
		error_msg = "Invalid Planned End Date";
	}
	
	/*validate planned date*/
	if(!validate_date($('.nex_date').find('.month').val(),$('.nex_date').find('.date').val(),$('.nex_date').find('.year').val())){
		error = true;
		error_msg = "Invalid Next End Date";
	}
	
	if($('#project_detail_form').find('.invalid_ip').length==0 && error!=true){
		$.ajax({
			url:'<?php echo HOST_URL?>ajaxfiles/manage_project.php',
			type:'POST',
			data:form_serialize,
			success:function(data){
				window.location="";
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
							
							
				data = JSON.parse(data);
				var planned_start_date = $('.pls_date').find('.year').val()+'-'+$('.pls_date').find('.month').val()+'-'+$('.pls_date').find('.date').val();
				var planned_end_date = $('.ple_date').find('.year').val()+'-'+$('.ple_date').find('.month').val()+'-'+$('.ple_date').find('.date').val();
		
				var next_review_date = $('.nex_date').find('.year').val()+'-'+$('.nex_date').find('.month').val()+'-'+$('.nex_date').find('.date').val();
				
				// DOM element where the Timeline will be attached
				var container = document.getElementById('visualization');
				
				// Create a DataSet (allows two way data-binding)
				var items = new vis.DataSet([
				{id: 1, content: 'Planned Start Date', start: planned_start_date},
				{id: 2, content: 'Planned End Date', start: planned_end_date,'className': 'light-red'},
				{id: 3, content: 'Review', start: next_review_date,'className': 'blue'}
				]);
				
				$(container).html("");
				// Configuration for the Timeline
				var options = {};
				
				// Create a Timeline
				var timeline = new vis.Timeline(container, items, options);
				
				$('#project_detail_form').find('.formatted_date').val("");
				if($('#smart_sheet_iframe').length>0){
					$('#smart_sheet_iframe').addClass('disp-none');
				}
				if(data['msg_type']=="Success"){
					$('.form-msg').addClass('usa-alert-success').removeClass('disp-none');
					$('.form-msg').find('.usa-alert-heading').text('Success');
					$('.form-msg').find('.usa-alert-text').text(data['msg']);
				}
				else{	
					$('.form-msg').addClass('usa-alert-error').removeClass('disp-none');
					$('.form-msg').find('.usa-alert-heading').text('Error');
					$('.form-msg').find('.usa-alert-text').text('Something went wrong..'); 
					
				}
				
				setTimeout(function(){
					$('.form-msg').addClass('disp-none').removeClass('usa-alert-success usa-alert-error');
				},5000);
			}
		});
	}
	else{
		
		$('.form-msg').addClass('usa-alert-error').removeClass('disp-none');
		$('.form-msg').find('.usa-alert-heading').text('Error');
		$('.form-msg').find('.usa-alert-text').text(error_msg); 
		
		setTimeout(function(){
			$('.form-msg').addClass('disp-none');
		},5000);
	}
});

/*show smartsheet textbox*/
$('#edit_smartsheet_link').click(function(){
	$(this).addClass('disp-none');
	$('#smart_sheet_ip,#cancel_smartsheet_link').removeClass('disp-none');
});

/*cancel smartsheet*/
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

/*reset form*/
$('.reset_form').click(function(){
	$(this).closest('form').find('input[type="text"],textarea,select').val("");
});

/*view desing plan*/
$('#view_design_plan').click(function(){
	$(this).closest('form').submit();
});
/*$('.prj_textarea').focus(function({
	alert('dd');
	//(this).animate({height:'400px'});
});*/
</script>
<?php for($i=0; $i<count($project_activity_arr['data']);$i++){ 
?>
	<script>
		var obj = new Object({'planned_start_date':'<?php echo date('Y-m-d',strtotime($project_activity_arr['data'][$i]['planned_start_date']));?>','planned_end_date':'<?php echo date('Y-m-d',strtotime($project_activity_arr['data'][$i]['planned_end_date']));?>'});
		project_activity_arr.push(obj);
		console.log(project_activity_arr);
	</script>		
<?php }?>
<?php 
##==show timeline in edit mode===
if(isset($project_id) && $project_id!=""){?>
<script type="text/javascript">
  // DOM element where the Timeline will be attached
  var container = document.getElementById('visualization');

  // Create a DataSet (allows two way data-binding)
  var timeline_dates_arr = new Array();
  timeline_dates_arr = [
    {id: 1, content: 'Planned Start Date', start: '<?php echo date("Y-m-d",strtotime($planned_start_date));?>'},
    {id: 2, content: 'Planned End Date', start: '<?php echo date("Y-m-d",strtotime($planned_end_date));?>','className': 'light-red'},
    {id: 3, content: 'Review', start: '<?php echo date("Y-m-d",strtotime($next_review_date));?>','className': 'blue'}
  ];
  
  var j =4;
  for(var i=0; i <project_activity_arr.length; i++){
	var act_timeline_obj = new Object(); 
	act_timeline_obj = {id: j, content: 'Activity Planned Start Date', start: project_activity_arr[i]['planned_start_date']};
	timeline_dates_arr.push(act_timeline_obj);
	act_timeline_obj = {id: j+1, content: 'Activity Planned End Date', start: project_activity_arr[i]['planned_end_date'],'className': 'light-red'};
	timeline_dates_arr.push(act_timeline_obj);
	j = j+2;
  }
  
  var items = new vis.DataSet(timeline_dates_arr);

  // Configuration for the Timeline
  var options = {};

  // Create a Timeline
  var timeline = new vis.Timeline(container, items, options);
</script>
<?php }?>
</body>