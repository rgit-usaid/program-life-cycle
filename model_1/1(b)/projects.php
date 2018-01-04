<?php include('config/config.inc.php');

##get all project details from api==========
function _isCurl()
{
    return function_exists('curl_version');
}

###if curl is enable then get all projects==================    
if (_iscurl())
{
    //curl is enabled
    $url = API_HOST_URL_PROJECT."api_demo.php?project";  
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                               
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
  	$project_arr = json_decode($output,true); 
  
  	$url = API_HOST_URL_PROJECT."api_demo.php?stage";  
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
  		header("location:projects.php");	
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
<?php include('includes/resources.php');?> 
<style>
.typeahead__container  ul li::before{content:"";display:none;} .typeahead__item {display:block;width:100%;}.typeahead__item a{display:table-cell; padding:0;}
.typeahead__container  ul li{margin-bottom:0;margin-top:0;}.typeahead__dropdown, .typeahead__list{padding:0;}
</style>
</head>
<body class="page-ui-components">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
	<!--leftpanel start-->
	<?php include('includes/leftpanel.php');?>
    <!--leftpanel end-->
	<!--breadcrumb start-->
	<nav class="breadcrumb">
	  <a class="breadcrumb-item" href="home">Home</a> &raquo;
	  <a class="breadcrumb-item" href="#">Projects</a> &raquo;
	  <span class="breadcrumb-item active">My Project</span>
	</nav>
	<!--breadcrumb end-->
	<!--main container start-->
    <div class="main-content" id="main-content">
      <div class="styleguide-content usa-content">
		  <div style="margin-bottom:20px">
		  	<a id="find_project" class="btn btn-primary" style="text-decoration:none; display:none">Find Projects <i class="fa fa-search"></i></a>
			<form id="form-country_v2" name="form-country_v2" style="position:relative;top:15px; display:inline-block">
				<div class="typeahead__container" style="width:300px; display:none">
					<div class="typeahead__field">
						<span class="typeahead__query">
							<input class="js-typeahead-country_v2" name="country_v2[query]" type="search" placeholder="Search" autocomplete="off" style="width:0">
						</span>
					</div>
				</div>
			</form>
		</div>
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
		<table id="example" class="display table table-bordered table-striped" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>Project Id</th>
				<th>Project Title</th>
				<th class="text-center">Approved Budget</th>
				<th class="text-center">Stage</th>
				<th class="text-center">Project Published</th>
				<th class="text-center">Next Review</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			for($i=0; $i<count($project_arr['data']); $i++)
			{
				$rand_val = rand(0,6);
				$p_budget=rand(10000,99999999); 
			?>
			<tr>
				<td><?php echo $project_arr['data'][$i]['project_id'];?></td>
				<td><?php echo $project_arr['data'][$i]['title'];?></td>
				<td class="text-right">$<?php echo $p_budget;?></td>
				<td class="text-center" style="max-width:300px; position:relative; z-index:9999; "><div style="position:absolute;width:<?php echo $project_arr['data'][$i]['stage_percentage'].'%';?>; background:#a5d3d1; z-index:-1;">&nbsp;</div><?php echo $project_arr['data'][$i]['stage_name'];?></td>
				<td class="text-right"><?php echo $project_arr['data'][$i]['project_published'];?></td>
				<td class="text-right" title="MM/DD/YYYY"><?php echo $project_arr['data'][$i]['next_review_date'];?></td>
				<td class="text-center"> 
					 <form method="post" action="add_new_project.php" style="display:inline;font-size:14px"> 
					 	<input type="hidden" name="project_id" value="<?php echo $project_arr['data'][$i]['project_id']; ?>" style="display:inline">
					 	<input type="submit" name="details" value="Details" style="color:#00a6d2; border:none; background:none;font-weight:normal;padding:0px;margin:0px;display:inline">
					 </form>
					 |
					 <form method="post" style="display:inline;font-size:14px">
					 	<input type="hidden" name="project_id" value="<?php echo $project_arr['data'][$i]['project_id']; ?>" style="display:inline"> 
					 	<input type="submit" name="remove" value="Remove" style="color:#00a6d2; border:none; background:none;font-weight:normal;padding:0px;margin:0px;display:inline;" onClick="return window.confirm('Are you sure you want to remove this project');">
					 </form> 
				</td>
			</tr>
			<?php $pid=$pid+1; 
			}?>
		</tbody>
		</table>
	</div>
		</div>
		<!--project table container end-->
      </div>
    </div>
	<!--main container end-->
	<!--<script>
	$("#find_project").click(function(){
		$('#form-country_v2').find('input[type="search"]').css({width:'0%'});
		$('#form-country_v2').find('.typeahead__container').css({display:'block'}).find('input[type="search"]').animate({width:'100%'});
	});
	</script>
	<script src="<?php echo HOST_URL;?>js/jquery.typeahead.min.js"></script>
	<script>
		$.typeahead({
		input: '.js-typeahead-country_v2',
		minLength: 2,
		maxItem: 10,
		order: "asc",
		href: "https://en.wikipedia.org/?title={{display}}",
		template: "{{display}} <small style='color:#999;'>{{details}}</small>",
		source: {
			data: ["Education", "Safety and Justice","Primary Health Care"," Food and Nutrition Technical Assistance"," Global Alliance for Improved Nutrition"],
		},
		callback: {
			onNavigateAfter: function (node, lis, a, item, query, event) {
				if (~[38,40].indexOf(event.keyCode)) {
					var resultList = node.closest("form").find("ul.typeahead__list"),
						activeLi = lis.filter("li.active"),
						offsetTop = activeLi[0] && activeLi[0].offsetTop - (resultList.height() / 2) || 0;
	 
					resultList.scrollTop(offsetTop);
				}
	 
			},
			onClickAfter: function (node, a, item, event) {
	 
				event.preventDefault();
	 
				var r = confirm("You will be redirected to:\n" + item.href + "\n\nContinue?");
				if (r == true) {
					window.location = item.href;
				}
	 
				$('#result-container').text('');
	 
			},
			onResult: function (node, query, result, resultCount) {
				if (query === "") return;
	 
				var text = "";
				if (result.length > 0 && result.length < resultCount) {
					text = "Showing <strong>" + result.length + "</strong> of <strong>" + resultCount + '</strong> elements matching "' + query + '"';
				} else if (result.length > 0) {
					text = 'Showing <strong>' + result.length + '</strong> elements matching "' + query + '"';
				} else {
					text = 'No results matching "' + query + '"';
				}
				$('#result-container').html(text);
	 
			},
			onMouseEnter: function (node, a, item, event) {
	 
				if (item.group === "country") {
					$(a).append('<span class="flag-chart flag-' + item.display.replace(' ', '-').toLowerCase() + '"></span>')
				}
	 
			},
			onMouseLeave: function (node, a, item, event) {
	 
				$(a).find('.flag-chart').remove();
	 
			}
		}
	});
    </script>-->
	<script>
		$(".tbl-up").click(function(){
			$(this).closest(".tbl-caption").next(".table-container").slideUp("fast");
		});
		$(".tbl-down").click(function(){
			$(this).closest(".tbl-caption").next(".table-container").slideDown("fast");
		});
	</script>
	<?php include('includes/footer.php');?>
	<script src="<?php echo HOST_URL;?>js/jquery.dataTables.min.js"></script>
	<script src="<?php echo HOST_URL;?>js/dataTables.responsive.js"></script>
	
	<script type="text/javascript" language="javascript" class="init">
	$(document).ready(function() {
		$('#example').DataTable({
			 responsive: true
		});
	});
	</script>
</body>
</html>
