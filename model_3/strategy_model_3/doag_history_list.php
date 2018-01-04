<?php
include('config/config.inc.php');
include('include/function.inc.php');
//## get Detail operating unit ===========
if($_SESSION['operating_unit_id']!='')
{
	$operating_unit_id = $_SESSION['operating_unit_id'];
	$url = PHOENIX_API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;
	$operating_unit_arr = requestByCURL($url); 
}
else
{
	header('location:index.php');
}

if($_GET['history_id']!='')
{
	$objective_agreement_history_id = $_GET['history_id'];
	$_SESSION['objective_agreement_history_id']=$objective_agreement_history_id;
}
	$objective_agreement_history_id = $_SESSION['objective_agreement_history_id'];

	$select_archives_objective_agreement="select * from usaid_archive_objective_agreement where objective_agreement_id = '".$objective_agreement_history_id."' order by archived_on desc";
	$result_archives_objective_agreement = $mysqli->query($select_archives_objective_agreement);
	$fetch_archives_objective_agreement = $result_archives_objective_agreement->fetch_array();
	$total_num_row = $result_archives_objective_agreement->num_rows; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>USAID</title>
	<!-- Bootstrap -->
	<link rel="shortcut icon" type="image/ico" href="images/favicon.ico"/>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">	
	<link href="css/uswds.min.css" rel="stylesheet">
	<style>
	.parent-tbl h5{margin:1px !important;}
	.parent-iframe { height:5px;}
	.btn-default{padding:1px 2px !important;}
	.sub-table{background:#D3D3D3 !important;} 
	.sub-table{ background: #A9A9A9 !important;} 
	.manage-child-table{margin:0 !important;}
	</style>
</head>
<body>
	<!-- Header Include Here -->
	<?php include 'include/header.php'; ?>
	
	<!-- Header Details -->
	<div class="container-fluid">
		<div class="header-detail">
			<div class="head-title">
				<div class="head">Operating Unit <span class="pull-right">(<a href="go_to_doag.php">Back </a>)</span></div>
			</div>
			<div class="row clear details">
				<div class="col-md-12"><a href="manage.php" class="remove-line"><?php echo $operating_unit_arr['data']['operating_unit_description']; ?></a> >> <a href="go_to_doag.php" class="remove-line"> DOAGs and SOAGs</a> >> History >> <?php echo ucfirst($fetch_archives_objective_agreement['name']);?></div>
			</div>
		</div>
	</div>
	<!-- Pop Up Dialog Box  -->
	<div class="container-fluid" id="show_list" style="display:block">
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<td colspan="6" align="center" style="font-size:24px; font-weight:bold;"><?php echo ucfirst($fetch_archives_objective_agreement['name']);  echo ' ('.$fetch_archives_objective_agreement['objective_agreement_type'].')';?></td>
				</tr>
				<tr>
					<th class="text-center">Archived On</th>
					<th class="text-center">Type</th>
					<th class="text-center">Description</th>
					<th class="text-center">Estimated Funding</th>
					<th class="text-center">Date Approved</th>
					<th class="text-center">Action</th>   
				</tr>
			</thead>
			<tbody>
			<?php
				$show_archives_objective_agreement = $mysqli->query($select_archives_objective_agreement);
				while($list_archives_objective_agreement = $show_archives_objective_agreement->fetch_array())
				{
			 ?> 
				<tr class="parent-tbl parent-iframe" >
					<td><?php echo date( "m/d/Y h:i", strtotime($list_archives_objective_agreement['archived_on'])); ?></td>
					<td><?php echo $list_archives_objective_agreement['objective_agreement_type']; echo $list_archives_objective_agreement['id']; echo $objective_agreement_history_id; ?></td>
					<td><?php echo $list_archives_objective_agreement['description'];?></td>
					<td><?php echo "$".number_format($list_archives_objective_agreement['funding_estimate']); ?></td>
					<td ><?php echo dateFormatView($list_archives_objective_agreement['approved_date']); ?></td> 
					<td><button onClick="showChild(this)" class="btn-name">Show Doc</button><button onClick="showObjChild(this)" class="btn-name">Show DO</button></td>      
				</tr>
				<tr class="child-table" style="display:none;">
				<td colspan="8">
						<table id="manage-child-table" class="table table-striped"  cellspacing="0" width="100%" border="1">
							<thead>
								<tr>										
									<th class="text-center">Document Name</th>
									<th class="text-center">Tags</th>
								</tr>
							</thead>
							<tbody>
						<?php $select_archive_object_agreement_document="select document_name, document_tags from usaid_archive_objective_agreement_document where objective_agreement_id='".$list_archives_objective_agreement['id']."'"; 
						$result_archive_document = $mysqli->query($select_archive_object_agreement_document);
						$total_document = $result_archive_document->num_rows;
						if($total_document>0) {
							while($fetch_archive_document = $result_archive_document->fetch_array())
							 { ?>
									<tr>										
										<td class="text-center"><?php echo $fetch_archive_document['document_name']; ?></td>
										<td class="text-center"><?php echo $fetch_archive_document['document_tags']; ?></td>
									</tr>
					<?php 	}
					 }  else { ?>	
					 				<tr>										
										<td class="text-center" colspan="2">No Document</td>
									</tr>	
							<?php } ?>	
							</tbody>								
						</table>
				</td>
			</tr>
				<tr class="child-table2" style="display:none;">
					<td colspan="8">
						<table id="manage-child-table" class="table table-striped"  cellspacing="0" width="100%" border="1">
							<thead>
								<tr>										
									<th class="text-center">Developement Objectives</th>
								</tr>
							</thead>
							<tbody>
						<?php ## fetch usaid_archive_objective_agreement_relation =============== 
						 $select_archive_DO="select * from usaid_archive_objective_agreement_relation where objective_agreement_id='".$list_archives_objective_agreement['id']."'"; 
						 $result_archive_DO = $mysqli->query($select_archive_DO);
						 $total_document = $result_archive_DO->num_rows;
						if($total_document>0) {
							while($fetch_archive_document = $result_archive_DO->fetch_array())
							 { 
							 	$select_DO="select * from usaid_development_objective where id='".$fetch_archive_document['relation_id']."'";
								$result_DO = $mysqli->query($select_DO);
								$fetch_development_objective = $result_DO->fetch_array()
							 ?>
									<tr>										
										<td class="text-center"><?php echo $fetch_development_objective['objective_description']; ?></td>
									</tr>
					<?php 	}
						 }  else { echo "<tr><td> No Dovelopment Objective </td> </tr>"; }?>		
								
							</tbody>								
						</table>
					</td>
				</tr>
		   <?php } ?>
				
			</tbody>
		</table>
	</div> 
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/uswds.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
	
	function showChild(elem)
	{
		if($(elem).closest('.parent-tbl').nextUntil('.parent-tbl','.child-table').css('display')=='none')
		{
			$(elem).closest('.parent-tbl').nextUntil('.parent-tbl','.child-table').css('display','table-row');
			$(elem).html("Hide Doc");
		}
		else
		{
			$(elem).closest('.parent-tbl').nextUntil('.parent-tbl','.child-table').css('display','none');
			$(elem).html("Show Doc");
		}
	}
	
	function showObjChild(elem)
	{
		if($(elem).closest('.parent-tbl').nextUntil('.parent-tbl','.child-table2').css('display')=='none')
		{
			$(elem).closest('.parent-tbl').nextUntil('.parent-tbl','.child-table2').css('display','table-row');
			$(elem).html("Hide DO");
		}
		else
		{
			$(elem).closest('.parent-tbl').nextUntil('.parent-tbl','.child-table2').css('display','none');
			$(elem).html("Show DO");
		}
	}
	

	</script>
</body>
</html>