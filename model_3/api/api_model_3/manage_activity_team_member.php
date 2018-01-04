<?php include('config/functions.inc.php');
##==validate user====
validate_user();

###request for get single project details using project id ===========
$project_id = '';
$activity_id = '';
if(isset($_SESSION['project_id']) && isset($_SESSION['activity_id']))
{
	$project_id = $_SESSION['project_id'];
	$activity_id = $_SESSION['activity_id'];
	
	$url = "http://rgdemo.com/usaid/api/get_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id;  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $project_activity_arr = json_decode($output,true);
}
else{
	header("Location:list_activity");
}

?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-AMP</title>
<?php include('includes/resources.php');?>
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<div>
			<!--project overview start-->
			<?php include('includes/activity_header.php');?>	 
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head">Manage Team Member</div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="table-container">
				<div class="form-blk">
					<!--add team member block start-->
						<div>
						<button class="btn btn-blue add_new_team"><i class="fa fa-user" aria-hidden="true"></i> Add new team member</button>
						<form id="add_new_team">
							<div class="row add_team_blk disp-none">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 search_team_member">
								<div style="height:10px;"></div>
								<div class="form-msg"></div>
								<header><h3 class="form-blk-head bold paddt-20">Team Member</h3></header>
								<div class="desc-txt">
									Start typing the name of the person you want to add to the team
								</div>
								<div id="search_div" style="position:relative">
									<input type="hidden" name="project_id" class="project_id" value="<?php echo $project_id;?>"/>
									<input type="text" name="employee_name" id="find_team_member" class="form-control" autocomplete="off"/>
									<input type="hidden" name="employee_id" id="find_team_member_id" />
									<div class="ajax_data">
										
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<header><h3 class="form-blk-head bold paddt-20">Project Role</h3></header>
								<div class="desc-txt">
									Select the person role
								</div>
								<div class="row threshold">
									<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
										<div>
											<label><input type="radio" name="project_team_role" class="team_role" onClick="show_save_btn()" value="Project Manager"/> Project Manager</label>
											<div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="team_role" onClick="show_save_btn()" value="COR/AOR"/> COR/AOR</label>
											<div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="team_role" onClick="show_save_btn()" value="CO"/> CO</label>
											<div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="team_role" onClick="show_save_btn()" value="Financial Advisor"/> Financial Advisor</label><div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="team_role" onClick="show_save_btn()" value="Technical Advisor"/> Technical Advisor</label>
										</div>
									</div>
									<div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
										<div>
											<label><input type="radio" name="project_team_role" class="team_role" onClick="show_save_btn()" value="Legal Advisor"/> Legal Advisor</label>
											<div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="team_role" onClick="show_save_btn()" value="Communications Advisor"/> Communications Advisor</label><div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="team_role" onClick="show_save_btn()" value="Project Administration"/> Project Administration</label><div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="team_role" onClick="show_save_btn()" value="Quality Assurance"/> Quality Assurance</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 search_team_member">
								<header><h3 class="form-blk-head bold paddt-20">Start Date</h3></header>	
								<div class="desc-txt">
									Date the person starts on the project
								</div>
								<div class="calendar-blk" style="margin-bottom:20px">
									<table class="project_dates">
										<tr class="head">
											<td>Month</td>
											<td>Day</td>
											<td>Year</td>
										</tr>
										<tr>
											<td><input type='text' class="form-control month date_ip only_num" value="" onKeyup="show_save_btn()"/></td>
											<td><input type='text' class="form-control date date_ip only_num" value="" onKeyup="show_save_btn()"/></td>
											<td><input type='text' class="form-control year date_ip only_num" value="" onKeyup="show_save_btn()"/></td>
										</tr>
									</table>
									<input type="hidden" name="team_member_start_date" class="formatted_date" value=""/>
								</div>
								<input type="hidden" value="add_project_team" name="add_project_team"/>
								<input class="btn btn-green save_team" value="Save" type="button"/>
								<input class="btn btn-blue cancel_team" value="Cancel" type="button"/>
							</div>
							</div>
							<div class="gray-line"></div>
						</form>
					</div>
					<!--add team member block end-->
					<!--edit team member block start-->
						<div class="edit_team_member_blk disp-none">
						<form id="edit_team_member_info">
							<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 search_team_member">
								<header><h3 class="form-blk-head bold paddt-20">Edit Team Member</h3></header>
								<div class="form-msg"></div>
								<div style="height:10px;"></div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 img-blk">
									<img src="img/user.png"  width="130" class="center-block emp_img"/>
									<input type="hidden" name="project_id" class="project_id" value="<?php echo $project_id;?>"/>
									<input type="hidden" class="emp_id" name="emp_id"/>
									<input type="hidden" class="emp_old_role" name="emp_old_role"/>
									<div class="user-info">
										<div class="emp_name">Seema Jauhari</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<header><h3 class="form-blk-head bold paddt-20">Project Role</h3></header>
								<div class="desc-txt">
									Select the person role
								</div>
								<div class="row threshold">
									<div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
										<div>
											<label><input type="radio" name="project_team_role" class="project_team_role" onClick="show_save_btn()" value="Project Manager"/> Project Manager</label><div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="project_team_role" onClick="show_save_btn()" value="COR/AOR"/> COR/AOR</label><div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="project_team_role" onClick="show_save_btn()" value="CO"/> CO</label><div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="project_team_role" onClick="show_save_btn()" value="Financial Advisor"/> Financial Advisor</label><div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="project_team_role" onClick="show_save_btn()" value="Technical Advisor"/> Technical Advisor</label>
										</div>
									</div>
									<div class="col-lg-7 col-md-6 col-sm-6 col-xs-12">
										<div>
											<label><input type="radio" name="project_team_role" class="project_team_role" onClick="show_save_btn()" value="Legal Advisor"/> Legal Advisor</label><div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="project_team_role" onClick="show_save_btn()" value="Communications Advisor"/> Communications Advisor</label><div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="project_team_role" onClick="show_save_btn()" value="Project Administration"/> Project Administration</label><div class="extra_ht"></div>
											<label><input type="radio" name="project_team_role" class="project_team_role" onClick="show_save_btn()" value="Quality Assurance"/> Quality Assurance</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 search_team_member">
								<input type="hidden" value="edit_project_team" name="edit_project_team"/>
								<input class="btn btn-green update_team disp-none" value="Save" type="button"/>
								<input class="btn btn-blue noupdate_in_team" value="Cancel" type="button"/>
							</div>
							</div>
							<div class="gray-line"></div>
						</form>
						</div>
					<!--edit team member block end-->
					<!--add current team member block start-->
						<div class="current_team">
							<header><h3 class="form-blk-head bold paddt-20">Current Team</h3></header>
							<div class="desc-txt">
								Any one who has an active role in the project. At minimum a project must have an Project Manager and COR/AOR. Anyone can edit the team.
							</div>
							<div class="form-msg"></div>
							<div class="row current_team_data">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="gray-line"></div></div>
							</div>
						</div>
					<!--add current team member block end-->
					<!--team marker block start-->
						<div class="current_team">
							<header><h3 class="form-blk-head bold">Team Marker</h3></header>
							<div class="desc-txt">
								A marker to indetify the team working on.
							</div>
							<div class="form-msg"></div>
							<div style="height:10px;"></div>
							<form id="team_marker_form">
								<select id="team_marker" class="form-control" name="team_marker">
									<option>Select</option>
									<option value="Peace and Security" <?php if($team_marker=="Peace and Security"){ echo "selected='selected'";}?>>Peace and Security</option>
									<option value="Democracy, Human Rights and Governance" <?php if($team_marker=="Democracy, Human Rights and Governance"){ echo "selected='selected'";}?>>Democracy, Human Rights and Governance</option>
									<option value="Health" <?php if($team_marker=="Health"){ echo "selected='selected'";}?>>Health</option>
									<option value="Education and Social Services" <?php if($team_marker=="Education and Social Services"){ echo "selected='selected'";}?>>Education and Social Services</option>
									<option value="Economic Growth" <?php if($team_marker=="Economic Growth"){ echo "selected='selected'";}?>>Economic Growth</option>
									<option value="Humanitarian Assistance" <?php if($team_marker=="Humanitarian Assistance"){ echo "selected='selected'";}?>>Humanitarian Assistance</option>
								</select>
							</form>
							<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="gray-line"></div></div></div>
						</div>
					<!--team marker block end-->
					<!--old team start-->
						<button class="btn btn-blue hide_old_team_btn">Hide team history</button>
						<button class="btn btn-green show_old_team_btn disp-none">Show team history</button>	
						<div class="table-container">
							<div class="form-blk">
							<!--old team member block start-->
							<div class="old_team_blk">	
								<header><h3 class="form-blk-head bold paddt-20">Team History</h3></header>
								<div class="row old_team_data">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="gray-line"></div></div>
								</div>
							</div>
						</div>		
					</div>
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
<script src="<?php echo HOST_URL?>js/manage_team_member.js"></script>	
<script>
	$('.save_team').click(function(){
		var form_data = $('#add_new_team').serialize();
		$('.form-msg').removeClass('success error');
		if($('#add_new_team').find('.invalid_ip').length==0){
			$.ajax({
				url:'<?php echo HOST_URL?>ajaxfiles/manage_project_team.php',
				type:'POST',
				data: form_data,
				success:function(data){
					var project_id = $('.project_id').val();
					var data = JSON.parse(data);
					reset_add_new_team_form();
					$('.save_team').addClass('disp-none');
					$('#add_new_team').find('.form-msg').removeClass('success error');
					
					if(data['msg_type']=='Success'){
						$('#add_new_team').find('.form-msg').addClass('success');
					}
					else{
						$('#add_new_team').find('.form-msg').addClass('error');
					}
					$('#add_new_team').find('.form-msg').html(data['msg']);
					get_current_team(project_id);
					get_old_team(project_id);
					setTimeout(function(){
						$('#add_new_team').find('.form-msg').html("");
					},2500);
				}
			});
		}
		else{
			$('#add_new_team').find('.form-msg').html("Something went wrong...");
			$('#add_new_team').find('.form-msg').addClass('error');
			$(document).scrollTop(0);
			setTimeout(function(){
				$('#add_new_team').find('.form-msg').html("");
			},5000);
		}
	});
	
	function get_current_team(project_id){
		$.ajax({
			url:'<?php echo HOST_URL?>ajaxfiles/manage_project_team.php',
			type:'POST',
			data: {'get_project_team':'get_project_team',project_id:project_id},
			success:function(result){
				$('.current_team_data').html(result);
			}
		});
	}
	
	function get_old_team(project_id){
		$.ajax({
			url:'<?php echo HOST_URL?>ajaxfiles/manage_project_team.php',
			type:'POST',
			data: {'get_old_project_team':'get_old_project_team',project_id:project_id},
			success:function(result){
				$('.old_team_data').html(result);
			}
		});
	}
	
	/*load team member*/
	get_current_team('<?php echo $project_id;?>');
	
	/*load team member*/
	get_old_team('<?php echo $project_id;?>');
	
	/*remove team member*/
	$(document).on('click','.remove_team_member',function(){
		var form_data = $(this).closest('form').serialize();
		var project_id = $('.project_id').val();
		var emp_name =$(this).closest('form').find('.emp_name').val();
		reset_add_new_team_form();
		reset_edit_teammember_form();
		$('.edit_team_member_blk').addClass('disp-none');
		var msg= "Are you sure to remove "+emp_name+" from project?";
		if(confirm(msg)){
			$.ajax({
				url:'<?php echo HOST_URL?>ajaxfiles/manage_project_team.php',
				type:'POST',
				data: form_data +'&remove_team_member=remove_team_member'+'&project_id='+project_id,
				context:this,
				success:function(data){
					var project_id = $('.project_id').val();
					var data = JSON.parse(data);
					if(data['msg_type']=='Success'){
						$('.current_team').find('.form-msg').addClass('success');
					}
					else{
						$('.current_team').find('.form-msg').addClass('error');
					}
					$('.current_team').find('.form-msg').html(data['msg']);
					get_current_team(project_id);
					get_old_team(project_id);
					setTimeout(function(){
						$('.current_team').find('.form-msg').html("");
					},5000);
				}
			});
		}
	});
	
	/*edit team member*/
	$(document).on('click','.edit_team_member',function(){
		reset_add_new_team_form();
		$('.add_team_blk').addClass('disp-none');
		$('.edit_team_member_blk').removeClass('disp-none');
		var emp_id = $(this).closest('.team_member_info_form').find('.emp_id').val();
		$('.edit_team_member_blk').find('.emp_id').val(emp_id);
		var name = $(this).closest('.team_member_info_form').find('.emp_name').val();
		$('.edit_team_member_blk').find('.emp_name').text(name);
		var emp_role = $(this).closest('.team_member_info_form').find('.emp_role').val();
		$('.edit_team_member_blk').find('.emp_old_role').val(emp_role);
		var team_role = $('.edit_team_member_blk').find('.project_team_role');
		var emp_img = $(this).closest('.team_member_info_form').find('.emp_img').val(); 
		$('.edit_team_member_blk').find('.emp_img').attr('src',emp_img);
		$(team_role).each(function(index, elem){
			if($(elem).val()==emp_role){
				$(elem).closest('label').css({'display':'none'});
				$(elem).closest('label').next('.extra_ht').addClass('disp-none');
				return false;
			}
		});
	});
	
	$('.project_team_role').click(function(){
		$('.update_team').removeClass('disp-none');
	});
	$('.noupdate_in_team').click(function(){
		$('.update_team').addClass('disp-none');
		reset_edit_teammember_form();
		$('.edit_team_member_blk').addClass('disp-none');
	});
	
	$(document).on('click','.update_team',function(){
		var form_data = $('#edit_team_member_info').serialize();
		var project_id = $('#edit_team_member_info').find('.project_id').val();
		$.ajax({
			url:'<?php echo HOST_URL?>ajaxfiles/manage_project_team.php',
			type:'POST',
			data: form_data,
			success:function(data){
				var project_id = $('.project_id').val();
				var data = JSON.parse(data);
				if(data['msg_type']=='Success'){
					$('#edit_team_member_info').find('.form-msg').addClass('success');
					reset_edit_teammember_form();
					$('.noupdate_in_team').addClass('disp-none');
				}
				else{
					$('#edit_team_member_info').find('.form-msg').addClass('error');
				}
				$('#edit_team_member_info').find('.form-msg').html(data['msg']);
				get_current_team(project_id);
				setTimeout(function(){
					$('#edit_team_member_info').find('.form-msg').html("");
					$('.edit_team_member_blk').addClass('disp-none');
					$('.noupdate_in_team').removeClass('disp-none');
				},3000);
			}
		});
	});
	
	$('#team_marker').change(function(){
		if($(this).val()!=""){
			if(confirm("Are sure to change the Team Marker?")){
				var form_data = $('#team_marker_form').serialize();
				var project_id = $('#edit_team_member_info').find('.project_id').val();
				$.ajax({
					url:'<?php echo HOST_URL?>ajaxfiles/manage_project_team.php',
					type:'POST',
					data: form_data+'&project_id='+project_id+'&change_team_marker=change_team_marker',
					success:function(data){
						$('#team_marker_form').find('.form-msg').html("Team marker changed successfully.");
						$('#team_marker_form').find('.form-msg').addClass('success');
						setTimeout(function(){
							$('#team_marker_form').find('.form-msg').html("");
						},3000);
					}
				});
			}
		}
	});
</script>
</body>
</html>
