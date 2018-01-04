<?php
include('config/config.inc.php');
include('include/function.inc.php');
## get all operating unit id==========
$url = API_HOST_URL."get_all_operating_unit.php";
$operating_unit_arr = requestByCURL($url);

## get all vendor id ==========
$url = GS_API_HOST_URL."get_all_vendor.php";
$vendor_unit_arr = requestByCURL($url);

### Query Report ===========
$error = '';
$operating_unit_id = '';
if(isset($_REQUEST['query_report']))
{
	$operating_unit_id = trim($_REQUEST['operating_unit_id']); // operating id as bureau id
	 
	$url = API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id."";
	$ou_arr = requestByCURL($url);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title><?php echo TITLE;?></title>
	<!-- <link rel="shortcut icon" type="image/x-icon" href="images/hr-logo.gif" />	 -->
	
    <!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- CSS
  ================================================== -->
 	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet"> 
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css"> 
	<link href="css/font-awesome.min.css" type="text/css" rel="stylesheet"> 
	<link href="css/style.css" type="text/css" rel="stylesheet">
	<link href="css/jquery-ui.css" type="text/css" rel="stylesheet">
	<style>
	.table1 {
    border: none;
    }
	.table1> tbody > tr:nth-of-type(odd){
  background-color:#f9f9f9 !important;
}
	.table1>tbody>tr>td{
	border-top:none !important;
	}
	.divide{
	background:#ccccff;
	padding-bottom:6px;
	padding-top:6px;
	padding-left:8px;}
	
	.table1 h4 {
    margin-top:12px;
    margin-bottom:13px;
	}
.table{
	    margin-bottom:3px !important;
		}
		
  		.disp-none{
  			display: none;
  		}
  	</style>  
  	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script>	
	$(document).ready(function() {
		$("#datepicker").datepicker({
			changeMonth: true,
			changeYear: true
		});
		$('#btn').click(function() {
			$("#datepicker").focus();
		});
	
	}); 

// JS for Date Picker
$(document).ready(function() {
	$('.datepicker').datepicker({
		startDate: '-3d'                
	});
});	
	</script>
    
</head>
<body>
	<!-- Header -->
	
	<?php include 'header.html'; ?>

	<!---  / Header - --> 
	<!-- Breadcrumbs --> 
	<ol class="breadcrumb">
  		<li><a href="#">Site Map</a></li>
  		<li><a href="index.php">Query Reports</a></li>
  	</ol> 
	<!-- / Breadcrumbs  --> 
	<!-- Pop Up Div Menu Nav -->

	<div class="menu-nav">
		<ul class="nav navbar-nav navbar">
			<li>Query Reports</li>
		</ul>
	</div> 
	<!-- Main Manage Accounts Payable Goes Here --> 
	<div class="container-fluid manage-form">
		<?php
		if($error!='')
		{
			echo '<div style="text-align:center;color:red;">'.$error.'</div>';
		}
		?>
		<div class="form-title" style="width:40px">
			<i class="fa" aria-hidden="true"></i><i class="fa" aria-hidden="true"></i>
		</div>
		<!----Section1------>
		<form class="form-horizontal" role="form" method="post" action=""> 
			<input type="hidden" name="query_report" value="1">
			<div class="new-form-content" >
				<div class="manage-funds"> 
					<div class="form-group">
						<label class="col-md-4" for="Operating Unit">Operating Unit:</label>
						<div class="col-sm-8">
							<select class="form-control operating_unit_id" name="operating_unit_id" onchange="this.form.submit();">
								<option value="" >Select</option>
								<?php
								for($i=0; $i<count($operating_unit_arr['data']); $i++)
								{	
								?>
									<option value="<?php echo $operating_unit_arr['data'][$i]['operating_unit_id'];?>" <?php if($_REQUEST['operating_unit_id']==$operating_unit_arr['data'][$i]['operating_unit_id'])echo 'selected="selected"'; ?> ><?php echo $operating_unit_arr['data'][$i]['operating_unit_description'].' ('.$operating_unit_arr['data'][$i]['operating_unit_abbreviation'].')';?>
									</option>
								<?php
								}
								?>	 
							</select>
						</div>
					</div> 
					<div class="form-group">
						<div class="col-md-offset-4 col-md-8">
							<a href="" class="btn btn-default" style="margin-right:20px">Cancel</a>
							 
						</div>
					</div> 
				</div>
			</div>
		</form> 
	<!-- Manage Accounts Payable Ends Here --> 
	<!-- Data Display Here --> 
	<?php
	if(isset($ou_arr['data']) and trim($ou_arr['data']['type'])=='Bureau')
	{ 
		$url = API_HOST_URL."get_bureau_fund_unique.php?operating_unit_id=".$operating_unit_id."";
		$unique_fund_arr = requestByCURL($url);
	?>	
		<h2>Bureau Report For <?php echo '('.$ou_arr['data']['operating_unit_description'].')';?></h2> 
		<div class="data-display container-fluid  table-responsive"> 
			<table id="manage-table" class="table table-striped table1" cellspacing="0" width="100%"> 
			<?php 
	        if(count($unique_fund_arr['data'])!=NULL)
	        {
	        	for($i=0; $i<count($unique_fund_arr['data']); $i++)
			    {
		        	$ledger_type_id = $operating_unit_id;
		        	$fund_beginning_fiscal_year = $unique_fund_arr['data'][$i]['fund_beginning_fiscal_year'];
		        	$fund_ending_fiscal_year = $unique_fund_arr['data'][$i]['fund_ending_fiscal_year'];
		        	$fund_id = $unique_fund_arr['data'][$i]['fund_id'];
		        	$pe_id = $unique_fund_arr['data'][$i]['program_element_id'];
		        	 
		        	### get closing balance
		        	$closing_balance_arr = getClosingBalance($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$fund_id,'Operating Unit',$pe_id);

					### get un allowed amount from OU =========== 
					$alloted_balance_arr = getTotalAllotedToOU($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$fund_id,'Operating Unit',$pe_id);

					### get un allowed amount from OU ===========
					$url = API_HOST_URL."get_un_allowed_amount_count_from_ou.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id;
					$un_alloted_arr = requestByCURL($url);
					$total_un_allowed_amount = $un_alloted_arr['data'][0]['total_un_allowed_amount'];

					### get all operating which is got fund from this strip with bureau
					$url = API_HOST_URL."get_total_ou_alloted_fund_to_single_from_bureau.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."";
					$alloted_transaction_arr = requestByCURL($url);

					### total recieved fund========
					$total_received_amount = $all_bureau_fund_arr['data'][$count_pe]['total_amount'] - $total_un_allowed_amount;

			?>	
				<tr>
					<td colspan="2">
						<div class="">
						  	<div class="divide" >
								<div class="row" >
							        <div class="col-sm-4 "><h4><b> <?php echo $unique_fund_arr['data'][$i]['gp_year'];?></b></h4></div>
							        <div class="col-sm-4 text-center"><h4> Received: <?php echo '$'.number_format(($alloted_balance_arr['total_debit_amount'] - $total_un_allowed_amount) + $closing_balance_arr['closing_balance']);?></h4></div>
									<div class="col-sm-4 text-center"><h4> Availble: <?php if($closing_balance_arr['closing_balance']!='')echo '$'.number_format($closing_balance_arr['closing_balance']);else echo 'No Available'; ?></h4></div>
							 	</div>
						 	</div>
						 	<?php
						 	if($alloted_transaction_arr['data']!=NULL)
						 	{
						 	?> 
								<table class="table table-striped table-bordered clearfix table1" cellspacing= "0" width="100%">
									<thead>
									   <tr>
											<th>Operating Unit</th>
											<th width="30%">Alloted</th>	
									   </tr>
									</thead>
									<tbody>
									<?php 
									for($j=0; $j<count($alloted_transaction_arr['data']); $j++)
									{
										### get total reverse from this ou===
										$url = API_HOST_URL."get_total_reverse_fund_from_ou_to_single_ou.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type_id_dr=".$alloted_transaction_arr['data'][$j]['ledger_type_id']."";
										$reverse_transaction_arr = requestByCURL($url); 
									 
									?>	
										<tr>
											<td><?php echo $alloted_transaction_arr['data'][$j]['operating_unit_description'];?></td>
											<td><?php echo '$'.number_format($alloted_transaction_arr['data'][$j]['total_amount']-$reverse_transaction_arr['data'][0]['total_amount']);?></td>
										</tr>
									<?php
									}
									?> 
										<tr>
											<td colspan="" style="text-align:right"><b>Total Alloted </b></td>
											<td colspan="" style="text-align:left"><b> <?php echo '$'.number_format($alloted_balance_arr['total_debit_amount'] - $total_un_allowed_amount);?></b></td>
										</tr>
									</tbody>
								</table>
							<?php
							}else echo "Not alloted to any Operating Unit";
							?>
						</div> 
					</td> 
	            </tr>
	        <?php
	        	}
	        }else if($operating_unit_id!='')echo '<tr><td colspan="2">No Record Found</td></tr>'; else echo '<tr><td colspan="2">Please Select Operating Unit First</td></tr>';	
	        ?> 
		   </table>
		</div>
	<?php
	}
	if(isset($ou_arr['data']) and trim($ou_arr['data']['type'])!='Bureau')
	{ 
		## API for get all list of group data ==========
		$url = API_HOST_URL."get_ou_fund_unique.php?operating_unit_id=".$operating_unit_id."";
		$unique_fund_arr = requestByCURL($url); 
		?>
		<h2>Operating Unit Report For (<?php echo $ou_arr['data']['operating_unit_description']?>)</h2> 
		<div class="data-display container-fluid  table-responsive"> 
			<table id="manage-table" class="table table-striped table1" cellspacing="0" width="100%"> 
			<?php 
	        if(count($unique_fund_arr['data'])!=NULL)
	        {
	        	for($i=0; $i<count($unique_fund_arr['data']); $i++)
			    {
		        	$ledger_type_id = $operating_unit_id;
		        	$fund_beginning_fiscal_year = $unique_fund_arr['data'][$i]['fund_beginning_fiscal_year'];
		        	$fund_ending_fiscal_year = $unique_fund_arr['data'][$i]['fund_ending_fiscal_year'];
		        	$fund_id = $unique_fund_arr['data'][$i]['fund_id'];
		        	$pe_id = $unique_fund_arr['data'][$i]['program_element_id'];
 
		        	### get closing balance
		        	$closing_balance_arr = getClosingBalance($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$fund_id,'Operating Unit',$pe_id); 
					
					### get total committed fund================== 
					$commited_balance_arr = getTotalCommitedTOOU($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$fund_id,'Operating Unit',$pe_id);
					$total_commited_amount = $commited_balance_arr['total_debit_amount'];

					### get total decommitted fund ==========
					$url = API_HOST_URL."get_all_reverse_de_commit_fund_from_commit_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."";
					$de_commited_balance_arr = requestByCURL($url);
					$total_de_commited_amount = $de_commited_balance_arr['data'][0]['total_de_commited_amount'];

					$net_committed_amount = $total_commited_amount - $total_de_commited_amount;
					
					### get alloted balance to OU to Obligate =============
					$obligated_balance_arr = getTotalObligateTOOU($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$fund_id,'Operating Unit',$pe_id); 
				    $total_obligated_amount = $obligated_balance_arr['total_debit_amount'];

				    ### get total reverse de-obligated  fund from obligate screen==========
					$url = API_HOST_URL."get_all_reverse_de_obligate_fund_from_obligate_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."";
					$de_obligated_balance_arr = requestByCURL($url);
					$total_de_obligated_amount = $de_obligated_balance_arr['data'][0]['total_de_obligated_amount'];

					### actual spent fund for this operating unit==========
					$url = API_HOST_URL."get_total_paid_amount_by_fund_strip_ou.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."";
					$paid_arr = requestByCURL($url);

					$total_net_obligated =  $total_obligated_amount - $total_de_obligated_amount;
					 
			?>	
				<tr>
					<td colspan="2">
						<div class="">
						  	<div class="divide" >
								<div class="row" >
							        <div class="col-sm-6 "><h4><b> <?php echo $unique_fund_arr['data'][$i]['gp_year'];?></b></h4></div>
									<div class="col-sm-6 text-center"><h4> Received: <?php echo '$'.number_format($net_committed_amount+$total_net_obligated+$closing_balance_arr['closing_balance']); ?></h4></div> 
							 	</div>
						 	</div> 
							<table class="table table-striped table-bordered clearfix table1" cellspacing= "0" width="100%">
								<thead>
								   <tr> 
										<th>Committed</th>
										<th>Obligated</th>			
										<th>Spent</th>
										<th>Available</th>
								   </tr>
								</thead>
								<tbody> 
									<tr> 
										<td><?php echo '$'.number_format($net_committed_amount);?></td>
										<td><?php echo '$'.number_format($total_net_obligated);?></td>
										<td><?php echo '$'.number_format($paid_arr['data']['total_paid']);?></td>
										<td><?php if($closing_balance_arr['closing_balance']!='')echo '$'.number_format($closing_balance_arr['closing_balance']);else echo 'No Available'; ?></td>
									</tr> 
								</tbody>
							</table> 
						</div> 
					</td> 
	            </tr>
	        <?php
	        	}
	        }else if($operating_unit_id!='')echo '<tr><td colspan="2">No Record Found</td></tr>'; else echo '<tr><td colspan="2">Please Select Operating Unit First</td></tr>';	
	        ?> 
		   </table>
		</div>
	<?php	
	}
	?>
	<br> 
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/bootstrap.min.js"></script>
<!--	<script src="js/bootstrap-datepicker.js"></script> -->
	<script src="js/main.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript"> 
	 
	function showChild(elem){
		//alert($(elem).closest('.child-table').length);
		$(elem).closest('.parent-tbl').next('.child-table').toggleClass('disp-none');
	}
	
	function getOperatingUnit()
	{
		$('.show_project').css('display','none');
		$('.aj_loader_project').css('display','');
		var operating_unit_id = $('.operating_unit_id').val();	
		 
		$.ajax({
		  type: "POST",
		  url: "ajax_files/get_project_drop.php",
		  data: {operating_unit_id:operating_unit_id},
		  success: function(data){
		  		$('.aj_loader_project').css('display','none');
		  		$('.show_project').css('display','block');	
		    	if(data!='')$('.show_project').html(data);
		  	}
		}); 
	}
	
	</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
	
</body>
</html>