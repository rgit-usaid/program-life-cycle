<?php
include('config/config.inc.php');
include('include/function.inc.php');
$page_name = "fund_strip";
$error = '';
if(isset($_REQUEST['add_fund_time_period']))
{
	 $fund_beginning_fiscal_year = '';
	 $fund_ending_fiscal_year = ''; 
	 $beg_fiscal_year = '';
	 $end_fiscal_year = '';
	 $fund_transaction_id = trim($_REQUEST['fund_transaction_id']); 
	 $ledger_type_id_cr = trim($_REQUEST['fund_id']);// auto increment id not fund_id
	 $fiscal_year = trim($_REQUEST['fiscal_year']); //this is use to fiscal year restriction
	if($fiscal_year=='Y'){
		 $fund_beginning_fiscal_year =  " fund_beginning_fiscal_year = '".trim($_REQUEST['fund_beginning_fiscal_year'])."',";
		 $fund_ending_fiscal_year =  " fund_ending_fiscal_year = '".trim($_REQUEST['fund_ending_fiscal_year'])."',";

		$beg_fiscal_year = trim($_REQUEST['fund_beginning_fiscal_year']);
	 	$end_fiscal_year = trim($_REQUEST['fund_ending_fiscal_year']);
	}
	
	 $fund_amount = $mysqli->real_escape_string(trim($_REQUEST['fund_amount']));
	 $fund_amount = getNumericAmount($fund_amount); // use this function if amount has $ sign or comma
	 $fund_origination_point = $mysqli->real_escape_string(trim($_REQUEST['fund_origination_point']));
 
	 if($ledger_type_id_cr=='')
	 {
	 	$error = "Please select fund name";
	 }
	 elseif($fund_amount=='')
	 {
	 	$error = "Please enter amount";
	 }
	 elseif(!is_numeric($fund_amount))
	 {
	 	$error = "Please use only $ or comma in amount";
	 }
	 else
	 {
	 	if($fund_transaction_id=='')
	 	{
		 	###insert query into transaction table ===========
		 	$insert_fund_transaction = "insert into usaid_fund_transaction set
		 				transaction_type = 'Receive',
		 				fund_id = '".$ledger_type_id_cr."',
		 				 ".$fund_beginning_fiscal_year."
		 				 ".$fund_ending_fiscal_year."
		 				fund_amount = '".$fund_amount."',
		 				fund_fiscal_year_restriction = '".$fiscal_year."'"; 
		 	$result_fund_transaction = $mysqli->query($insert_fund_transaction);

		 	$transaction_id = $mysqli->insert_id;
		 	###insert query for credit amount into transaction detail table=======================
		 	$opn_clg_balance_arr = getClosingBalance($ledger_type_id_cr,$beg_fiscal_year,$end_fiscal_year,$ledger_type_id_cr,'Fund');
		 	$opening_balance = $opn_clg_balance_arr['opening_balance'];
		 	$closing_balance = $opn_clg_balance_arr['closing_balance'];
		 	$opening_balance = $closing_balance;
		 	$closing_balance = ($closing_balance + $fund_amount);
		 	$insert_fund_transaction_detail_cr = "insert into usaid_fund_transaction_detail set
		 				transaction_id = '".$transaction_id."', 
		 				credit_amount = '".$fund_amount."',
		 				opening_balance = '".$opening_balance."',
		 				closing_balance = '".$closing_balance."',
		 				ledger_type = 'Fund',
		 				ledger_type_id = '".$ledger_type_id_cr."'";
		 	$result_fund_transaction_detail_cr = $mysqli->query($insert_fund_transaction_detail_cr);

		 	###insert query for debit amount into transaction table=======================
		 	$insert_fund_transaction_detail_de = "insert into usaid_fund_transaction_detail set
		 				transaction_id = '".$transaction_id."', 
		 				debit_amount = '".$fund_amount."',
		 				ledger_type = 'Origination Point',
		 				ledger_type_id = '".$fund_origination_point."'";
		 	$result_fund_transaction_detail_de = $mysqli->query($insert_fund_transaction_detail_de);
		 	if($result_fund_transaction and $result_fund_transaction_detail_cr and  $result_fund_transaction_detail_de)
		 	{
		 		header("location:fund_time_period.php");
		 	}
		 } 
	 }
}

####reverse fund from fund time period to origination point =====
if(isset($_REQUEST['reverse_fund_time_period']))
{
	$fund_id = trim($_REQUEST['debit_from_id']);
	$origination_point_id = trim($_REQUEST['credit_in_id']);
	$beginning_fiscal_year = trim($_REQUEST['fund_beginning_fiscal_year']);
	$ending_fiscal_year = trim($_REQUEST['fund_ending_fiscal_year']);
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
								fund_id = '$fund_id',
								".$cond."
								fund_amount = '".$fund_amount."'";
		
		$result_fund_transaction = $mysqli->query($insert_to_bureau);
		$transaction_id = $mysqli->insert_id;
		
		###insert query for credit amount into transaction detail table=======================
	 	$opn_clg_balance_arr = getClosingBalance($origination_point_id,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,'Origination Point');
	 	$opening_balance = $opn_clg_balance_arr['opening_balance'];
	 	$closing_balance = $opn_clg_balance_arr['closing_balance'];
	 	$opening_balance =  $closing_balance;
	 	$closing_balance = ($closing_balance + $fund_amount);
	 	$insert_fund_transaction_detail_cr = "insert into usaid_fund_transaction_detail set
	 				transaction_id = '".$transaction_id."', 
	 				credit_amount = '".$fund_amount."',
	 				opening_balance = '".$opening_balance."',
	 				closing_balance = '".$closing_balance."',
	 				ledger_type = 'Origination Point',
	 				ledger_type_id = '".$origination_point_id."'";
	 	
	 	$result_fund_transaction_detail_cr = $mysqli->query($insert_fund_transaction_detail_cr);

	 	###insert query for debit amount into transaction table=======================
	 	$opn_clg_balance_arr = getClosingBalance($fund_id,$beginning_fiscal_year,$ending_fiscal_year,$fund_id,'Fund');
	 	$opening_balance = $opn_clg_balance_arr['opening_balance'];
	 	$closing_balance = $opn_clg_balance_arr['closing_balance'];

	 	$opening_balance = $closing_balance;
	 	$closing_balance = ($closing_balance - $fund_amount);

	 	$insert_fund_transaction_detail_de = "insert into usaid_fund_transaction_detail set
	 				transaction_id = '".$transaction_id."', 
	 				debit_amount = '".$fund_amount."',
	 				opening_balance = '".$opening_balance."',
	 				closing_balance = '".$closing_balance."',
	 				ledger_type = 'Fund',
	 				ledger_type_id = '".$fund_id."'";
	 	 
	 	$result_fund_transaction_detail_de = $mysqli->query($insert_fund_transaction_detail_de);
	 	if($result_fund_transaction and $result_fund_transaction_detail_cr and  $result_fund_transaction_detail_de)
	 	{
	 		header("location:fund_time_period.php");
	 	}
 	}
}

## API for get all fund list========
$url = API_HOST_URL."get_all_fund.php";  
$fund_all_arr = requestByCURL($url);

 ##API for group list with reverse button ===============
$url = API_HOST_URL."get_all_fund_strip_group.php";
$fund_all_fund_time_arr = requestByCURL($url);

## API for get all origination point ========
$url = API_HOST_URL."get_all_origination_point.php";
$all_origination_point_arr = requestByCURL($url);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>USAID - Phoenix</title>
	<!-- <link rel="shortcut icon" type="image/x-icon" href="images/hr-logo.gif" />	 -->
	
    <!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <!-- CSS
  ================================================== -->
 	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<link rel="stylesheet" href="css/bootstrap-select.css">

	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">

	<link href="css/font-awesome.min.css" type="text/css" rel="stylesheet">
  	
	<link href="css/style.css" type="text/css" rel="stylesheet"> 

	<style>
  		.disp-none{
  			display: none;
  		}
  		.rev-btn{
  			width: 15%;
  		}
  
  	</style> 

</head>
<body>
<!-- help popup section end-->
 <?php include ('include/help_popup.php'); ?>
<!-- help popup section end-->
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
			<li><a href="fund_time_period.php" class="btn btn-default">Fund Strip</a></li>
			<li><a href="fund_strip_program_element.php" class="btn btn-default active">Fund Strip + Program Element</a></li>
			<li><a href="allow_to_bureau.php" class="btn btn-default active">Allot to Bureau</a></li>
			<li><a href="allow_to_operating_unit.php" class="btn btn-default active">Allow to OU</a></li>
		</ul>
	</div>


	<!-- Main Manage Fund Content Goes Here -->

	<div class="container-fluid manage-form">
	<!--help icon section-->
      <!--<div class="col-sm-offset-9 col-sm-3 text-right" style="float:right;height:20px;"><img src="images/information.png" class="pointer_help" title="Click to view help of this page"></div>-->
  	<!-- help icon section end-->
		<div class="form-title">
			<i class="fa fa-minus" aria-hidden="true"></i><i class="fa fa-plus" aria-hidden="true"></i>Fund Strip
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
				 <input type="hidden" name="fund_transaction_id" value="<?php if(isset($fund_time_arr))echo $fund_time_arr['data']['transaction_id'];?>" >
					<div class="form-group">
						<label class="col-sm-4" for="Fund Name">Fund Name:</label>
						<div class="col-sm-8">
						<?php
						$fund_id = '';
						if(isset($_REQUEST['fund_id'])) $fund_id = $_REQUEST['fund_id'];
						
						?>		
							<select name="fund_id" class="form-control">
								<option value=''>Select</option>
								<?php
								for($count_cat=0; $count_cat<count($fund_all_arr['data']); $count_cat++)
								{
								?> 
								<option value="<?php echo $fund_all_arr['data'][$count_cat]['id'];?>" <?php if($fund_id==$fund_all_arr['data'][$count_cat]['fund_id'])echo 'selected="selected"'; ?>><?php echo $fund_all_arr['data'][$count_cat]['fund_name'];?></option>
								<?php
								} ?> 
							</select> 
						</div>
					</div>
					
					<div class="form-group">
			            <label class="col-md-4" for="Select Fiscal Year">Select Fiscal Year</label>
			            <div class="col-md-8">			   
			            <?php
						$fiscal_year = '';
						if(isset($_REQUEST['fiscal_year'])) $fiscal_year = $_REQUEST['fiscal_year'];
						
						?>	    
			                <label class="radio-inline">
			                    <input type="radio" id="chkYes" name="fiscal_year" <?php if($fiscal_year=='Y')echo 'checked="checked"';?> value="Y"/>
			                    Fiscal Year
			                </label>
			                <label class="radio-inline">
			                    <input type="radio" id="chkNo" name="fiscal_year" <?php if($fiscal_year=='N')echo 'checked="checked"';if($fiscal_year=='')echo 'checked="checked"'; ?> value="N" />
			                    Starting FY & No Ending FY
			                </label>
			            </div>
			        </div>
			       
					<div class="form-group fy">
						<label class="col-sm-4" for="Fund Beginning Budget Fiscal Year">Fund Beginning Budget Fiscal Year:</label>
						<div class="col-sm-8">
						 <?php
							$fund_beginning_fiscal_year = '';
							if(isset($_REQUEST['fund_beginning_fiscal_year'])) $fund_beginning_fiscal_year = $_REQUEST['fund_beginning_fiscal_year'];
							 
							?>		
							<input type="text" class="form-control" name="fund_beginning_fiscal_year" id="beg_year" placeholder="Beginning Fiscal Budget Year" value="<?php echo $fund_beginning_fiscal_year;?>">
						</div>
					</div>
					<div class="form-group fy">
						<label class="col-sm-4" for="Fund Ending Budget Fiscal Year">Fund Ending Budget Fiscal Year:</label>
						<div class="col-sm-8">
						 <?php
							$fund_ending_fiscal_year = '';
							if(isset($_REQUEST['fund_ending_fiscal_year'])) $fund_ending_fiscal_year = $_REQUEST['fund_ending_fiscal_year'];
							
							?>	
							<input type="text" class="form-control" name="fund_ending_fiscal_year" id="end_year" placeholder="Ending Fiscal Budget Year" value="<?php echo $fund_ending_fiscal_year;?>">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4" for="Fund Amount">Fund Amount:</label>
						<div class="col-sm-8">
						<?php
						$fund_amount = '';
						if(isset($_REQUEST['fund_amount'])) $fund_amount = $_REQUEST['fund_amount'];
						
						?>		
							<input type="text" class="form-control" name="fund_amount" id="fund_amount" placeholder="Enter Fund Amount" value="<?php echo $fund_amount?>">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-4" for="Fund Source">Origination Point:</label>
						<div class="col-sm-8">
						<?php
						$fund_origination_point = '';
						if(isset($_REQUEST['fund_origination_point'])) $fund_origination_point = $_REQUEST['fund_origination_point']; 
						?> 	
							<select name="fund_origination_point" class="form-control">
								<option value="">Select</option>
								<?php
								for($j=0; $j<count($all_origination_point_arr['data']); $j++)
								{ ?>
									<option value="<?php echo $all_origination_point_arr['data'][$j]['id']; ?>" <?php if($fund_origination_point==$all_origination_point_arr['data'][$j]['id'])echo 'selected="selected"'; ?>><?php echo $all_origination_point_arr['data'][$j]['origination_point_name']; ?></option>
								<?php
								}
								?> 
							</select>
						</div>
					</div> 
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							<a href="fund_time_period.php" class="btn btn-default" style="margin-right:20px">Cancel</a>
							<button type="submit" class="btn btn-default" name="add_fund_time_period">Save</button>
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
			 		<td style="background:red;width:20px;">&nbsp;</td><td>&nbsp; Reverse to Fund &nbsp;&nbsp;</td><td style="background:#2E9AFE;width:20px;">&nbsp;</td><td>&nbsp; Allot to FS+PE &nbsp;&nbsp;</td>
			 		<td style="background:#E67E22;width:20px;">&nbsp;</td><td>&nbsp; UnAllot from FS+PE </td>
			 	</tr>
			 </table>
		</div> 
		<table id="manage-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
            <tr>    
                <th>Fund Name</th>	                
                <th>Fund Beginning Fiscal Year</th>
                <th>Fund Ending Fiscal Year</th>
                <th>Available</th> 
                <th>Alloted to FS+PE</th> 
                <th width="20%">Action</th>
            </tr>
             </thead>
             <tbody>  
	         <?php
	        if(count($fund_all_fund_time_arr['data'])>0)
	        {
	            for($count_pe=0; $count_pe<count($fund_all_fund_time_arr['data']); $count_pe++)
	            { 
	            	$ledger_type_id = $fund_all_fund_time_arr['data'][$count_pe]['ledger_type_id'];
	            	$fund_id = $fund_all_fund_time_arr['data'][$count_pe]['fund_id'];
	            	$fund_beginning_fiscal_year = $fund_all_fund_time_arr['data'][$count_pe]['fund_beginning_fiscal_year'];
	            	$fund_ending_fiscal_year = $fund_all_fund_time_arr['data'][$count_pe]['fund_ending_fiscal_year'];

	            	###get total reversed amount===============
	            	$url = API_HOST_URL."get_total_reverse_of_fund_strip_by_fund.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year;
					$debit_transaction_arr = requestByCURL($url); 
					### get closing balance means available balance=============
					$closing_balance_arr = getClosingBalance($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year,$ledger_type_id,'Fund'); 			
					### get alloted balance to bureau=============
					$alloted_balance_arr = getTotalAllotedToPE($ledger_type_id,$fund_beginning_fiscal_year,$fund_ending_fiscal_year); 
							
					### get all fund source of this ===========
					$url = API_HOST_URL."get_all_fund_time_period_group_source.php?ledger_type_id=".$fund_all_fund_time_arr['data'][$count_pe]['ledger_type_id']."&b_year=".$fund_all_fund_time_arr['data'][$count_pe]['fund_beginning_fiscal_year']."&e_year=".$fund_all_fund_time_arr['data'][$count_pe]['fund_ending_fiscal_year']."";
					$source_arr = requestByCURL($url);

					### get reverse amount from FS+PE ===========
					$url = API_HOST_URL."get_reverse_amount_count_from_fs_pe.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."";
					$un_alloted_arr = requestByCURL($url);
					$total_fs_pe_rev_amount = $un_alloted_arr['data'][0]['total_fs_pe_rev_amount'];
					//total received amount from origination point
					$total_received_amount = $fund_all_fund_time_arr['data'][$count_pe]['total_amount'] - $total_fs_pe_rev_amount;
					$total_reversed_amount = $debit_transaction_arr['data']['total_debit_amount'];
					$total_alloted_amount = $alloted_balance_arr['total_debit_amount'];
	            ?>
	            	<tr class="parent-tbl child">
		                <td><?php echo $fund_all_fund_time_arr['data'][$count_pe]['fund_id'].' ('.$fund_all_fund_time_arr['data'][$count_pe]['fund_name'].')';?></td>	                
		                <td><?php if(!empty($fund_all_fund_time_arr['data'][$count_pe]['fund_beginning_fiscal_year']))echo $fund_all_fund_time_arr['data'][$count_pe]['fund_beginning_fiscal_year'];else echo 'No Expiration';?></td>
		                <td><?php if(!empty($fund_all_fund_time_arr['data'][$count_pe]['fund_ending_fiscal_year']))echo $fund_all_fund_time_arr['data'][$count_pe]['fund_ending_fiscal_year'];else echo 'No Expiration';?></td>
		                <td class="text_right"><?php echo '<div class="tip" title="Total received: $'.number_format($total_received_amount).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total reversed: -$'.number_format($total_reversed_amount).' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total alloted: -$'.number_format($total_alloted_amount - $total_fs_pe_rev_amount).'">$'.number_format($closing_balance_arr['closing_balance']).'</div>';?>
		                </td>
		                <td class="text_right"><?php echo '<div class="tip" title="Total alloted: $'.number_format($total_alloted_amount).' and Total un-alloted: $'.number_format($total_fs_pe_rev_amount).'">$'.number_format($total_alloted_amount - $total_fs_pe_rev_amount); ?> 
		                </td> 
		                <td>
		                <form action="" method="post">
	            			<input type="hidden" name="debit_from_id" class="debit_from_id" value="<?php echo $fund_all_fund_time_arr['data'][$count_pe]['ledger_type_id'];?>">
	            			<input type="hidden" name="credit_in_id" class="credit_in_id" value="">
	            			<input type="hidden" name="fund_beginning_fiscal_year" class="fund_beginning_fiscal_year" value="<?php echo $fund_all_fund_time_arr['data'][$count_pe]['fund_beginning_fiscal_year'];?>">
	            			<input type="hidden" name="fund_ending_fiscal_year" class="fund_ending_fiscal_year" value="<?php echo $fund_all_fund_time_arr['data'][$count_pe]['fund_ending_fiscal_year'];?>">
	            			<input type="hidden" name="total_amount" class="total_amount" value="">
	            			<div class="rev-amt disp-none">		  
		                		<p><b>Select Source & Enter Reverse Amount</b></p>
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
			                		<div class="col-md-6 amnt_input disp-none" >
			                			<input type="text" name="reverse_amount"  class="form-control reverse_amount" onkeyup="checkReverseAmount(this);" autocomplete="off" placeholder="Amount" style="margin-top:5px;">
			                		</div>
		                			<div class="col-md-3 rev_save" style="display:none;"><button type="submit" name="reverse_fund_time_period" style="margin-top:7px;" class="btn btn-default btn-xs " onClick="reverseAmount(this)">Proceed</button>
		                			</div>
			                		<div class="col-md-2"><button type="button" style="margin-top:7px;" class="btn btn-default btn-xs" onClick="reverseAmount(this)">Cancel</button>
			                		</div>
				                	 
			                	</div>
		                		<div class="text-danger amnt_err"></div>  	
		                	</div> 
	            			<div class="rev">
	            				<i class="btn btn-xs fa fa-chevron-circle-down" style="margin-right:20px;" onClick="showChild(this);"></i>
	            				<input type="hidden" name="transaction_id" value="<?php echo $fund_all_fund_time_arr['data'][$count_pe]['ledger_type_id'];?>">
	            				<input type="button" value="Reverse" class="btn btn-default" onClick="reverse(this)">
	            			</div>
	            		</form>
		                </td>
		            </tr>
		            <tr class="child-table disp-none">
		            	<td colspan="6">
		            		<div style="padding:10px;">
		            			<table class="table  table-bordered" cellspacing="0" width="100%">
		            			<tr class="collapse in" style="background:#EBDEF0;">					
		            					<th class="text-center">Movement From</th>
		            					<th class="text-center">Movement To</th>
		            					<th class="text-center">Transaction Type</th> 
		            					<th class="text-center">Amount</th>
		            					<th class="text-center">Date</th> 			            					
		            				</tr>
		            				<?php
		            				## api for get all transaction of a fund with reverse ======================
		            				$url = API_HOST_URL."get_all_fund_strip_transaction_by_fund.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."";
									$transaction_arr = requestByCURL($url);
		            				
		            				### get all transation for alloted fund to fs+pe===============
		            				$url = API_HOST_URL."get_all_fund_strip_transaction_alloted_to_pe.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year."";
									$alloted_transaction_arr = requestByCURL($url);
									
									### get all reverse transaction from PE==========
									$url = API_HOST_URL."get_all_reverse_fund_from_program_element.php?ledger_type_id=".$ledger_type_id."&b_year=".$fund_beginning_fiscal_year."&e_year=".$fund_ending_fiscal_year.""; 
									$reverse_from_transaction_arr = requestByCURL($url);

									##unique array for all transaction==============
									$count_un = 0;
									$all_transaction_arr = array(); 

	            					### loop for all transaction for a fund with reverse======
	            					for($a=0; $a<count($transaction_arr['data']); $a++)
	            					{ 
	            						$debit_from = '';
	            						$credit_in = '';
	            						if($transaction_arr['data'][$a]['credit_amount']>0)
	            						{
	            							$credit_in = $transaction_arr['data'][$a]['fund_name'];
	            							$debit_from = $transaction_arr['data'][$a]['origination_point'];
	            						}
	            						else
	            						{
	            							$debit_from = $transaction_arr['data'][$a]['fund_name'];
	            							$credit_in = $transaction_arr['data'][$a]['origination_point'];
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

	            					### loop for all transaction for alloted fund to bureau======
	            					for($c=0; $c<count($alloted_transaction_arr['data']); $c++)
	            					{ 
	            						$all_transaction_arr[$count_un]['move_from'] = $alloted_transaction_arr['data'][$c]['fund_name'];
	            						$all_transaction_arr[$count_un]['move_to'] = $alloted_transaction_arr['data'][$c]['ledger_type_id'];
	            						$all_transaction_arr[$count_un]['credit_amount'] = 0;
	            						$all_transaction_arr[$count_un]['debit_amount'] = $alloted_transaction_arr['data'][$c]['debit_amount'];
	            						$all_transaction_arr[$count_un]['amount'] = $alloted_transaction_arr['data'][$c]['debit_amount'];
	            						$all_transaction_arr[$count_un]['tran_type'] = 'Alloted';
	            						$all_transaction_arr[$count_un]['transaction_date'] = $alloted_transaction_arr['data'][$c]['transaction_date'];
	            						$count_un++;
	            					}
		            				
		            				### loop for all reverse transaction from bureau to fund strip======
	            					for($b=0; $b<count($reverse_from_transaction_arr['data']); $b++)
	            					{ 
	            						$all_transaction_arr[$count_un]['move_from'] = $reverse_from_transaction_arr['data'][$b]['debit_from_id'];
	            						$all_transaction_arr[$count_un]['move_to'] = $reverse_from_transaction_arr['data'][$b]['fund_name'];
	            						$all_transaction_arr[$count_un]['credit_amount'] = $reverse_from_transaction_arr['data'][$b]['credit_amount'];
	            						$all_transaction_arr[$count_un]['debit_amount'] = 0;
	            						$all_transaction_arr[$count_un]['amount'] = $reverse_from_transaction_arr['data'][$b]['credit_amount'];
	            						$all_transaction_arr[$count_un]['tran_type'] = 'UnAlloted';
	            						$all_transaction_arr[$count_un]['transaction_date'] = $reverse_from_transaction_arr['data'][$b]['transaction_date'];
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
										if($all_transaction_arr[$h]['tran_type']=='Alloted')$color = '#2E9AFE';
										if($all_transaction_arr[$h]['tran_type']=='UnAlloted')$color = '#E67E22';
										
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
	        }
	        else{
	        	echo '<tr><td colspan="8">No Data Available</td></tr>';
	        }    
	        ?> 
	        </tbody>
		</table>
	</div 
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery-2.1.4.min.js"></script>
	<script src="js/main.js"></script>
	<script>
		$( function() {
		   $('.tip').tooltip();
		}); 
		function showChild(elem){
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
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	 
	<script type="text/javascript">
    $(function () {
    	$('.fy').hide();
        $("input[name='fiscal_year']").click(function () {
            if ($("#chkYes").is(":checked")) {
                $(".fy").show();
            } else {
                $(".fy").hide();
            }
        });
    });

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
				  data: {debit_from_id:debit_from_id,total_amount:total_amount,fund_beginning_fiscal_year:fund_beginning_fiscal_year,fund_ending_fiscal_year:fund_ending_fiscal_year,reverse_amount:reverse_amount,fund_id:debit_from_id,ledger_type:'Fund'},
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
	<script src="js/bootstrap-select.js"></script>
<script>
    $(document).ready(function(){
		$('.pointer_help').click(function(){
			$(this).hide();
			$('.fund-help-popup').show();
			
			
			});
			$('.cancel-help').click(function(){
				$('.fund-help-popup').hide();
				$('.pointer_help').show();
				
				});
				$('body').click(function() {
					if (!$(event.target).is('.pointer_help')){
						$("body").find(".fund-help-popup").hide();
						$('.pointer_help').show();
					}
       
	             });
				 
				 $(".fund-help-popup").click(function(e) {
					e.stopPropagation(); // This is the preferred method.
					return false;        // This should not be used unless you do not want
										 // any click events registering inside the div
				});
		}); 
    </script>
</body>
</html>