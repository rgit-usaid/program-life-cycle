<?php include('config/functions.inc.php');
##==validate user====
validate_user();

###request for get single project details using project id ===========
$project_id = '';
$activity_id = '';
if(isset($_SESSION['project_id'])){
	$project_id = $_SESSION['project_id'];
}

if(isset($_SESSION['project_id']) && isset($_REQUEST['activity_id']))
{	
	$_SESSION['activity_id'] = $activity_id = $_REQUEST['activity_id'];
}

if(isset($_SESSION['project_id']) && isset($_SESSION['activity_id']))
{	
	$activity_id = $_SESSION['activity_id'];
}
if($project_id!="" && $activity_id!=""){

	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $project_arr = json_decode($output,true);
	
	$url = API_HOST_URL_PROJECT."get_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id;  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $project_activity_arr = json_decode($output,true);
	
	$url = API_HOST_URL_PROJECT."get_all_program_element.php";  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    $program_elem_arr = json_decode($output,true);

}
$page_type="activity_pages";
?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico" />
<title>USAID-AMP</title>
<?php include('includes/resources.php');?>
<link rel="stylesheet" type="text/css" href="css/jquery.multiselect.css" />
<style>
.ms-options-wrap ul, .ms-options-wrap ul li {list-style:none; padding-left:10px}
.ms-options-wrap{display:none;}
</style>
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<div>
			<?php include('includes/activity_header.php');?>
			<div class="tbl-block">
				<div class="tbl-caption">
				<div class="tbl-content-head">Manage Activity Program Element</div>
				<div class="tbl-content-btn">
					<a href="#" class="tbl-up"><i class="fa fa-chevron-up"></i></a> 
					<a href="#" class="tbl-down"><i class="fa fa-chevron-down"></i></a>
				</div>
				<div class="clear"></div>
			</div>
			<!--add new project start-->
			<div class="sector_blk table-container">
				<div class="row">
				<div class="col-sm-12 col-xs-12 text-right">
			<a href="view_program_element_archive.php">Program Element Change Log</a>
										</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 add_prgm_elem_blk">
					<div class="form-msg"></div>
					<div class="form-blk">
						<div class="add_program_element disp-none1">
							<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<select name="program_element[]" multiple id="prgm_elem" style="display:none">
									<?php 
										$prgm_elem_info = $program_elem_arr['data']; 
										for($i=0; $i<count($prgm_elem_info); $i++){
									?>
									<option value="<?php echo $prgm_elem_info[$i]['id'];?>"><?php echo $prgm_elem_info[$i]['program_element_name'];?> (<?php echo $prgm_elem_info[$i]['program_element_code'];?>)</option>
									<?php }?>
								</select>	
							</div>
							</div>
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
									<div style="height:20px"></div>
									
									<input type="button" value="Edit Program Element" class="btn btn-blue disp-none" id="edit_prgm_elem"/>
									<div class="text-center loader_img" style="padding:70px 0">
										<img src="img/loading.gif" width="30" style="margin-bottom:10px"/><br/>
										<span class="bold">Loading data..</span>
									</div>
									<form id="add_program_element" class="disp-none">
										<input type="hidden" class="activity_id" name="activity_id" value="<?php echo $activity_id;?>"/>
										<table class="prgm_elems_info table table-striped disp-none text-left" style="margin-top:30px;">
											<tr class="prgm_elem_info">
												<th class="elem_label">Program Element Code</th>
												<th class="elem_label">Program Element Name</th>
												<th class="elem_ip">Percentage</th>
												<th class="elem_close_img"></th>
											</tr>
											<tr class="save_btn_tr disp-none">
												<td colspan="3">&nbsp;</td>
												<td class="text-left"><input id="add_prgm_elems" type="button" value="Save" class="btn btn-green"/></td>
												
											</tr>
										</table>
										
									</form>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right" style="max-width:700px; margin-top:20px">
									<canvas id="myChart"></canvas>
								</div>
							</div>
						</div>
						</div>
						<div class="gray-line"></div>
					</div>
					
				</div>
			</div>
			<!--add new project end-->
     	</div>
	</div>
</div>
<!--main container end-->
<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script src="<?php echo HOST_URL?>js/jquery.multiselect.js"></script>
<script src="<?php echo HOST_URL?>js/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.2/Chart.min.js"></script>
	
<script>
/*show hide program element block*/
$('.add_prgm_elem_btn').click(function(){
	if($('.add_program_element').hasClass('disp-none')){
		$('.add_program_element').removeClass('disp-none');
	}
	else{
		$('.add_program_element').addClass('disp-none');
	}
});


$('#prgm_elem').multiselect({
    columns: 1,
    placeholder: 'Select Program Element',
    search: false
});


/*enable save prgm elem*/
$(document).on('blur','.prgm_elems_info .perc', function(){
	var total_prgm_elems = $('.prgm_elems_info .perc').length; 
	var total_filled_prgm_elems = $('.prgm_elems_info .perc').filter(function() {
		 return $(this).val() && $(this).val()>0;
	}).length;

	if(total_prgm_elems!=total_filled_prgm_elems || $('#add_program_element').find('.invalid_ip').length>0){
		//$('#add_prgm_elems').attr('disabled','disabled');
	}
	else{
		//$('#add_prgm_elems').removeAttr('disabled');
	}
});

$(document).on('keyup','.prgm_elems_info .perc', function(){
	$(this).validate_ip();
});

$('.program_element_chk').click(function(){
	var id = $(this).val();
	var name = $(this).attr('title');
	var code_with_dash = id.split(".").join("-");
	var end = $(this).attr('title').lastIndexOf(')');
	var start = $(this).attr('title').lastIndexOf('(')+1;
	var code = $(this).attr('title').substring(start,end);
	name = name.replace(" ("+code+")","");
	if($(this).prop('checked')){
		/*make all saved data editable*/
		$('.prgm_elem_info').find('.perc_outer').removeClass('disp-none');
		$('.prgm_elem_info').find('.elem_perc_text').addClass('disp-none');
		$('.save_btn_tr,.remove_prgm_elem').removeClass('disp-none');
		$('.ms-options-wrap').css('display','block');
		$('.prgm_elems_info .no_elem_found').remove();
		
		/*display program element table if first checkbox is checked*/
		if($('.program_element_chk:checked').length==1){
			$('.prgm_elems_info').removeClass('disp-none');
		}
		
		var html ='<tr class="prgm_elem_info"><td class="elem_label">'+code+'</td><td class="elem_label">'+name+'<input type="hidden" name="program_element_id[]" class="program_element_code '+code_with_dash+'" value="'+id+'" title="'+code_with_dash+'"/></td><td class="elem_ip"><span class="perc_outer"><input type="text" name="program_element_percentage[]" class="form-control perc only_num" style="display:inline-block;width:90%"/> %<span><span class="elem_perc_text"></span></td><td class="elem_close_img"><img src="<?php echo HOST_URL;?>img/cross.jpg" width="15" class="remove_prgm_elem" /></td></tr>';
		
		
		/*add program element only once*/
		if($('.prgm_elem_info').find('.'+code_with_dash).length==0){
			$(this).addClass(code_with_dash);
			$('.prgm_elem_info:last').after(html);
		}
	}
	else{
		
		/*hide program element table if last checkbox is unchecked*/
		if($('.program_element_chk:checked').length==0){
			$('.prgm_elems_info').addClass('disp-none');
		}
		
		$('.'+code_with_dash).closest('.prgm_elem_info').find('.remove_prgm_elem').trigger('click');
	}
	//$('#add_prgm_elems').attr('disabled','disabled');
});


/*save prgm elem click*/
$('#add_prgm_elems').click(function(){
	var perc_ip = $('.prgm_elems_info').find('.perc');
	var sum =0;
	$(perc_ip).each(function(index, elem){
		if($(elem).val()!=""){
			sum = sum + parseInt($(elem).val());	
		}
	});
	
	var total_prgm_elems = $('.prgm_elems_info .perc').length; 
	var total_filled_prgm_elems = $('.prgm_elems_info .perc').filter(function() {
		 return $(this).val()=="" || $(this).val()==0;
	}).length;
	
	if(sum== 100 && $('#add_program_element').find('.invalid_ip').length==0 && total_filled_prgm_elems==0){
		
		/*save data if true*/
		var form_data = $('#add_program_element').serialize();
		$.ajax({
			url:'<?php echo HOST_URL?>ajaxfiles/manage_activity_program_element.php',
			data:form_data+'&add_program_element=add',
			type:'POST',
			success:function(data){
				var data = JSON.parse(data);
				var activity_id =  $('#add_program_element').find('.activity_id').val();
				
				$('.add_prgm_elem_blk').find('.form-msg').html(data['msg']);
				$('.add_prgm_elem_blk').find('.form-msg').addClass('success');
				
				/*trigger click again to uncheck the checkbox*/
				$('.program_element_chk:checked').trigger('click');
				
				/*reset program element*/
				$('.ms-options-wrap button').text("Select Program Element");
				$('.ms-options ul li').removeClass('selected');
						
				$('.prgm_elem_info .perc_outer').addClass('disp-none');
				$('.prgm_elem_info .elem_perc_text').removeClass('disp-none');	
				
				/*show msg*/		
				$('.add_prgm_elem_blk').find('.form-msg').removeClass('error');
				$('.add_prgm_elem_blk').find('.form-msg').text('');
				
				get_all_activity_program_elem(activity_id);
				window.location= "add_activity_program_element";
				setTimeout(function(){
					$('.add_prgm_elem_blk').find('.form-msg').html("");
				},5000);
				
			}
		});
	}
	else if($('#add_program_element').find('.invalid_ip').length>0){
		$('.add_prgm_elem_blk').find('.form-msg').addClass('error');
		$('.add_prgm_elem_blk').find('.form-msg').text('Some error found...');
		
		setTimeout(function(){
			$('.add_prgm_elem_blk').find('.form-msg').html("");
		},5000);
	}
	else if(total_filled_prgm_elems>0){
		$('.add_prgm_elem_blk').find('.form-msg').addClass('error');
		$('.add_prgm_elem_blk').find('.form-msg').text("Percentage can't be zero.");
		
		setTimeout(function(){
			$('.add_prgm_elem_blk').find('.form-msg').html("");
		},5000);
	}
	else{
		$('.add_prgm_elem_blk').find('.form-msg').addClass('error');
		$('.add_prgm_elem_blk').find('.form-msg').text('Total Percentage must be 100%');
		
		setTimeout(function(){
			$('.add_prgm_elem_blk').find('.form-msg').html("");
		},5000);
	}

});

/*remove program element*/
$('.prgm_elems_info').on('click','.remove_prgm_elem', function(){
	$(this).closest('.prgm_elem_info').remove();
	var elem_unique_class = $(this).closest('.prgm_elem_info').find('.program_element_code').attr('title');
	$('.program_element_chk').filter('#ms-opt-'+elem_unique_class).trigger('click');
	$('.prgm_elem_info').find('.perc_outer').removeClass('disp-none');
	$('.prgm_elem_info').find('.elem_perc_text').addClass('disp-none');
	$('.save_btn_tr,.remove_prgm_elem').removeClass('disp-none');
	$('.ms-options-wrap').css('display','block');
		
	/*hide if last program element in the table is removed*/
	if($('.prgm_elem_info').length<=1){
		$('.prgm_elems_info').addClass('disp-none');
	}
});

/*get all saved activity program element*/
function get_all_activity_program_elem(activity_id){
	$.ajax({
		url:'<?php echo HOST_URL?>ajaxfiles/manage_activity_program_element.php',
		type:'POST',
		data:{activity_id:activity_id,'list_data':'list_data'},
		success:function(data){
			var activity_id =  $('#add_program_element').find('.activity_id').val();
			$('.prgm_elem_info:last').after(data);
			$('.prgm_elems_info').removeClass('disp-none');
			
			$('.prgm_elems_info').find('.program_element_code').each(function(index, elem){
				var title = $(elem).attr('title');
				$('#ms-opt-'+title).trigger('click');
			});
			
			draw_graph(activity_id);
			
			/*make all input box default non-editable*/
			$('.prgm_elem_info').find('.perc_outer').addClass('disp-none');
			$('.prgm_elem_info').find('.elem_perc_text').removeClass('disp-none');
			$('.save_btn_tr,.remove_prgm_elem').addClass('disp-none');
			$('.ms-options-wrap').css('display','none');
			
			if($('.prgm_elem_info').length>1){
				$('#edit_prgm_elem').removeClass('disp-none');
			}
			else{
				$('.ms-options-wrap').css('display','block');
				$('#edit_prgm_elem').addClass('disp-none');
			}
			
			$('.loader_img').addClass('disp-none');
			$('#add_program_element').removeClass('disp-none');
		}
	});
}

/*get all program element on page load*/
get_all_activity_program_elem("<?php echo $activity_id;?>");
$('#edit_prgm_elem').click(function(){
	$('.prgm_elem_info .perc').attr('type','text');
	$('.prgm_elem_info .perc_outer').removeClass('disp-none');
	$('.prgm_elem_info .elem_perc_text').addClass('disp-none');	
	$('.save_btn_tr,.remove_prgm_elem').removeClass('disp-none');
	$('.ms-options-wrap').css('display','block');
});

function draw_graph(activity_id){
	$.ajax({
		url:'<?php echo HOST_URL?>ajaxfiles/manage_activity_program_element.php',
		type:'POST',
		data:{activity_id:activity_id,'graph_data':'graph_data'},
		success:function(data){
			$('.chartjs-hidden-iframe').remove();
			var data = JSON.parse(data);
			var ctx = document.getElementById("myChart");
			
			var myChart = new Chart(ctx, {
				type: 'pie',
				data: {
					labels: data['prgm_elem_label'],
					datasets: [{
						data: data['prgm_elem'],
						backgroundColor: [
							'rgba(204, 40, 40, 0.8)',
							'rgba(30, 179, 13, 0.8)',
							'rgba(210, 188, 19, 0.85)',
							'rgba(255, 87, 34, 0.8)',
							'rgba(153, 102, 255, 0.8)',
							'rgba(255, 159, 64, 0.8)',
							'rgba(255, 102, 87, 0.8)',
							getRandomColor(),
							getRandomColor(),
							getRandomColor(),
						],
						borderColor: [
							'rgba(255,99,132,1)',
							'rgba(54, 162, 235, 1)',
							'rgba(255, 206, 86, 1)',
							'rgba(75, 192, 192, 1)',
							'rgba(153, 102, 255, 1)',
							'rgba(255, 159, 64, 1)',
							'rgba(255, 102, 87, 0.2)'
						],
						borderWidth: 1
					}]
				},
				options: {
					responsive: true
				}
			});
		}
	});
}



function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
</script>
<?php ?>
</body>
</html>
