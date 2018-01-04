<?php
include('config/config.inc.php');
include('include/function.inc.php');

## get all funded operating unit ==========
$url = API_HOST_URL."get_all_funded_operating_unit.php";
$operating_unit_arr = requestByCURL($url);

## API for get all list of group data ==========
$url = API_HOST_URL."get_all_commit_fund_group.php"; //api for get group data for list
$all_comiit_fund_gp_arr = requestByCURL($url);

### Commit fund ===========
$error = '';
if(isset($_REQUEST['commit_fund']))
{
	$fund_id = '';
	$operating_unit_id = trim($_REQUEST['operating_unit_id']); //DR
	$commit_type = trim($_REQUEST['commit_type']); //from drop down
	$commit_in_id = trim($_REQUEST['commit_in_id']); //CR
	$beginning_fiscal_year = '';
	$ending_fiscal_year = '';
	$full_strip = trim($_REQUEST['ou_year']);
	$full_strip_arr = explode('>>',$full_strip);
	$strip_narration = $full_strip_arr[0];
	$real_amount = $full_strip_arr[1]; // get real amount to validate alloted amount

	$ou_year = str_replace("-"," ",trim($strip_narration));
	$current_fiscal_year = trim($_REQUEST['current_fiscal_year']); 

	$fund_year_arr = explode(' ', $ou_year);
	$fund_amount = trim($_REQUEST['amount']);
	$fund_amount = getNumericAmount($fund_amount); // use this function if amount has $ sign or comma
	$fund_code = $fund_year_arr[0];
	
	## fund id by fund code like DA==================
	$url = API_HOST_URL."get_fund.php?fund_id=".$fund_code.""; // api for get id by fund code like DA (fund_id)
	$fund_arr = requestByCURL($url);
	$fund_id = $fund_arr['data']['id'];
	$cond = " fund_id = '".$fund_id."', ";
	 if(in_array('FY',$fund_year_arr))
	 {
	 	$beginning_fiscal_year = $fund_year_arr[2];
	 	$ending_fiscal_year = $fund_year_arr[3];
	 	$pe_id = $fund_year_arr[4]; // program element code 
	 	$cond .= " fund_beginning_fiscal_year = '".$beginning_fiscal_year."', fund_ending_fiscal_year='".$ending_fiscal_year."',program_element_id='".$pe_id."',";
	 }
	 else
	 {
	 	$pe_id = $fund_year_arr[1];
	 	$cond .= " program_element_id='".$pe_id."',";	
	 }

	### PHP validation ======
	if($operating_unit_id=='')
	{
		$error= "Please select OU";
	}
	elseif($commit_type=='')
	{
		$error= "Please select commit in ";
	}
	elseif($commit_in_id=='')
	{
		$error= "Please select ".$commit_type;
	}
	elseif($fund_amount=='')
	{
		$error= "Please enter amount ";
	}
	elseif($fund_amount>$real_amount)
	{
		$error= "You entered amount more than current amount";
	}
	else
	{
		$insert_data = "insert into usaid_fund_transaction set
							transaction_type = 'Allocate',
							narration = '".$strip_narration."',
							".$cond."
							fund_amount = '".$fund_amount."'";
		$result_fund_transaction = $mysqli->query($insert_data);
		$transaction_id = $mysqli->insert_id;
		if($transaction_id>0)
		{
			### get oppening and closing balance of cr entry like project id and activity etc=====
		 	$opn_clg_balance_arr = getClosingBalance($commit_in_id,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,$commit_type,$pe_id,'Commit');
		 	$opening_balance = $opn_clg_balance_arr['opening_balance'];
		 	$closing_balance = $opn_clg_balance_arr['closing_balance'];
		 	$opening_balance =  $closing_balance;
		 	$closing_balance = ($closing_balance + $fund_amount);
		 	$insert_fund_transaction_detail_cr = "insert into usaid_fund_transaction_detail set
		 				transaction_id = '".$transaction_id."', 
		 				credit_amount = '".$fund_amount."',
		 				opening_balance = '".$opening_balance."',
		 				closing_balance = '".$closing_balance."',
		 				ledger_type = '".$commit_type."',
						transaction_year = '".$current_fiscal_year."',						
		 				fund_status = 'Commit',
		 				ledger_type_id = '".$commit_in_id."'";
		 	$result_fund_transaction_detail_cr = $mysqli->query($insert_fund_transaction_detail_cr);

		 	###insert query for debit amount into transaction table =======================
		 	$opn_clg_balance_arr = getClosingBalance($operating_unit_id,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,'Operating Unit',$pe_id);
		 	$opening_balance = $opn_clg_balance_arr['opening_balance'];
		 	$closing_balance = $opn_clg_balance_arr['closing_balance'];
	 
		 	$opening_balance = $closing_balance;
		 	$closing_balance = ($closing_balance - $fund_amount);

		 	$insert_fund_transaction_detail_de = "insert into usaid_fund_transaction_detail set
		 				transaction_id = '".$transaction_id."', 
		 				debit_amount = '".$fund_amount."',
		 				opening_balance = '".$opening_balance."',
		 				closing_balance = '".$closing_balance."',
		 				ledger_type = 'Operating Unit',
						transaction_year = '".$current_fiscal_year."',
		 				ledger_type_id = '".$operating_unit_id."'";
		 	$result_fund_transaction_detail_de = $mysqli->query($insert_fund_transaction_detail_de);
		 	if($result_fund_transaction and $result_fund_transaction_detail_cr and  $result_fund_transaction_detail_de)
		 	{
		 		header("location:commit_funds.php");
		 	}
		 }
	}
}
####reverse fund to allot to bureu==============
if(isset($_REQUEST['reverse_to_commit']))
{
	$debit_from_id = trim($_REQUEST['debit_from_id']);//commit
	$credit_in_id = trim($_REQUEST['credit_in_id']);//operating
	$fund_id = trim($_REQUEST['fund_id']);
	$pe_id = trim($_REQUEST['pe_id']);
	$beginning_fiscal_year = trim($_REQUEST['fund_beginning_fiscal_year']);
	$ending_fiscal_year = trim($_REQUEST['fund_ending_fiscal_year']);
	$narration = trim($_REQUEST['narration']);
	$fund_amount = trim($_REQUEST['reverse_amount']);
	$fund_amount = getNumericAmount($fund_amount); // use this function if amount has $ sign or comma
	$ledger_type = trim($_REQUEST['ledger_type']);
	if($fund_amount>0)
	{
		$cond = '';
		if($beginning_fiscal_year!='' and $ending_fiscal_year!='')
		{
		 	$cond = " fund_beginning_fiscal_year = '".$beginning_fiscal_year."', fund_ending_fiscal_year='".$ending_fiscal_year."',";
		}
		 
	 	$insert_to_commit = "insert into usaid_fund_transaction set
							transaction_type = 'Reverse',
							narration = '".$narration."',
							fund_id = '".$fund_id."',
							program_element_id = '".$pe_id."',
							".$cond."
							fund_amount = '".$fund_amount."'";
		$result_fund_transaction = $mysqli->query($insert_to_commit);
		$transaction_id = $mysqli->insert_id;
		
		###insert query for credit amount into transaction detail table=======================
		$opn_clg_balance_arr = getClosingBalance($credit_in_id,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,'Operating Unit',$pe_id);
		$opening_balance = $opn_clg_balance_arr['opening_balance'];
	 	$closing_balance = $opn_clg_balance_arr['closing_balance'];
	 	$opening_balance =  $closing_balance;
	 	$closing_balance = ($closing_balance + $fund_amount);
	 	$insert_fund_transaction_detail_cr = "insert into usaid_fund_transaction_detail set
	 				transaction_id = '".$transaction_id."', 
	 				credit_amount = '".$fund_amount."',
	 				opening_balance = '".$opening_balance."',
	 				closing_balance = '".$closing_balance."',
	 				ledger_type = 'Operating Unit',
	 				ledger_type_id = '".$credit_in_id."'";		
	 	$result_fund_transaction_detail_cr = $mysqli->query($insert_fund_transaction_detail_cr);

	 	###insert query for debit amount into transaction table======================= 	
		$opn_clg_balance_arr = getClosingBalance($debit_from_id,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,$ledger_type,$pe_id,'Commit');
		$opening_balance = $opn_clg_balance_arr['opening_balance'];
	 	$closing_balance = $opn_clg_balance_arr['closing_balance'];

	 	$opening_balance = $closing_balance;
	 	$closing_balance = ($closing_balance - $fund_amount);

	 	$insert_fund_transaction_detail_de = "insert into usaid_fund_transaction_detail set
	 				transaction_id = '".$transaction_id."', 
	 				debit_amount = '".$fund_amount."',
	 				opening_balance = '".$opening_balance."',
	 				closing_balance = '".$closing_balance."',
	 				ledger_type = '".$ledger_type."',
					fund_status = 'Commit',
	 				ledger_type_id = '".$debit_from_id."'";
					
	 	$result_fund_transaction_detail_de = $mysqli->query($insert_fund_transaction_detail_de);
	 	if($result_fund_transaction and $result_fund_transaction_detail_cr and  $result_fund_transaction_detail_de)
	 	{
	 		header("location:commit_funds.php");
	 	}
	 } 
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
  		.disp-none{
  			display: none;
  		}
  	</style>    
</head>
<body>
	<!-- Header -->
	
	<?php include 'header.html'; ?>

	<!---  / Header - --> 
	<!-- Breadcrumbs --> 
	<ol class="breadcrumb">
  		<li><a href="#">Site Map</a></li>
  		<li><a href="index.php">Phoenix</a></li>
  		<li class="active">Commit Funds</li>
	</ol> 
	<!-- / Breadcrumbs  --> 
	<!-- Pop Up Div Menu Nav -->

	<div class="menu-nav">
		<ul class="nav navbar-nav navbar">
			<li>Commit Fund</li>
			<!-- <li><a href="fund_time_period.php" class="btn btn-default active">Fund Time Period</a></li>
			<li><a href="allow_to_bureau.php" class="btn btn-default active">Allow to Bureau</a></li> -->
		</ul>
	</div> 
	<!-- Main Manage Fund Content Goes Here --> 
	<div class="container-fluid manage-form">
		<div class="form-title" style="width:40px">
			<i class="fa fa-minus" aria-hidden="true"></i><i class="fa fa-plus" aria-hidden="true"></i>
		</div>
		<div class="new-form-content">
			<?php
			if($error!='')
			{
				echo '<div style="text-align:center;color:red;">'.$error.'</div>';
			}
			?>
			<div class="manage-funds"> 
				<form class="form-horizontal" role="form" method="post" action=""> 
					<div class="form-group">
						<label class="col-md-4" for="Operating Unit">Operating Unit:</label>
						<div class="col-sm-8">
							<select class="form-control operating_unit_id" name="operating_unit_id" onchange="getOUFundDrop();">
								<option value="">Select</option>
								<?php
								for($i=0; $i<count($operating_unit_arr['data']); $i++)
								{	
								?>
									<option value="<?php echo $operating_unit_arr['data'][$i]['operating_unit_id'];?>"><?php echo $operating_unit_arr['data'][$i]['operating_unit_description'].' ('.$operating_unit_arr['data'][$i]['operating_unit_abbreviation'].')';?>
									</option>
								<?php
								}
								?>	 
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Fund Name">Funds:</label>
						<div class="col-md-8 show_ou_fund">
							<select class="form-control ou_year" name="ou_year" onchange="getcurrentFiscalyear();">
								<option>Select</option> 
							</select>
						</div>
						<div class="col-sm-8 aj_loader" style="display:none;">
							 <img src="images/loading.gif">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4" for="Commit In">Commit In:</label>
						<div class="col-md-8">
							<select class="form-control commit_type" name="commit_type" onchange="getInputFieldByCommitType();">
								<option value="">Select</option>
								<option value="DOAG">DOAG</option>
								<option value="Award CLIN">Award/CLIN</option>    
							</select>
						</div>
					</div>
					<div class="show_input_field">
						
					</div>
					<div class="form-group aj_loader_type" style="display:none;">
						<label class="col-md-4" for=""></label>
						<div class="col-sm-8">
							 <img src="images/loading.gif">
						</div> 
					</div>	
					<div class="form-group">
						<label class="col-md-4" for="Amount">Amount:</label>
						<div class="col-md-8">
							<input type="text" class="form-control" name="amount" id="" placeholder="Amount" value="">
						</div>
					</div> 
					<div class="show_current_fiscal_year"> 
					</div>
						<div class="form-group">
						<div class="col-md-offset-4 col-md-8">
							<a href="" class="btn btn-default" style="margin-right:20px">Cancel</a>
							<button type="submit" class="btn btn-default" name="commit_fund">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Manage Funds Content Ends Here -->

	<!-- Data Display Here -->
	<div class="data-display container-fluid">
		<div style="float:right;margin-bottom:5px;">
			 <table >
			 	<tr>
			 		<td style="background:red;width:20px;">&nbsp;</td><td>&nbsp; Reversed to OU &nbsp;&nbsp;</td> 
					<td style="background:#2E9AFE;width:20px;">&nbsp;</td><td>&nbsp; Obligated&nbsp;&nbsp;</td>
			 		<td style="background:#E67E22;width:20px;">&nbsp;</td><td>&nbsp; DeObligated</td>
			 	</tr>
			 </table>
		</div> 
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
	            <tr> 
	                <th style="">FS + PE</th>
	                <th style="">Commited In</th>
	                <th style="">Commited In <br/>Type</th>
	                <th style="">OU</th>
                    <th style="">Fund Beginning <br/>  FY</th>
	                <th style="">Fund Ending <br/>FY</th>
	                <th style="">Commited</th>
	                <th style="">Obligated</th> 
	                <th>Action</th>
	            </tr>
	        </thead>
	        <tbody>
	        <?php
	            for($count_pe=0; $count_pe<count($all_comiit_fund_gp_arr['data']); $count_pe++)
	            { 
	            	$ledger_type_id = $all_comiit_fund_gp_arr['data'][$count_pe]['ledger_type_id'];
	            	$fund_beginning_fiscal_year = $all_comiit_fund_gp_arr['data'][$count_pe]['fund_beginning_fiscal_year'];
	            	$fund_ending_fiscal_year = $all_comiit_fund_gp_arr['data'][$count_pe]['fund_ending_fiscal_year'];
	            	$fund_id = $all_comiit_fund_gp_arr['data'][$count_pe]['fund_id'];
	            	$pe_id = $all_comiit_fund_gp_arr['data'][$count_pe]['program_element_id'];
	            	$ledger_type = $all_comiit_fund_gp_arr['data'][$count_pe]['ledger_type'];

	            	###get closing balance of commited fund (Available balance)
					$closing_balance_arr = getClosingBalance($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$fund_id,$ledger_type,$pe_id,'Commit');
					
					### get all fund source of this ===========
					$url = API_HOST_URL."get_all_allow_ou_fund_group_source.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&program_element_id=".$pe_id;
					$source_arr = requestByCURL($url); 

					### get De-Commited=============
					$ledger_type = str_replace(" ","_",$ledger_type);
					$obligated_balance_arr = getTotalObligated($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$fund_id,$ledger_type,$pe_id);
					
					###get total reversed amount of program element===============
					$ledger_type = str_replace(" ","_",$ledger_type);
	            	$url = API_HOST_URL."get_all_transaction_uncommited.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".$ledger_type;
					$debit_transaction_arr = requestByCURL($url);
					 
					### get all reverse transaction count from obligated ==========
					$url = API_HOST_URL."get_all_reverse_fund_from_commit_to_obligate_count.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".str_replace(' ', '_', $ledger_type)."";
					$reverse_count_from_arr = requestByCURL($url);
					$total_de_obligated_amount = $reverse_count_from_arr['data'][0]['total_reverse_amount'];

					## get project OU by project or actvity======
					$operating_unit_id = '';
					if($all_comiit_fund_gp_arr['data'][$count_pe]['ledger_type']=='Project Activity' or $all_comiit_fund_gp_arr['data'][$count_pe]['ledger_type']=='Project')
					{
						$url = AMP_API_HOST_URL."get_ou_by_project_activity.php?ledger_type_id=".$ledger_type_id;
						$project_ou_arr = requestByCURL($url);
						$operating_unit_id = $project_ou_arr['data']['operating_unit_id'];		
					}
					if($all_comiit_fund_gp_arr['data'][$count_pe]['ledger_type']=='Award CLIN')
					{
						$url = GS_API_HOST_URL."get_ou_by_award_clin.php?ledger_type_id=".$ledger_type_id;
						$project_ou_arr = requestByCURL($url);
						$operating_unit_id = $project_ou_arr['data']['operating_unit_id'];		
					}

					###get ou detail===================
					$operating_unit = '';
					if($operating_unit_id!='')
					{
						$url = API_HOST_URL."get_operating_unit_by_id.php?operating_unit_id=".$operating_unit_id;
						$ou_arr = requestByCURL($url);
						$operating_unit = $ou_arr['data']['operating_unit_description'];	
					}

					### calculation for amount =======================
					// total commited amount 
					$total_commited_amount = $all_comiit_fund_gp_arr['data'][$count_pe]['total_amount']-$total_de_obligated_amount; // total commited 
					// total reverse amount means decommited
					$total_de_commited_amount = $debit_transaction_arr['data'][0]['total_debit_amount'];
					// total obligated amount 
					$total_obligated_amount = $obligated_balance_arr['total_debit_amount'];
	            ?>	 
		            <tr class="parent-tbl">
		            	<td><?php echo $all_comiit_fund_gp_arr['data'][$count_pe]['narration']; ?></td>
		            	<td><?php echo $ledger_type_id; ?></td>
		            	<td><?php echo $all_comiit_fund_gp_arr['data'][$count_pe]['ledger_type']; ?></td>
		            	<td><?php echo $operating_unit; ?></td>    
		            	<td><?php if(!empty($fund_beginning_fiscal_year))echo $fund_beginning_fiscal_year;else echo 'No Expiration'; ?></td>
		            	<td><?php if(!empty($fund_ending_fiscal_year))echo $fund_ending_fiscal_year;else echo 'No Expiration'; ?></td>
		            	<td><?php echo '<div class="tip" title="Total Commited: $'.number_format($total_commited_amount).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total De-commited: -$'.number_format($total_de_commited_amount).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total Obligated: -$'.number_format($total_obligated_amount - $total_de_obligated_amount).'"> $'.number_format($closing_balance_arr['closing_balance']).'</div>'; ?></td> 
						<td><?php echo '<div class="tip" title="Total Obligated: $'.number_format($total_obligated_amount).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total De-obligated: -$'.number_format($total_de_obligated_amount).'"> $'.number_format($total_obligated_amount - $total_de_obligated_amount).'</div>'; ?></td>
		            	<td>
							<form action="" method="post">
	            			<input type="hidden" name="debit_from_id" class="debit_from_id" value="<?php echo $all_comiit_fund_gp_arr['data'][$count_pe]['ledger_type_id'];?>">
	            			<input type="hidden" name="credit_in_id" class="credit_in_id" value="">
	            			<input type="hidden" name="fund_id" class="fund_id" value="<?php echo $all_comiit_fund_gp_arr['data'][$count_pe]['fund_id'];?>">
	            			<input type="hidden" name="pe_id" class="pe_id" value="<?php echo $all_comiit_fund_gp_arr['data'][$count_pe]['program_element_id'];?>">
	            			<input type="hidden" name="fund_beginning_fiscal_year" class="fund_beginning_fiscal_year" value="<?php echo $all_comiit_fund_gp_arr['data'][$count_pe]['fund_beginning_fiscal_year'];?>">
	            			<input type="hidden" name="fund_ending_fiscal_year" class="fund_ending_fiscal_year" value="<?php echo $all_comiit_fund_gp_arr['data'][$count_pe]['fund_ending_fiscal_year'];?>">
							<input type="hidden" name="ledger_type" class="ledger_type" value="<?php echo $all_comiit_fund_gp_arr['data'][$count_pe]['ledger_type'];?>">
	            			<input type="hidden" name="total_amount" class="total_amount" value="">
	            			<input type="hidden" name="narration" value="<?php echo $all_comiit_fund_gp_arr['data'][$count_pe]['narration'];?>">
	            			<div class="rev-amt disp-none">		  
		                		<p><b>Enter Reverse Amount</b></p>
		                		<div class="row">
		                			<div class="col-md-12" >
			                			 <select name="source" onchange="fillVal(this);">
			                			 	<option value="">Select Source</option>
			                			 	<?php
			                			 	for($k=0; $k<count($source_arr['data']);$k++)
			                			 	{  
			                			 	?>
			                			 		<option value="<?php echo $source_arr['data'][$k]['op_id'].'='.$source_arr['data'][$k]['total_amount'];?>"><?php echo $source_arr['data'][$k]['origination_point'];?></option>
			                			 	<?php
			                			 	}
			                			 	?>
			                			 </select>
			                			 <br>
			                		</div> 
		                			<div class="col-md-6 amnt_input disp-none" ><input type="text" name="reverse_amount"  class="form-control reverse_amount" onkeyup="checkReverseAmount(this);" autocomplete="off" placeholder="Amount" style="margin-top:5px;"></div>
		                			<div class="col-md-3 rev_save" style="display:none;"><button type="submit" name="reverse_to_commit" style="margin-top:7px;" class="btn btn-default btn-xs " onClick="reverseAmount(this)">Proceed</button>
		                			</div>
		                			<div class="col-md-3"><button type="button" style="margin-top:7px;" class="btn btn-default btn-xs" onClick="reverseAmount(this)">Cancel</button></div> 
		                		</div>
		                		<div class="text-danger amnt_err"></div>  	
		                	</div>
		                	<div class="rev"> 
		            			 <i class="btn btn-xs fa fa-chevron-circle-down" style="margin-right:20px;" onClick="showChild(this);"></i>
		            			 <input type="button"   value="De-commit" class="btn btn-default" onClick="reverse(this)">
		            		</div>
	            		</form> 
		            	</td> 
		            </tr>
		            <tr class="child-table disp-none">
		            	<td colspan="10">
		            		<div style="padding:10px;">
		            			<table class="table table-bordered" cellspacing="0" width="100%">
		            			<tr class="collapse in" style="background:#EBDEF0;">					
		            					<th class="text-center">Movement From</th>
		            					<th class="text-center">Movement To</th>
		            					<th class="text-center">Transaction Type</th> 
		            					<th class="text-center">Amount</th> 
		            					<th class="text-center">Date</th> 
		            				</tr>
		            				<?php
		            				$url = API_HOST_URL."get_all_commited_fund_transaction.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".str_replace(' ', '_', $ledger_type)."";
									$transaction_arr = requestByCURL($url);

									### get all transation for commited===============
									$url = API_HOST_URL."get_all_transaction_commited_to_obligate.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".str_replace(' ', '_', $ledger_type)."";
									$alloted_transaction_arr = requestByCURL($url);

									### get all reverse transaction from ==========
									$url = API_HOST_URL."get_all_reverse_fund_from_commit_to_obligate.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."&ledger_type=".str_replace(' ', '_', $ledger_type)."";
									$reverse_from_arr = requestByCURL($url);									
									
									##unique array for all transaction==============
									$count_un = 0;
									$all_transaction_arr = array();	
		            				 
	            					for($a=0; $a<count($transaction_arr['data']); $a++)
	            					{
	            						$debit_from = '';
	            						$credit_in = '';
	            						if($transaction_arr['data'][$a]['credit_amount']>0)
	            						{
	            							$credit_in = $transaction_arr['data'][$a]['ledger_type_id'].' ('.$transaction_arr['data'][$a]['ledger_type'].')';
	            							$debit_from = $transaction_arr['data'][$a]['operating_unit_abbreviation'].' ('.$transaction_arr['data'][$a]['operating_unit_description'].')';
	            						}
	            						else
	            						{
	            							$debit_from = $transaction_arr['data'][$a]['ledger_type_id'].' ('.$transaction_arr['data'][$a]['ledger_type'].')';;
	            							$credit_in = $transaction_arr['data'][$a]['operating_unit_abbreviation'].' ('.$transaction_arr['data'][$a]['operating_unit_description'].')';
	            						}

	            						### fill unique array to show all transaction========================
	            						$debit_amount = 0;
	            						$credit_amount = 0; 
	            						if($transaction_arr['data'][$a]['credit_amount']>0)
	            						{
	            							$credit_amount = $transaction_arr['data'][$a]['credit_amount'];
	            							$all_transaction_arr[$count_un]['tran_type'] = 'Commited';
	            							$all_transaction_arr[$count_un]['amount'] = $credit_amount; 
	            						}
	            						else
	            						{
	            							$debit_amount = $transaction_arr['data'][$a]['debit_amount'];
	            							$all_transaction_arr[$count_un]['tran_type'] = 'DeCommited';
	            							$all_transaction_arr[$count_un]['amount'] = $debit_amount;  
	            						} 
	            						$all_transaction_arr[$count_un]['move_from'] = $debit_from;
	            						$all_transaction_arr[$count_un]['move_to'] = $credit_in;
	            						$all_transaction_arr[$count_un]['credit_amount'] = $credit_amount;
	            						$all_transaction_arr[$count_un]['debit_amount'] = $debit_amount; 
	            						$all_transaction_arr[$count_un]['transaction_date'] = $transaction_arr['data'][$a]['transaction_date'];
	            						$count_un++; 
	            					}
	            					
									### loop for all transaction for Total Obligated======
									for($c=0; $c<count($alloted_transaction_arr['data']); $c++)
									{  
										$all_transaction_arr[$count_un]['move_from'] = $alloted_transaction_arr['data'][$c]['ledger_type_id'].' ('.$alloted_transaction_arr['data'][$c]['ledger_type'].') - Commited';
	            						$all_transaction_arr[$count_un]['move_to'] = $alloted_transaction_arr['data'][$c]['op_id'].' ('.$alloted_transaction_arr['data'][$c]['op_type'].') - Obligated';
	            						$all_transaction_arr[$count_un]['credit_amount'] = 0;
	            						$all_transaction_arr[$count_un]['debit_amount'] = $alloted_transaction_arr['data'][$c]['debit_amount'];
	            						$all_transaction_arr[$count_un]['amount'] = $alloted_transaction_arr['data'][$c]['debit_amount'];  
	            						$all_transaction_arr[$count_un]['tran_type'] = 'Obligated';
	            						$all_transaction_arr[$count_un]['transaction_date'] = $alloted_transaction_arr['data'][$c]['transaction_date'];
	            						$count_un++;
            						 
									}
		            				 
		            				### loop for all UnObligate ======
									for($b=0; $b<count($reverse_from_arr['data']); $b++)
									{  
										$all_transaction_arr[$count_un]['move_from'] = $reverse_from_arr['data'][$b]['ledger_type_id'].' ('.$reverse_from_arr['data'][$b]['ledger_type'].') - Obligated';
	            						$all_transaction_arr[$count_un]['move_to'] = $reverse_from_arr['data'][$b]['op_id'].' ('.$reverse_from_arr['data'][$b]['op_type'].') - Commited';
	            						$all_transaction_arr[$count_un]['credit_amount'] = $reverse_from_arr['data'][$b]['credit_amount'];
	            						$all_transaction_arr[$count_un]['amount'] = $reverse_from_arr['data'][$b]['credit_amount'];
	            						$all_transaction_arr[$count_un]['debit_amount'] = 0; 
	            						$all_transaction_arr[$count_un]['tran_type'] = 'DeObligated';
	            						$all_transaction_arr[$count_un]['transaction_date'] = $reverse_from_arr['data'][$b]['transaction_date'];
	            						$count_un++; 
									}  		            				 
		            				
									## code for displayed all transaction from sorted array======================
		            				$arr2 = array_msort($all_transaction_arr, array('transaction_date'=>SORT_ASC));// code for sorting
									$all_transaction_arr = array_values($arr2);
									$closing_balance = '';
									for($h=0; $h<count($all_transaction_arr); $h++)
									{ 
										$color = '';
										if($all_transaction_arr[$h]['tran_type']=='DeCommited')$color = 'red';
										if($all_transaction_arr[$h]['tran_type']=='Obligated')$color = '#2E9AFE';
										if($all_transaction_arr[$h]['tran_type']=='DeObligated')$color = '#E67E22';
										$credit_amount = '';
										$debit_amount ='';
										if($all_transaction_arr[$h]['credit_amount']>0)
										{
											$closing_balance = $closing_balance + $all_transaction_arr[$h]['credit_amount'];
											$credit_amount = '$'.number_format($all_transaction_arr[$h]['credit_amount']);
										}
										else
										{
											$closing_balance = $closing_balance - $all_transaction_arr[$h]['debit_amount'];	
											$debit_amount ='$'.number_format($all_transaction_arr[$h]['debit_amount']);
										}
            						?>
            						<tr class="collapse in ">
										<td><?php echo $all_transaction_arr[$h]['move_from']; ?></td>
										<td><?php echo $all_transaction_arr[$h]['move_to']; ?></td>
										<td><?php echo $all_transaction_arr[$h]['tran_type']; ?></td>
										<td style="color:<?php echo $color;?>; text-align:right;"><?php echo '$'.number_format($all_transaction_arr[$h]['amount']);  ?></td>
										 
										<td><?php echo $all_transaction_arr[$h]['transaction_date']; ?></td>
            						</tr>
            						<?php
									}  		            				 
		            				?>
		            			</table>
		            		</div>
		            	</td>
		            </tr>
		         <?php
		        }
		     ?>
		</table>
	</div>
	
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/main.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script type="text/javascript">
	$( function() {
	   $('.tip').tooltip();
	});	

	function showChild(elem){
		//alert($(elem).closest('.child-table').length);
		$(elem).closest('.parent-tbl').next('.child-table').toggleClass('disp-none');
	}
	function reverseAmount(elem){
		$(elem).closest('.parent-tbl').find('.rev-amt').addClass('disp-none');
		$(elem).closest('.parent-tbl').find('.rev').removeClass('disp-none');
	}
	function reverse(elem){
		$(elem).closest('.parent-tbl').find('.rev-amt').removeClass('disp-none');
		$(elem).closest('.parent-tbl').find('.rev').addClass('disp-none');
	}
	/*=====function for get ou fund drop down ==================*/
	function getOUFundDrop()
	{
		$('.show_ou_fund').css('display','none');
		$('.aj_loader').css('display','');
		var operating_unit_id = $('.operating_unit_id').val();
		 
		$.ajax({
		  type: "POST",
		  url: "ajax_files/get_ou_fund_drop.php",
		  data: {operating_unit_id:operating_unit_id},
		  success: function(data){
		  		$('.aj_loader').css('display','none');
		  		$('.show_ou_fund').css('display','block');	
		    	if(data!='')$('.show_ou_fund').html(data);
		  	}
		}); 
	}
	function getInputFieldByCommitType()
	{
		$('.show_input_field').css('display','none');
		$('.aj_loader_type').css('display','');
		var commit_type = $('.commit_type').val();
		var operating_unit_id = $('.operating_unit_id').val();
		//alert(operating_unit_id);
		if(commit_type=='')
		{
			$('.show_input_field').css('display','none');
			$('.aj_loader_type').css('display','none');
		}
		else
		{
			$.ajax({
			  type: "POST",
			  url: "ajax_files/get_input_field_by_commit_type.php",
			  data: {commit_type:commit_type,operating_unit_id:operating_unit_id},
			  success: function(data){
			  		$('.aj_loader_type').css('display','none');
			  		$('.show_input_field').css('display','block');	
			    	if(data!='')$('.show_input_field').html(data);
			  	}
			}); 	
		}	
	}
	
	function getcurrentFiscalyear()//current fiscal year
	{
	var cur_year = $('.ou_year').val();
	$.ajax({
		type: "POST",
		url: "ajax_files/get_current_fiscal_year.php",
		data: {cur_year:cur_year},
		success: function(data){
		$('.show_current_fiscal_year').html(data);
			}
		}); 
	}
 
 	function showProjectActivity()
	{
		var project_id = $('.project_id_class').val();
		$.ajax({
			type: "POST",
			url: "ajax_files/get_activity_drop.php",
			data: {project_id:project_id},
			success: function(data){
				$('.show_activity').html(data);
			}
		}); 
	}
/* function for check reverse=================*/
	function fillVal(elem)
    {
    	var source = $(elem).val();
    	if((source.length)>0)
    	{
    		$(elem).closest('.parent-tbl').find('.amnt_input').css('display','block');
    		arr = source.split('=');
    		$(elem).closest('.parent-tbl').find('.credit_in_id').val(arr[0]);
    		$(elem).closest('.parent-tbl').find('.total_amount').val(arr[1]);
    	}
    	else
    	{
    		$(elem).closest('.parent-tbl').find('.amnt_input').css('display','none');
    	}
    }
	function checkReverseAmount(elem)
	{
		var debit_from_id = $(elem).closest('.parent-tbl').find('.debit_from_id').val();
		var fund_id = $(elem).closest('.parent-tbl').find('.fund_id').val();
		var pe_id = $(elem).closest('.parent-tbl').find('.pe_id').val();
		var total_amount =  parseInt($(elem).closest('.parent-tbl').find('.total_amount').val());
		var ledger_type = $(elem).closest('.parent-tbl').find('.ledger_type').val();
		var fund_beginning_fiscal_year = $(elem).closest('.parent-tbl').find('.fund_beginning_fiscal_year').val();
		var fund_ending_fiscal_year = $(elem).closest('.parent-tbl').find('.fund_ending_fiscal_year').val();
		var reverse_amount =  $(elem).closest('.parent-tbl').find('.reverse_amount').val();
		var reverse_amount = reverse_amount.replace(/[^0-9\.]+/g, ""); // replace comma or $ from amount
		$(elem).closest('.parent-tbl').find('.amnt_err').html('');
		if(reverse_amount>0)
		{
			if(reverse_amount > total_amount)
			{
				$(elem).closest('.parent-tbl').find('.rev_save').css('display','none');
				$(elem).closest('.parent-tbl').find('.amnt_err').html('Sorry you can not reverse more than $'+ total_amount +' total amount');
			} 
			else
			{
				$(elem).closest('.parent-tbl').find('.rev_save').css('display','none');
				$.ajax({
				  type: "POST",
				  url: "ajax_files/check_closing_reverse_amnt.php",
				  data: {debit_from_id:debit_from_id,total_amount:total_amount,fund_beginning_fiscal_year:fund_beginning_fiscal_year,fund_ending_fiscal_year:fund_ending_fiscal_year,reverse_amount:reverse_amount,fund_id:fund_id,ledger_type:ledger_type,pe_id:pe_id},
				  success: function(data){
				  	 if(data!='')
				  	 {
				  	 	$(elem).closest('.parent-tbl').find('.rev_save').css('display','none');
				  	 	$(elem).closest('.parent-tbl').find('.amnt_err').html(data);	 
				  	 }
				  	 else
				  	 {
				  	 	$(elem).closest('.parent-tbl').find('.amnt_err').html('');	 
				  	 	$(elem).closest('.parent-tbl').find('.rev_save').css('display','');
				  	 } 
					 }
				  
				}); 
			}
		}
		else
		{
			$(elem).closest('.parent-tbl').find('.rev_save').css('display','none');
		}
	}
	
	</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
</body>
</html>