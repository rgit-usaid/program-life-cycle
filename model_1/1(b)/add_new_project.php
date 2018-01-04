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
}

$project_stage_id = '';
if(isset($project_arr)) {
	$project_stage_id = $project_arr['data']['project_stage_id'];
}

?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico"/>
<link rel="stylesheet" href="<?php echo HOST_URL;?>css/vis.css">
<script src="<?php echo HOST_URL;?>/js/vis.js"></script>
<title>USAID-3</title>
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
			<div class="extra_ht"></div><div class="extra_ht"></div>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head"><?php if($project_id!=""){ echo "You are editing project details"; } else echo "Add New Project Details";?></div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="project-detail-blk table-container">
				<form id="project_detail_form" action="home" method="post" autocomplete="off">
				<div class="form-msg usa-alert usa-alert-success disp-none">
    				<div class="usa-alert-body">
      				<h3 class="usa-alert-heading">Success Status</h3>
     				<p class="usa-alert-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod.</p>
    				</div>
			  	</div>
				<div class="form-blk">
					<div class="row project-detail-textarea">
					<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<h2 class="form-blk-head" style="margin-top:0px">Project Details</h2>
						<div>Activites scheduled for concurrent design are located on the Activtiy Tab</div>
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
					<div class="row project-detail-textarea">
						<?php
							$project_title = '';
							if(isset($project_arr)) $project_title = $project_arr['data']['title'];
						?>
						<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<label>Project Title</label>
							<textarea class="form-textarea  sm project_title autoh_textarea" name="title" rows="1" placeholder="Project Title"><?php echo $project_title;?></textarea>
						</div>
						<?php
							$originating_operating_unit_id = $operating_unitinfo_desc = '';
							if(isset($project_arr)) {
								$originating_operating_unit_id = $project_arr['data']['originating_operating_unit_id'];
								$operating_unitinfo_url = API_HOST_URL_PROJECT."get_individual_operating_unit.php?operating_unit_id=".$originating_operating_unit_id;  
								$operating_unitinfo = requestByCURL($operating_unitinfo_url);
								$operating_unitinfo_desc = $operating_unitinfo['data']['operating_unit_description'];
							}
						?>
						<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="pull-right">
							<label>Originating Operating Unit : </label><br/>
								<div class="search_div" style="position:relative">
									<input type="text" name="originating_operating_unit" class="search_txt form-control" autocomplete="off" onKeyUp="search_val(this,'<?php echo API_HOST_URL_PROJECT;?>get_operating_unit.php')" value="<?php echo $operating_unitinfo_desc;?>"/>
									<input type="hidden" name="originating_operating_unit_id" class="textarea_id" value="<?php echo $originating_operating_unit_id;?>" />
									<div class="ajax_data">
										
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<?php
						$estimated_total_funding_amount = '';
						if(isset($project_arr)) $estimated_total_funding_amount = $project_arr['data']['estimated_total_funding_amount'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="validate_ip_par">
							<label>Estimated of Total USAID Project Funding Needed</label>
							<input type="text" class="maxw_350 only_num_with_blank amount_type"  value="<?php echo $estimated_total_funding_amount;?>" name="estimated_total_funding_amount" placeholder="Estimated of Total USAID Project Funding Needed" value="<?php echo $estimated_total_funding_amount;?>"/>
							</div>
						</div>
						<?php
							$implementing_operating_unit_id = $implementing_unitinfo_desc = '';
							if(isset($project_arr)) {
								$implementing_operating_unit_id = $project_arr['data']['implementing_operating_unit_id'];
								$implementing_unitinfo_url = API_HOST_URL_PROJECT."get_individual_operating_unit.php?operating_unit_id=".$implementing_operating_unit_id;  
								$implementing_unitinfo = requestByCURL($implementing_unitinfo_url);
								$implementing_unitinfo_desc = $implementing_unitinfo['data']['operating_unit_description'];
							}
						?>
						<div  class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<div class="pull-right">
							<label>Implementing Operating Unit : </label><br/>
								<div class="search_div" style="position:relative">
									<input type="text" name="implementing_operating_unit" class="search_txt form-control" autocomplete="off" onKeyUp="search_val(this,'<?php echo API_HOST_URL_PROJECT;?>get_operating_unit.php')" value="<?php echo $implementing_unitinfo_desc;?>"/>
									<input type="hidden" name="implementing_operating_unit_id" class="textarea_id" value="<?php echo $implementing_operating_unit_id;?>"/>
									<div class="ajax_data">
										
									</div>
								</div>
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<?php
						$project_purpose = '';
						if(isset($project_arr)) $project_purpose = $project_arr['data']['project_purpose'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Project Purpose</label>
							<textarea class="form-textarea sm autoh_textarea" name="project_purpose" rows="2" placeholder="Project Purpose"><?php echo $project_purpose;?></textarea>
						</div>
					</div>
					<?php
						$engaging_local_actor_plan = '';
						if(isset($project_arr)) $engaging_local_actor_plan = $project_arr['data']['engaging_local_actor_plan'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Plan for Engaging Local Actors</label>
							<textarea class="form-textarea sm autoh_textarea" name="engaging_local_actor_plan" rows="2" placeholder="Plan for Engaging Local Actors"><?php echo $engaging_local_actor_plan;?></textarea>
						</div>
					</div>
					<?php
						$conducting_analyses_plan = '';
						if(isset($project_arr)) $conducting_analyses_plan = $project_arr['data']['conducting_analyses_plan'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Plan for Conducting Analyses</label>
							<textarea class="form-textarea sm autoh_textarea" name="conducting_analyses_plan" rows="2" placeholder="Plan for Conducting Analyses"><?php echo $conducting_analyses_plan;?></textarea>
						</div>
					</div>
					<?php
						$use_of_govt_to_govt_plan = '';
						if(isset($project_arr)) $use_of_govt_to_govt_plan = $project_arr['data']['use_of_govt_to_govt_plan'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<label>Plan for Possible Use of Govt to Govt</label>
							<textarea class="form-textarea sm autoh_textarea" name="use_of_govt_to_govt_plan" rows="2" placeholder="Plan for Possible Use of Govt to Govt"><?php echo $use_of_govt_to_govt_plan;?></textarea>
						</div>
					</div>
					<?php
						$proposed_design_schedule = '';
						if(isset($project_arr)) $proposed_design_schedule = $project_arr['data']['proposed_design_schedule'];
					?>
					<div class="row project-detail-textarea">
						<div  class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<label>Proposed Design Schedule</label><br/>
							<input type="hidden" value="<?php echo $proposed_design_schedule;?>" class="actual_smartsheet_link"/>
						   	<input type="text" class="maxw_350  disp-none " id="smart_sheet_ip" value="<?php echo $proposed_design_schedule;?>" name="proposed_design_schedule" placeholder="Proposed Design Schedule" />
							<div class="extra_ht"></div>
							
							<a id="smart_sheet_link"  target="_blank" class="bold <?php if($proposed_design_schedule==""){ ?>disp-none <?php }?> " href="<?php echo $proposed_design_schedule;?>">Smartsheet Project Link</a>  
							
							<a class="btn btn-blue disp-none" id="cancel_smartsheet_link">Cancel</a> 
							<a class="btn btn-green" id="edit_smartsheet_link"><?php if($proposed_design_schedule==""){ ?> Add Smartsheet Link <?php } else {?> Edit Link <?php }?></a> 
							
							
							<a id="show_smartsheet_iframe_btn" class="btn btn-warning <?php if($proposed_design_schedule==""){ ?>disp-none<?php }?> "><i class="fa fa-toggle-on" aria-hidden="true"></i> View Snapshot</a>
							<a id="hide_smartsheet_iframe_btn" class="btn btn-green disp-none"><i class="fa fa-toggle-off" aria-hidden="true"></i> Hide Snapshot</a>	
							<div class="extra_ht"></div>
							<?php if($project_id!=''){?>
								<IFRAME id="smart_sheet_iframe" WIDTH=1000 HEIGHT=700 FRAMEBORDER=0  class="disp-none" SRC="<?php echo $proposed_design_schedule;?>"></IFRAME> 
							<?php }?>
						</div>
						<?php
							$proposed_design_cost = '';
							if(isset($project_arr)) $proposed_design_cost = $project_arr['data']['proposed_design_cost'];
						?>
						<div  class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
							<div class="validate_ip_par">
							<label>Proposed Design Cost</label><br/>
							<input type="text" class=" maxw_350 only_num_with_blank amount_type"  value="<?php echo $proposed_design_cost;?>" name="proposed_design_cost" placeholder="Proposed Design Cost"/>
							</div>
						</div>	
					</div>
					<!--<div class="ip-outer">
						<input id="profile-image-upload" class="hidden" type="file">
						<div id="profile-image">click here to change profile image</div>
					</div>-->
					</div>
				<input type="hidden" name="project_id" value="<?php echo $project_id;?>"/>
				<button class="usa-button-outline reset_form" type="button">Cancel</button>
				<button class="usa-button-active" id="save_project" type="button">Save</button>
				<a href="home" style="text-decoration:underline;">Back to list</a>
				</form>	
			</div>
			
			
			</div>
			<!--add new project end-->
     	  </div>
		</div>
		<!--add new project start-->
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL;?>/js/jquery-ui.min.js"></script>	
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script src="<?php echo HOST_URL?>js/add_new_project.js"></script>
<script>
$( "#find" ).autocomplete({
  source: function( request, response ) {
	$.ajax( {
	  url: "<?php echo API_HOST_URL_PROJECT;?>get_operating_unit.php",
	  dataType: "jsonp",
	  data: {
		term: request.term.data
	  },
	  success: function( data ) {
		response( data );
	  }
	} );
  },
  minLength: 2,
  select: function( event, ui ) {
	log( "Selected: " + ui.item.value + " aka " + ui.item.id );
  }
});
	
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
						
						if(project_st>2){
							$('.project_appraisal_doc_link').removeClass('disp-none');
						}
						else{
							$('.project_appraisal_doc_link').addClass('disp-none');
						}
						
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
					$('.form-msg').addClass('usa-alert-success').removeClass('disp-none');
					$('.form-msg').find('.usa-alert-heading').text('Success');
					$('.form-msg').find('.usa-alert-text').text(data['msg']);
				}
				else{
					$('.form-msg').addClass('usa-alert-error').removeClass('disp-none');
					$('.form-msg').find('.usa-alert-heading').text('Error');
					$('.form-msg').find('.usa-alert-text').text(data['msg']);
				}
				
				setTimeout(function(){
					$('.form-msg').addClass('disp-none');
				},5000);
			}
		});
	}
	else{
		$('.form-msg').addClass('usa-alert-error').removeClass('disp-none');
		$('.form-msg').find('.usa-alert-heading').text('Error');
		$('.form-msg').find('.usa-alert-text').text('Something went wrong..'); 
		$(window).scrollTop(0);
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

/*reset form*/
$('.reset_form').click(function(){
	$(this).closest('form').find('input[type="text"],textarea,select').val("");
});
</script>
<script>
$('.amount_type').keyup(function(){
	$(this).validate_ip();
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
</body>
</html>
