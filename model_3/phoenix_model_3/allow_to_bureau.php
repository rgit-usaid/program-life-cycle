<?php
include('config/config.inc.php');
include('include/function.inc.php');
## get unique period fund for drop down==========
$url = API_HOST_URL."get_all_funded_program_element.php";
$unique_funded_pe_arr = requestByCURL($url);

## API for get all list of group data ==========
$url = API_HOST_URL."get_all_allot_bureau_fund_group.php";
$all_bureau_fund_arr = requestByCURL($url);

## get all reverse fund to bureau from operating unit ==========
$url = API_HOST_URL."get_all_reverse_fund_from_ou_to_bureau.php";
$all_reverse_fund_arr = requestByCURL($url);

## get unique period fund strip and program element for drop down==========
$url = API_HOST_URL."get_program_element_fund_unique.php";
$unique_fund_arr = requestByCURL($url);

## get all bureau ==========
$url = API_HOST_URL."get_all_bureau.php";
$all_bureau_arr = requestByCURL($url);

### add fund to bureau===========
$error = '';
if(isset($_REQUEST['allow_to_bureau']))
{
	$fund_id = '';
	$beginning_fiscal_year = '';
	$ending_fiscal_year = '';
	$full_strip = trim($_REQUEST['strip_year']);
	$full_strip_arr = explode('>>',$full_strip);
	$strip_narration = $full_strip_arr[0];
	$real_amount = $full_strip_arr[1]; // get real amount to validate alloted amount

	$strip_year = str_replace("-"," ",trim($strip_narration));
	$fund_year_arr = explode(' ', $strip_year);
	$bureau_id = trim($_REQUEST['bureau_id']); //cr
	$fund_amount = trim($_REQUEST['amount']);
	$fund_amount = getNumericAmount($fund_amount); // use this function if amount has $ sign or comma
	$fund_code = $fund_year_arr[0];
	
	## fund id by fund code like DA==================
	$url = API_HOST_URL."get_fund.php?fund_id=".$fund_code."";
	$fund_arr = requestByCURL($url);
	$fund_id = $fund_arr['data']['id'];
	
	$cond = '';
	 if(in_array('FY',$fund_year_arr))
	 {
	 	$beginning_fiscal_year = $fund_year_arr[2];
	 	$ending_fiscal_year = $fund_year_arr[3];
	 	$pe_id= $fund_year_arr[4];
	 	$cond = "fund_beginning_fiscal_year = '".$beginning_fiscal_year."', fund_ending_fiscal_year='".$ending_fiscal_year."',";
	 }
	 else
	 {
	 	$pe_id= $fund_year_arr[1];//dr
	 }
	 
 
	### PHP validation ======
	if($strip_year=='')
	{
		$error= "Please select Fund Strip + Program Element";
	}
	if($bureau_id=='')
	{
		$error= "Please select Bureau";
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
	$insert_to_bureau = "insert into usaid_fund_transaction set
							transaction_type = 'Allocate',
							narration = '".$strip_narration."',
							fund_id = '$fund_id',
							program_element_id = '$pe_id',
							".$cond."
							fund_amount = '".$fund_amount."'";
				
		$result_fund_transaction = $mysqli->query($insert_to_bureau);
		$transaction_id = $mysqli->insert_id;
		
		###insert query for credit amount into transaction detail table=======================
	 	$opn_clg_balance_arr = getClosingBalance($bureau_id,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,'Operating Unit',$pe_id);
	 	
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
	 				ledger_type_id = '".$bureau_id."'"; 
	 	$result_fund_transaction_detail_cr = $mysqli->query($insert_fund_transaction_detail_cr);

	 	###insert query for debit amount into transaction table=======================
	 	$opn_clg_balance_arr = getClosingBalance($pe_id,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,'Program Element',$pe_id);
	 	
	 	$opening_balance = $opn_clg_balance_arr['opening_balance'];
	 	$closing_balance = $opn_clg_balance_arr['closing_balance'];
 
	 	$opening_balance = $closing_balance;
	 	$closing_balance = ($closing_balance - $fund_amount);

	 	$insert_fund_transaction_detail_de = "insert into usaid_fund_transaction_detail set
	 				transaction_id = '".$transaction_id."', 
	 				debit_amount = '".$fund_amount."',
	 				opening_balance = '".$opening_balance."',
	 				closing_balance = '".$closing_balance."',
	 				ledger_type = 'Program Element',
	 				ledger_type_id = '".$pe_id."'";
	 	$result_fund_transaction_detail_de = $mysqli->query($insert_fund_transaction_detail_de);
	 	if($result_fund_transaction and $result_fund_transaction_detail_cr and  $result_fund_transaction_detail_de)
	 	{
	 		header("location:allow_to_bureau.php");
	 	}
	}
}

####reverse fund to Fund Strip + Program Element==============
if(isset($_REQUEST['reverse_allow_to_bureau']))
{
	$debit_from_id = trim($_REQUEST['debit_from_id']);//bureau dr
	$credit_in_id = trim($_REQUEST['credit_in_id']); //program element cr
	$fund_id = trim($_REQUEST['fund_id']);
	$pe_id = trim($_REQUEST['pe_id']);
	$beginning_fiscal_year = trim($_REQUEST['fund_beginning_fiscal_year']);
	$ending_fiscal_year = trim($_REQUEST['fund_ending_fiscal_year']);
	$narration = trim($_REQUEST['narration']);
	$fund_amount = trim($_REQUEST['reverse_amount']);
	$fund_amount = getNumericAmount($fund_amount); // use this function if amount has $ sign or comma
	if($fund_amount>0)
	{
		$cond = '';
		if($beginning_fiscal_year!='' and $ending_fiscal_year!='')
		{
		 	$cond = " fund_beginning_fiscal_year = '".$beginning_fiscal_year."', fund_ending_fiscal_year='".$ending_fiscal_year."',";
		}
		 
	 	$insert_to_bureau = "insert into usaid_fund_transaction set
							transaction_type = 'Reverse',
							narration = '".$narration."',
							fund_id = '".$fund_id."',
							program_element_id = '".$pe_id."',
							".$cond."
							fund_amount = '".$fund_amount."'
		";
		
		$result_fund_transaction = $mysqli->query($insert_to_bureau);
		$transaction_id = $mysqli->insert_id;
		
		###insert query for credit amount into transaction detail table=======================
	 	$opn_clg_balance_arr = getClosingBalance($credit_in_id,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,'Program Element',$pe_id);
	 	$opening_balance = $opn_clg_balance_arr['opening_balance'];
	 	$closing_balance = $opn_clg_balance_arr['closing_balance'];
	 	$opening_balance =  $closing_balance;
	 	$closing_balance = ($closing_balance + $fund_amount);
	 	$insert_fund_transaction_detail_cr = "insert into usaid_fund_transaction_detail set
	 				transaction_id = '".$transaction_id."', 
	 				credit_amount = '".$fund_amount."',
	 				opening_balance = '".$opening_balance."',
	 				closing_balance = '".$closing_balance."',
	 				ledger_type = 'Program Element',
	 				ledger_type_id = '".$credit_in_id."'";
	 		 		 
	 	$result_fund_transaction_detail_cr = $mysqli->query($insert_fund_transaction_detail_cr);

	 	###insert query for debit amount into transaction table======================= 	
	 	$opn_clg_balance_arr = getClosingBalance($debit_from_id,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,'Operating Unit',$pe_id);
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
	 				ledger_type_id = '".$debit_from_id."'";
	 	$result_fund_transaction_detail_de = $mysqli->query($insert_fund_transaction_detail_de);
	 	if($result_fund_transaction and $result_fund_transaction_detail_cr and  $result_fund_transaction_detail_de)
	 	{
	 		header("location:allow_to_bureau.php");
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
	<link rel="stylesheet" href="css/bootstrap-select.css">  
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
  		<li class="active">Manage Funds</li>
	</ol>

	<!-- / Breadcrumbs  -->

	<!-- Pop Up Div Menu Nav -->

	<div class="menu-nav">
		<ul class="nav navbar-nav navbar">
			<li><a href="fund.php" class="btn btn-default active">Fund</a></li>
			<li><a href="fund_time_period.php" class="btn btn-default active">Fund Strip</a></li>
			<li><a href="fund_strip_program_element.php" class="btn btn-default  active ">Fund Strip + Program Element</a></li>
			<li><a href="allow_to_bureau.php" class="btn btn-default">Allot to Bureau</a></li>
			<li><a href="allow_to_operating_unit.php" class="btn btn-default active ">Allow to OU</a></li>
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
						<label class="col-sm-4" for="Fund Strip + Program Element">Fund Strip + Program Element:</label>
						<div class="col-sm-8">
							<select name="strip_year" class="form-control strip_year">
								<option value="">Select</option>

							<?php
							for($i=0; $i<count($unique_fund_arr['data']); $i++)
							{
								$unique_total_fund_arr = getClosingBalance($unique_fund_arr['data'][$i]['ledger_type_id'],$unique_fund_arr['data'][$i]['fund_beginning_fiscal_year'],$unique_fund_arr['data'][$i]['fund_ending_fiscal_year'],$unique_fund_arr['data'][$i]['fund_id'],'Program Element',$unique_fund_arr['data'][$i]['ledger_type_id']); 
								?>	
								<option value="<?php echo $unique_fund_arr['data'][$i]['gp_year'].'>>'.$unique_total_fund_arr['closing_balance']; ?>"><?php echo $unique_fund_arr['data'][$i]['gp_year']; if($unique_total_fund_arr['closing_balance']!='')echo ' ($'.number_format($unique_total_fund_arr['closing_balance']).')'; ?>
								</option>
								<?php
							}
							?>	 
							</select> 
						</div>
					</div>
						<div class="form-group">
						<label class="col-sm-4" for="Bureau">Bureau:</label>
						<div class="col-sm-8">
							<select name="bureau_id" class="form-control">
								<option value=''>Select</option>
								<?php
								for($b_count=0; $b_count<count($all_bureau_arr['data']); $b_count++)
								{
								?>	
									<option value="<?php echo $all_bureau_arr['data'][$b_count]['operating_unit_id']; ?>"><?php echo $all_bureau_arr['data'][$b_count]['operating_unit_abbreviation'].' ('.$all_bureau_arr['data'][$b_count]['operating_unit_description'].')'; ?></option>
								<?php
								} ?> 
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4" for="Amount">Amount:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="amount" >
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							<a href="" class="btn btn-default" style="margin-right:20px">Cancel</a>
							<button type="submit" class="btn btn-default" name="allow_to_bureau">Save</button>
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
			 		<td style="background:red;width:20px;">&nbsp;</td><td>&nbsp; Reverse to FS+PE &nbsp;&nbsp;</td><td style="background:#2E9AFE;width:20px;">&nbsp;</td><td>&nbsp; Allow to OU &nbsp;&nbsp;</td>
			 		<td style="background:#E67E22;width:20px;">&nbsp;</td><td>&nbsp; UnAllow from OU</td>
			 	</tr>
			 </table>
		</div> 
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
	            <tr>
	            	<th>FS + PE</th>
	            	<th>Bureau</th>
	            	<th>Fund Beginning Fiscal Year</th>
	                <th>Fund Ending Fiscal Year</th>
	                <th>Available</th>
	                <th>Allowed to OU</th> 
	                <th width="20%">Action</th>
	            </tr>
	        </thead>
	        <tbody> 
	            <?php
	            for($count_pe=0; $count_pe<count($all_bureau_fund_arr['data']); $count_pe++)
	            { 
	            	$ledger_type_id = $all_bureau_fund_arr['data'][$count_pe]['ledger_type_id'];//cr bureau
	            	$fund_id = $all_bureau_fund_arr['data'][$count_pe]['fund_id']; 
	            	$pe_id = $all_bureau_fund_arr['data'][$count_pe]['program_element_id'];
	            	$fund_beginning_fiscal_year = $all_bureau_fund_arr['data'][$count_pe]['fund_beginning_fiscal_year'];
	            	$fund_ending_fiscal_year = $all_bureau_fund_arr['data'][$count_pe]['fund_ending_fiscal_year'];
	            	
	            	###get total reversed amount of program element===============
	            	 $url = API_HOST_URL."get_total_reverse_of_pe_by_bureau.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id;
					$debit_transaction_arr = requestByCURL($url);
				
					### get closing balance means available balance=============
					$closing_balance_arr =getClosingBalance($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$fund_id,'Operating Unit',$pe_id);
					
					### get alloted balance to OU=============
					$alloted_balance_arr = getTotalAllotedToOU($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$fund_id,'Operating Unit',$pe_id);
															
					### get all fund source of this ===========
					$url = API_HOST_URL."get_all_allow_bureau_fund_group_source.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&program_element_id=".$pe_id;
					$source_arr = requestByCURL($url);

					### get un allowed amount from OU ===========
					$url = API_HOST_URL."get_un_allowed_amount_count_from_ou.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id;
					$un_alloted_arr = requestByCURL($url);
					$total_un_allowed_amount = $un_alloted_arr['data'][0]['total_un_allowed_amount'];

					### amount calculation variable===========
					// total received amount
					$total_received_amount = $all_bureau_fund_arr['data'][$count_pe]['total_amount'] - $total_un_allowed_amount;
					// total reversed amount
					$total_reversed_amount = $debit_transaction_arr['data'][0]['total_debit_amount'];
					// total allowed amount
					$total_allowed_amount = $alloted_balance_arr['total_debit_amount'];

	            ?>
	            	<tr class="parent-tbl">
	            		 <td><?php echo $all_bureau_fund_arr['data'][$count_pe]['narration'];?></td>	
		                <td><?php echo $all_bureau_fund_arr['data'][$count_pe]['operating_unit_abbreviation'].' '.'('.$all_bureau_fund_arr['data'][$count_pe]['operating_unit_description'].')';?></td>	                
		                <td><?php if(!empty($all_bureau_fund_arr['data'][$count_pe]['fund_beginning_fiscal_year']))echo $all_bureau_fund_arr['data'][$count_pe]['fund_beginning_fiscal_year'];else echo 'No Expiration';?></td>
		                <td><?php if(!empty($all_bureau_fund_arr['data'][$count_pe]['fund_ending_fiscal_year']))echo $all_bureau_fund_arr['data'][$count_pe]['fund_ending_fiscal_year'];else echo 'No Expiration';?></td>
		                <td class="text_right"><?php echo '<div class="tip" title="Total received: $'.number_format($total_received_amount).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total reversed: -$'.number_format($total_reversed_amount).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total Allowed: -$'.number_format($total_allowed_amount - $total_un_allowed_amount).'">$'.number_format($closing_balance_arr['closing_balance']).'</div>';?></td> 
		                 <td class="text_right"><?php echo '<div class="tip" title="Total allowed: $'.number_format($total_allowed_amount).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total UnAllowed: $'.number_format($total_un_allowed_amount).'">$'.number_format($total_allowed_amount - $total_un_allowed_amount).'</div>'; ?> 
		                 </td> 
		                <td> 
		                <form action="" method="post">
	            			<input type="hidden" name="debit_from_id" class="debit_from_id" value="<?php echo $all_bureau_fund_arr['data'][$count_pe]['ledger_type_id'];?>">
	            			<input type="hidden" name="credit_in_id" class="credit_in_id" value="">
	            			<input type="hidden" name="fund_id" class="fund_id" value="<?php echo $all_bureau_fund_arr['data'][$count_pe]['fund_id'];?>">
	            			<input type="hidden" name="pe_id" class="pe_id" value="<?php echo $all_bureau_fund_arr['data'][$count_pe]['program_element_id'];?>">
	            			<input type="hidden" name="fund_beginning_fiscal_year" class="fund_beginning_fiscal_year" value="<?php echo $all_bureau_fund_arr['data'][$count_pe]['fund_beginning_fiscal_year'];?>">
	            			<input type="hidden" name="fund_ending_fiscal_year" class="fund_ending_fiscal_year" value="<?php echo $all_bureau_fund_arr['data'][$count_pe]['fund_ending_fiscal_year'];?>">
	            			<input type="hidden" name="total_amount" class="total_amount" value="">
	            			<input type="hidden" name="narration" value="<?php echo $all_bureau_fund_arr['data'][$count_pe]['narration'];?>">
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
		                			<div class="col-md-3 rev_save" style="display:none;"><button type="submit" name="reverse_allow_to_bureau" style="margin-top:7px;" class="btn btn-default btn-xs " onClick="reverseAmount(this)">Proceed</button>
		                			</div>
		                			<div class="col-md-3"><button type="button" style="margin-top:7px;" class="btn btn-default btn-xs" onClick="reverseAmount(this)">Cancel</button></div> 
		                		</div>
		                		<div class="text-danger amnt_err"></div>  	
		                	</div>
		                	<div class="rev"> 
		            			 <i class="btn btn-xs fa fa-chevron-circle-down" style="margin-right:20px;" onClick="showChild(this);"></i>
		            			 <input type="button"   value="Reverse" class="btn btn-default" onClick="reverse(this)">
		            		</div>
	            		</form> 
		                </td>
		            </tr>
		            <tr class="child-table disp-none">
		            	<td colspan="7">
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
	            				$url = API_HOST_URL."get_all_bureau_transaction_by_bureau.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."";
								$transaction_arr = requestByCURL($url);

								### get all transation for alloted fund to bureau===============
            					$url = API_HOST_URL."get_all_bureau_transaction_alloted_ou.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."";
								$alloted_transaction_arr = requestByCURL($url);
								
								### get all reverse transaction from ==========
								$url = API_HOST_URL."get_all_reverse_fund_from_ou_to_ou.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."&fund_id=".$fund_id."&pe_id=".$pe_id."";
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
            							$credit_in = $transaction_arr['data'][$a]['operating_unit_abbreviation'].' '.'('.$transaction_arr['data'][$a]['operating_unit_description'].')';
            							$debit_from = $transaction_arr['data'][$a]['narration'];
            						}
            						else
            						{
            							$debit_from = $transaction_arr['data'][$a]['operating_unit_abbreviation'].' '.$transaction_arr['data'][$a]['operating_unit_description'];
            							$credit_in = $transaction_arr['data'][$a]['narration'];
            						}

            						### fill unique array to show all transaction ========================
            						$debit_amount = 0;
            						$credit_amount = 0; 
            						if($transaction_arr['data'][$a]['credit_amount']>0)
            						{
            							$credit_amount = $transaction_arr['data'][$a]['credit_amount'];
            							$all_transaction_arr[$count_un]['tran_type'] = 'Received';
            							$all_transaction_arr[$count_un]['amount'] = $credit_amount; 
            						}
            						else
            						{
            							$debit_amount = $transaction_arr['data'][$a]['debit_amount'];
            							$all_transaction_arr[$count_un]['tran_type'] = 'Reversed';
            							$all_transaction_arr[$count_un]['amount'] = $debit_amount;  
            						} 
            						$all_transaction_arr[$count_un]['move_from'] = $debit_from;
            						$all_transaction_arr[$count_un]['move_to'] = $credit_in;
            						$all_transaction_arr[$count_un]['credit_amount'] = $credit_amount;
            						$all_transaction_arr[$count_un]['debit_amount'] = $debit_amount;
            						$all_transaction_arr[$count_un]['transaction_date'] = $transaction_arr['data'][$a]['transaction_date'];
            						$count_un++; 
        						} 
            					### loop for all transaction for alloted fund to OU======
            					for($c=0; $c<count($alloted_transaction_arr['data']); $c++)
            					{ 
        							$all_transaction_arr[$count_un]['move_from'] = $alloted_transaction_arr['data'][$c]['debit_from'];
            						$all_transaction_arr[$count_un]['move_to'] = $alloted_transaction_arr['data'][$c]['operating_unit_abbreviation'].' ('.$alloted_transaction_arr['data'][$c]['operating_unit_description'].')';
            						$all_transaction_arr[$count_un]['credit_amount'] = 0;
            						$all_transaction_arr[$count_un]['debit_amount'] = $alloted_transaction_arr['data'][$c]['debit_amount'];
            						$all_transaction_arr[$count_un]['amount'] = $alloted_transaction_arr['data'][$c]['debit_amount'];
            						$all_transaction_arr[$count_un]['tran_type'] = 'Allowed';
            						$all_transaction_arr[$count_un]['transaction_date'] = $alloted_transaction_arr['data'][$c]['transaction_date'];
            						$count_un++; 
            					}

            					### loop for all reverse transaction from OU to bureau ======
            					for($b=0; $b<count($reverse_from_arr['data']); $b++)
            					{ 
            						$all_transaction_arr[$count_un]['move_from'] = $reverse_from_arr['data'][$b]['movement_from'];
	            						$all_transaction_arr[$count_un]['move_to'] = $reverse_from_arr['data'][$b]['movement_to'];
	            						$all_transaction_arr[$count_un]['credit_amount'] = $reverse_from_arr['data'][$b]['credit_amount'];
	            						$all_transaction_arr[$count_un]['debit_amount'] = 0;
	            						$all_transaction_arr[$count_un]['amount'] = $reverse_from_arr['data'][$b]['credit_amount'];
	            						$all_transaction_arr[$count_un]['tran_type'] = 'UnAllowed';
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
									if($all_transaction_arr[$h]['tran_type']=='Reversed')$color = 'red';
									if($all_transaction_arr[$h]['tran_type']=='Allowed')$color = '#2E9AFE';
									if($all_transaction_arr[$h]['tran_type']=='UnAllowed')$color = '#E67E22';
									
									$credit_amount = '';
									$debit_amount ='';
									if($all_transaction_arr[$h]['credit_amount']>0)
									{
										$closing_balance = $closing_balance + $all_transaction_arr[$h]['credit_amount'];
										$credit_amount = '$'.number_format($all_transaction_arr[$h]['credit_amount']);
										$amount = '$'.number_format($all_transaction_arr[$h]['amount']);
									}
									else
									{
										$closing_balance = $closing_balance - $all_transaction_arr[$h]['debit_amount'];	
										$debit_amount ='$'.number_format($all_transaction_arr[$h]['debit_amount']);
										$amount ='$'.number_format($all_transaction_arr[$h]['amount']);
									}

        						?>
        						<tr class="collapse in ">
									<td><?php echo $all_transaction_arr[$h]['move_from']; ?></td>
									<td><?php echo $all_transaction_arr[$h]['move_to']; ?></td>
									<td><?php echo $all_transaction_arr[$h]['tran_type']; ?></td>
									<td style="color:<?php echo $color;?>; text-align:right;"><?php echo $amount;?></td>
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
	        </tbody>
		</table>
	</div> 
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/main.js"></script>
	<script>
		$( function() {
		   $('.tip').tooltip();
		});	
		function showChild(elem){
		// alert($(elem).closest('.child-table').length);
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
	</script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-select.js"></script>
<script>
	function getPEFundDrop()
	{
		$('.show_bureau_fund').css('display','none');
		$('.aj_loader').css('display','');
		var pe_id = $('.pe_id').val();
		$.ajax({
		  type: "POST",
		  url: "ajax_files/get_program_element_fund_drop.php",
		  data: {pe_id:pe_id},
		  success: function(data){
		  		$('.aj_loader').css('display','none');
		  		$('.show_pe_fund').css('display','block');	
		    	if(data!='')$('.show_pe_fund').html(data);
		  }
		});

		$('.show_bureau_office').css('display','none');
		$('.ajax_loader').css('display','');
		var bureau_id = $('.bureau_id').val();
		$.ajax({
		  type: "POST",
		  url: "ajax_files/get_bureau_office_drop.php",
		  data: {bureau_id:bureau_id},
		  success: function(data){
		  		$('.ajax_loader').css('display','none');
		  		$('.show_bureau_office').css('display','block');	
		    	if(data!='')$('.show_bureau_office').html(data);
		  }
		});
	}

/* finction for check reverse=================*/
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
				  data: {debit_from_id:debit_from_id,total_amount:total_amount,fund_beginning_fiscal_year:fund_beginning_fiscal_year,fund_ending_fiscal_year:fund_ending_fiscal_year,reverse_amount:reverse_amount,fund_id:fund_id,ledger_type:'Operating Unit',pe_id:pe_id},
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

</body>
</html>