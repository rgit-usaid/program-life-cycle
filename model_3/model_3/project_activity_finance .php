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

	##==get project_info
	$url = API_HOST_URL_PROJECT."get_project.php?project_id=".$project_id."";  
    $project_arr = requestByCURL($url);

	##==get project_stage_info
    $url = API_HOST_URL_PROJECT."api_demo.php?stage"; 
  	$project_stage_arr = requestByCURL($url);
	
	
	$url = API_HOST_URL_PROJECT."get_project_activity.php?project_id=".$project_id."&activity_id=".$activity_id;  
    $project_activity_arr   = requestByCURL($url);
	
	$activity_start_date = $project_activity_arr['data']['actual_start_date'];
	$activity_end_date = $project_activity_arr['data']['actual_end_date'];
	$fiscal_year_start = date('Y',strtotime('-1 year',strtotime($project_activity_arr['data']['actual_start_date']))); 
	$fiscal_year_end = date('Y',strtotime('-1 year', strtotime($project_activity_arr['data']['actual_end_date']))); 
}


$FY_year_arr= array(); // array for show forecaster yearly basis
$fyear = array(); // year info forecaster data
$fyear["byear"] = $fyear["eyear"] = $fyear["bmonth"] = $fyear["emonth"] = 0; 
$start_fyear = $end_fyear = $start_fmonth = $end_fmonth = $start_fquarter = $end_fquarter = 0; 

$FY_finance_monthly = array();
$FY_finance_quarterly = array();

### get all award of a activity==============
$url = API_HOST_URL_GLAAS."get_award_by_activity.php?activity_id=".$activity_id."";
$award_arr  = requestByCURL($url);

## loop for get only award number in array==============
$award_number_arr = array();
for($j=0; $j<count($award_arr['data']); $j++)
{
	$award_number_arr[$j] = $award_arr['data'][$j]['award_number'];
}

$data_award = urlencode(serialize($award_number_arr));

$url = API_HOST_URL_PHOENIX."get_fund_by_awards.php?data_award=".$data_award."&fund_status=Obligate&fund_status2=Subobligate";  
$award_ob_fund  = requestByCURL($url);

$url = API_HOST_URL_PHOENIX."get_fund_by_awards.php?data_award=".$data_award."&fund_status=Commit";  
$activity_cm_fund  = requestByCURL($url);

$url = API_HOST_URL_PHOENIX."get_all_account_payable_by_award.php?data_award=".$data_award."";  
$activity_payable_fund  = requestByCURL($url);
/*fill obligate data*/
for($i=0; $i<count($award_ob_fund['data']);$i++){
	$byear = $award_ob_fund['data'][$i]['transaction_year'];
	$eyear = $byear+1;
	
	if($byear>0){
		$disp_label = $byear.'-'.substr($eyear,-2,2);
		$FY_year_arr[$byear]['fiscal_year']= $disp_label;
		$FY_year_arr[$byear]['b_year']= $byear;
		$FY_year_arr[$byear]['obligated']=$FY_year_arr[$byear]['obligated'] + $award_ob_fund['data'][$i]['credit_amount'];
		$FY_year_arr[$byear]['committed']= 0;
		$FY_year_arr[$byear]['spend']= 0;
		$FY_year_arr[$byear]['forecast']= 0;
	}
}

/*fill commited data*/
for($i=0; $i<count($activity_cm_fund['data']);$i++){
	$byear = $activity_cm_fund['data'][$i]['transaction_year'];
	$eyear = $byear+1;
	if($byear>0){
		$disp_label = $byear.'-'.substr($eyear,-2,2);
		$FY_year_arr[$byear]['fiscal_year']= $disp_label;
		$FY_year_arr[$byear]['b_year']= $byear;
		$FY_year_arr[$byear]['committed']= $FY_year_arr[$byear]['committed'] + $activity_cm_fund['data'][$i]['credit_amount'];
		if(!array_key_exists('obligated',$FY_year_arr[$byear])){
			$FY_year_arr[$byear]['obligated']= 0;
		}
		$FY_year_arr[$byear]['forecast']= 0;
	}
}

/*fill spend data*/
for($i=0; $i<count($activity_payable_fund['data']);$i++){
	$invoice_date = date('Y-m-d',strtotime($activity_payable_fund['data'][$i]['invoice_date']."+".PLUS_EXTRA_SPEND_DAYS." days"));
	$actual_year = $byear = date('Y',strtotime($invoice_date));
	$month = date('m',strtotime($invoice_date));
	$quarter = get_quarter($month);
	if($month<10){
		$byear = date('Y',strtotime($invoice_date))-1;
	}
	
	if($byear>0){
		$eyear = $byear+1;
		$disp_label = $byear.'-'.substr($eyear,-2,2);
		$FY_year_arr[$byear]['fiscal_year']= $disp_label;
		$FY_year_arr[$byear]['b_year']= $byear;
		if(!array_key_exists('obligated',$FY_year_arr[$byear])){
			$FY_year_arr[$byear]['spend']= $activity_payable_fund['data'][$i]['total_paid_amount'];
		}
		else{
			$FY_year_arr[$byear]['spend']= $activity_payable_fund['data'][$i]['total_paid_amount'] + $FY_year_arr[$byear]['spend'];
		}
		
		if(!array_key_exists('obligated',$FY_year_arr[$byear])){
			$FY_year_arr[$byear]['obligated']= 0;
		}
		
		if(!array_key_exists('committed',$FY_year_arr[$byear]) || !array_key_exists('obligated',$FY_year_arr[$byear])){
			$FY_year_arr[$byear]['committed']= 0;
		}
		$FY_year_arr[$byear]['forecast']= 0;
		
		/*fill spend array for monthly data*/
		$disp_arr_label = $actual_year.'-'.$month;
		if(!array_key_exists($disp_arr_label,$FY_finance_monthly)){
			$FY_finance_monthly[$disp_arr_label]['spend'] = $activity_payable_fund['data'][$i]['total_paid_amount'];
		}
		else{
			$FY_finance_monthly[$disp_arr_label]['spend'] = $FY_finance_monthly[$disp_arr_label]['spend'] + $activity_payable_fund['data'][$i]['total_paid_amount'];
		}
		
		/*fill spend array for quarterly data*/
		$disp_arr_qlabel = $actual_year.'-'.$quarter;
		if(!array_key_exists($disp_arr_qlabel,$FY_finance_quarterly)){
			$FY_finance_quarterly[$disp_arr_qlabel]['spend'] = $activity_payable_fund['data'][$i]['total_paid_amount'];
		}
		else{
			$FY_finance_quarterly[$disp_arr_qlabel]['spend'] = $FY_finance_quarterly[$disp_arr_qlabel]['spend'] + $activity_payable_fund['data'][$i]['total_paid_amount'];
		}
	}
}


/*====select forecaster data for this activity===*/
$url = API_HOST_URL_PROJECT."get_project_activity_finance.php?activity_id=".$activity_id."&type=Forecaster";  
$activity_forecaster_finance  = requestByCURL($url);
for($i=0;$i<count($activity_forecaster_finance['data']);$i++){
	$actual_year = $byear =  $activity_forecaster_finance['data'][$i]['finance_year'];
	$month = $activity_forecaster_finance['data'][$i]['finance_month'];
	$quarter = get_quarter($month);
	if($month<10){
		$month= "0".$month; 
	}
	
	if($activity_forecaster_finance['data'][$i]['finance_month']<10){
		$byear = $activity_forecaster_finance['data'][$i]['finance_year']-1;		
	}

	if(array_key_exists($byear,$FY_year_arr)){
		$FY_year_arr[$byear]['forecast'] = $FY_year_arr[$byear]['forecast'] + $activity_forecaster_finance['data'][$i]['total_amount'];
	}
	
	/*fill spend array for monthly data*/
	$disp_arr_label = $actual_year.'-'.$month;
	
	if(!array_key_exists($disp_arr_label,$FY_finance_monthly)){
		$FY_finance_monthly[$disp_arr_label]['forecaster'] = $activity_forecaster_finance['data'][$i]['total_amount'];
	}
	else{
		$FY_finance_monthly[$disp_arr_label]['forecaster'] = $FY_finance_monthly[$disp_arr_label]['forecaster'] + $activity_forecaster_finance['data'][$i]['total_amount'];
	}
	
	/*fill spend array for quarterly data*/
	$disp_arr_qlabel = $actual_year.'-'.$quarter;
	if(!array_key_exists($disp_arr_qlabel,$FY_finance_quarterly)){
		$FY_finance_quarterly[$disp_arr_qlabel]['forecaster'] = $activity_forecaster_finance['data'][$i]['total_amount'];
	}
	else{
		$FY_finance_quarterly[$disp_arr_qlabel]['forecaster'] = $FY_finance_quarterly[$disp_arr_qlabel]['forecaster'] + $activity_forecaster_finance['data'][$i]['total_amount'];
	}
}

/*====select planner data for this activity===*/
$url = API_HOST_URL_PROJECT."get_project_activity_finance.php?activity_id=".$activity_id."&type=Planner";  
$activity_forecaster_finance  = requestByCURL($url);
for($i=0;$i<count($activity_forecaster_finance['data']);$i++){
	$actual_year = $byear =  $activity_forecaster_finance['data'][$i]['finance_year'];
	$month = $activity_forecaster_finance['data'][$i]['finance_month'];
	$quarter = get_quarter($month);
	if($month<10){
		$month= "0".$month; 
	}
	
	
	if($activity_forecaster_finance['data'][$i]['finance_month']<10){
		$byear = $activity_forecaster_finance['data'][$i]['finance_year']-1;		
	}

	if(array_key_exists($byear,$FY_year_arr)){
		$FY_year_arr[$byear]['planner'] = $FY_year_arr[$byear]['planner'] + $activity_forecaster_finance['data'][$i]['total_amount'];
	}
	
	/*fill spend array for monthly data*/
	$disp_arr_label = $actual_year.'-'.$month;
	if(!array_key_exists($disp_arr_label,$FY_finance_monthly)){
		$FY_finance_monthly[$disp_arr_label]['planner'] = $activity_forecaster_finance['data'][$i]['total_amount'];
	}
	else{
		$FY_finance_monthly[$disp_arr_label]['planner'] = $FY_finance_monthly[$disp_arr_label]['planner'] + $activity_forecaster_finance['data'][$i]['total_amount'];
	}
	
	/*fill spend array for quarterly data*/
	$disp_arr_qlabel = $actual_year.'-'.$quarter;
	if(!array_key_exists($disp_arr_qlabel,$FY_finance_quarterly)){
		$FY_finance_quarterly[$disp_arr_qlabel]['planner'] = $activity_forecaster_finance['data'][$i]['total_amount'];
	}
	else{
		$FY_finance_quarterly[$disp_arr_qlabel]['planner'] = $FY_finance_quarterly[$disp_arr_qlabel]['planner'] + $activity_forecaster_finance['data'][$i]['total_amount'];
	}
}


//** sort forecaster array by month and year
ksort($FY_finance_monthly);
ksort($FY_finance_quarterly);

$temp_month_arr = array_keys($FY_finance_monthly);
$temp_data = explode('-',$temp_month_arr[0]);
$start_fyear = $temp_data[0]; 
$start_fmonth = $temp_data[1]; 
$temp_data = explode('-',$temp_month_arr[count($temp_month_arr)-1]);
$end_fyear = $temp_data[0]; 
$end_fmonth = $temp_data[1]; 

$temp_quarter_arr = array_keys($FY_finance_quarterly);
$temp_data = explode('-',$temp_quarter_arr[0]);
$start_fquarter = $temp_data[1]; 
$temp_data = explode('-',$temp_quarter_arr[count($temp_quarter_arr)-1]);
$end_fquarter = $temp_data[1]; 


//*===fill month array data===*//
if($start_fyear>0 && $end_fyear>0){
	for($i=$start_fyear;$i<=$end_fyear;$i++){
		for($j=$start_fmonth;$j<=$end_fmonth;$j++){
		$disp_label= $i.'-'.$j;
		$quarter = get_quarter($j); 
		$disp_qlabel = $i.'-'.$quarter; 
			
			//if no data found for this month year in this forecaster than set 0//
			if(!array_key_exists($disp_label,$FY_finance_monthly) && (($i==$start_fyear && $j>$start_fmonth) || ($i==$end_fyear && $j<$end_fmonth) || ($i>$start_fyear && $i<$end_fyear))){
				$FY_finance_monthly[$disp_label]['spend']= 0;	
				$FY_finance_monthly[$disp_label]['forecaster']= 0;
				$FY_finance_monthly[$disp_label]['planner']= 0;
				
			}
			else if(($i==$start_fyear && $j>$start_fmonth) || ($i==$end_fyear && $j<$end_fmonth) || ($i>$start_fyear && $i<$end_fyear)){
				if(!array_key_exists('spend',$FY_finance_monthly[$disp_label])){
					$FY_finance_monthly[$disp_label]['spend']= 0;	
				}
				
				if(!array_key_exists('forecaster',$FY_finance_monthly[$disp_label])){
					$FY_finance_monthly[$disp_label]['forecaster']= 0;	
				}
				
				if(!array_key_exists('planner',$FY_finance_monthly[$disp_label])){
					$FY_finance_monthly[$disp_label]['planner']= 0;	
				}
			}
			
			//if no data found for this month year in this forecaster than set 0//
			if(!array_key_exists($disp_qlabel,$FY_finance_quarterly) && (($i==$start_fyear && $quarter>=$start_fquarter) || ($i==$end_fyear && $quarter<=$end_fquarter) || ($i>$start_fyear && $i<$end_fyear))){
				$FY_finance_quarterly[$disp_qlabel]['spend']= 0;	
				$FY_finance_quarterly[$disp_qlabel]['forecaster']= 0;
				$FY_finance_quarterly[$disp_qlabel]['planner']= 0;
				
			}
			else if(($i==$start_fyear &&  $quarter>=$start_fquarter) || ($i==$end_fyear && $quarter<=$end_fquarter) || ($i>$start_fyear && $i<$end_fyear)){
				if(!array_key_exists('spend',$FY_finance_quarterly[$disp_qlabel])){
					$FY_finance_quarterly[$disp_qlabel]['spend']= 0;	
				}
				
				if(!array_key_exists('forecaster',$FY_finance_quarterly[$disp_qlabel])){
					$FY_finance_quarterly[$disp_qlabel]['forecaster']= 0;	
				}
				
				if(!array_key_exists('planner',$FY_finance_quarterly[$disp_qlabel])){
					$FY_finance_quarterly[$disp_qlabel]['planner']= 0;	
				}
			}
		}
	}
}
//** sort forecaster array by month and year
ksort($FY_finance_monthly);
ksort($FY_finance_quarterly);

/*===sort finance array by year===*/
ksort($FY_year_arr);

$month = date('m');
if($month<10){
	$current_year_index = (date('Y')-1); 
}
else{
	$current_year_index = date('Y'); 
}

if(!array_key_exists($current_year_index,$FY_year_arr)){
	$current_year_index = key($FY_year_arr);
}

$finance_arr = $FY_year_arr; 
$total_budget=0;
$total_spend=0; 
$total_forecast=0;


$total_budget = array_sum(array_map(create_function('$finance_arr', 'return $finance_arr["obligated"];'), $FY_year_arr));
$total_spend = array_sum(array_map(create_function('$finance_arr', 'return $finance_arr["spend"];'), $FY_year_arr));
$total_forecast = array_sum(array_map(create_function('$finance_arr', 'return $finance_arr["forecast"];'), $FY_year_arr));

?> 
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" type="image/ico" href="img/favicon.ico"/>
<title>USAID-AMP</title>
<?php include('includes/resources.php');?>
</head>
<body class="page-ui-components" style="position:relative">
	<!--header start-->
	<?php include('includes/header.php');?>
    <!--header end-->
    <!--main container start-->
	<div class="main-container container-fluid" id="main-content">
		<?php include('includes/activity_header.php');?>
		<div class="extra_ht"></div><div class="extra_ht"></div>
		<input type="hidden" value="<?php echo $activity_start_date;?>" id="activity_start_date"/>
		<input type="hidden" value="<?php echo $activity_end_date;?>" id="activity_end_date"/>
		<!--main container blk start-->
		<div class="tbl-block">
			<div class="tbl-caption">
				<div class="tbl-content-head"><?php if(!isset($edit_mode)){?>Project Activity Finance<?php } ?></div>
				<div class="clear"></div>
			</div>
			<div class="project-detail-blk table-container">
				<!--review display only block start-->
				<div class="form-blk">
					
					<div class="row project-detail-textarea">
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="finance_dashboard bdbudget">
								<div class="head">
									<?php echo '$'.number_format($total_budget);?>
								</div>
								<div class="sub-head">Lifetime Budget
								<input type="hidden" class="total_budget_js" value="<?php echo $total_budget;?>"></div>
							</div>	
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="finance_dashboard bdspend">
								<div class="head">
									<?php echo '$'.number_format($total_spend);?>
								</div>
								<div class="sub-head">Lifetime Actual Spend</div>
							</div>	
						</div>
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="finance_dashboard bdforecast">
								<div class="head">
									<?php echo '$'.number_format($total_forecast);?>
								</div>
								<div class="sub-head">Lifetime Forecast</div>
							</div>	
						</div>
					</div>
					<div class="extra_ht"></div>
					<header><h2 class="form-blk-head">Finance Profile</h2></header>
					<div class="row project-detail-textarea">
						<!-- line chart canvas element -->
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<button id="view_bar_chart" type="button" class="viewchart_btn usa-button-hover">View as bar chart</button>
							<button id="view_line_chart" type="button" class="viewchart_btn">View as line chart</button>
							<button id="view_hybrid_chart" type="button" class="viewchart_btn">View as hybrid chart</button>
							<div id="mode_blk">
							<div class="bold">Choose One</div>
							<div class="extra_ht"></div>
							<div><input type="radio"  value="monthly" checked="checked" name="mode"/><label> Month</label></div>
							<div class="extra_ht"></div>
							<div><input type="radio" value="quarterly" name="mode"/><label> Quarter</label></div>
							</div>
							<div class="clear extra_ht"></div><div class="extra_ht"></div>
							<div style="overflow:scroll; overflow-y:hidden">
							<div style="width:300%;">
								<div class="barchart_blk chart_blk">
									<div id="barchart_container" class="chart_cont"></div>
								</div>
								<div class="linechart_blk chart_blk disp-none">
									<div id="linechart_container" class="chart_cont"></div>
								</div>
								<div class="hybridchart_blk chart_blk disp-none">
									<div id="hybridchart_container" class="chart_cont"></div>
								</div>
							</div>
							</div>
						</div>
					</div>
					<header><h3 class="form-blk-head">Finance Details</h3></header>
					<button id="toggle_years_data" type="button" class="usa-button-hover">Show All Years</button>
					<button id="open_forecaster_popup_btn" type="button">Open Forecaster</button>
					<button id="open_planner_popup_btn" type="button">Open Planner</button>
					
					<input type="hidden" id="show_years_data_flag" value="no"/>
					<div class="row project-detail-textarea">
						<!-- line chart canvas element -->
						<div class="review-listing col-lg-12 col-md-12 col-sm-12 col-xs-12 project_activity_finance_details">
							<div>
								<div>
									<table id="projects_finance_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
									<tr class="head">
										<td class="sm_wdth">Year</td>
										<td class="sm_wdth">Committed</td>
										<td class="comm_wdth">Obligated</td>
										<td class="lg_wdth">Actual Spend</td>
										<td class="comm_wdth">Available</td>
										<td class="comm_wdth">Forecast</td>
										<td class="comm_wdth">Buttons</td>
									</tr>
									</thead>
									<?php 
									##==if any finance related info exists==
									if(count($finance_arr)>0){?>
										<tfoot>
										<tr class="head">
											<td class="sm_wdth">Total</td>
											<td class="sm_wdth committed_data">$<?php echo number_format($finance_arr[$current_year_index]['committed']);?>
												<input type="hidden" value="<?php echo $finance_arr[$current_year_index]['committed'];?>"  class="value"/>
											</td>
											<td class="comm_wdth obligated_data">$<?php echo number_format($finance_arr[$current_year_index]['obligated']);?>
												<input type="hidden" value="<?php echo $finance_arr[$current_year_index]['obligated'];?>"  class="value"/>
											</td>
											<td class="lg_wdth actual_spend_data">$<?php echo number_format($finance_arr[$current_year_index]['spend']);?>
												<input type="hidden" value="<?php echo $finance_arr[$current_year_index]['spend'];?>"  class="value"/>
											</td>
											<?php $avail_val = $finance_arr[$current_year_index]['obligated'] - $finance_arr[$current_year_index]['spend'];?>
											<td class="comm_wdth available_data">$<?php echo number_format($avail_val);?>
												<input type="hidden" value="<?php echo $avail_val;?>"  class="value"/>
											</td>
											<td class="comm_wdth forecast_data">$<?php echo number_format($finance_arr[$current_year_index]['forecast']);?>
												<input type="hidden" value="<?php echo $finance_arr[$current_year_index]['forecast'];?>"  class="value"/>
											</td>
											<td class="comm_wdth "></td>
										</tr>
										</tfoot>
										<tbody>
										<?php 
										$i=0;
										
										foreach($finance_arr as $key => $obj){
											$avail_val= $finance_arr[$key]['obligated'] - $finance_arr[$key]['spend'];
										?>
										<tr class="data_row  <?php if($finance_arr[$key]['b_year']!=$current_year_index) {?> disp-none other_year <?php } else {?> current_year <?php }?>">
											<td class="sm_wdth">
												<?php echo $finance_arr[$key]['fiscal_year'];?>
												<input type="hidden" value="<?php echo $finance_arr[$key]['b_year'];?>" class="finance_year"/>
											</td>
											<td class="comm_wdth committed_data">$<span><?php echo number_format($finance_arr[$key]['committed']);?></span>
												<input type="hidden" value="<?php echo $finance_arr[$key]['committed'];?>"  class="value"/>
											</td>
											<td class="sm_wdth obligated_data">$<span><?php echo number_format($finance_arr[$key]['obligated']);?></span>
												<input type="hidden" value="<?php echo $finance_arr[$key]['obligated'];?>"  class="value"/>
											</td>
											<td class="lg_wdth actual_spend_data">$<span><?php echo number_format($finance_arr[$key]['spend']);?></span>
												<input type="hidden" value="<?php echo $finance_arr[$key]['spend'];?>"  class="value"/>
											</td>
											<td class="comm_wdth available_data">$<span><?php echo number_format($avail_val);?></span>
												<input type="hidden" value="<?php echo $avail_val;?>"  class="value"/>
											</td>
											<td class="comm_wdth forecast_data">$<span><?php echo number_format($finance_arr[$key]['forecast']);?></span>
												<input type="hidden" value="<?php echo $finance_arr[$key]['forecast'];?>"  class="value"/>
											</td>
											<td class="comm_wdth common_btn">
												<a class="btn btn-gray btn-sm openf_indiv_popup <?php if($i!=0){?> disp-none1 <?php }?>">Forecaster</a> <a class="btn btn-gray btn-sm openp_indiv_popup <?php if($i!=0){?> disp-none1 <?php }?>">Planner</a>
											</td>
										</tr>
										<?php $i++;}?>
										</tbody>
									<?php } else {?>
										<tbody>
											<tr class="data_row">
												<td colspan="7" class="text-danger bold">No data exists</td>
											</tr>
										</tbody>
									<?php }?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
	    </div>
	  	<!--main container blk end-->
	</div>
	<div id="open_forecaster_popup" style="background:url('<?php echo HOST_URL?>img/light.png');">
		<form id="open_forecaster_form">
			<input type="hidden" value="<?php echo $activity_id;?>" class="popup_activity" name="activity_id"/>
			<div class="inner_cont">
			<div class="popup_header">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<header><h2 class="heading text-danger">Forecaster</h2></header>
						<input type="hidden" value="save_finance" class="save_finance" name="save_finance"/>
						<input type="hidden" value="Forecaster" class="popup_type" name="finance_type"/>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div style="float:right;margin-top:10px;font-size:30px;cursor:pointer;"><a class="close_btn text-danger" title="close"><i class="fa fa-times-circle" aria-hidden="true"></i></a></div><div class="extra_ht"></div>
					</div>
				</div>
			</div>
			<div class="popup_datacont">
				<div class="row" style="font-size:18px">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 total_budget">
					<header><h3>Total Budget : <span class="disp_value"></span><input type="hidden" value="" class="value"/></h3></header>
				</div>
				<div class="extra_ht clear"></div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 fiscal_year_start">
					<span class="text-success"></span> <span class="value"></span>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 fiscal_year_end">
					<span class="text-success"></span> <span class="value"></span>
				</div>
				</div>
			</div>
			<div class="extra_ht"></div><div class="extra_ht"></div><div class="extra_ht"></div><div class="extra_ht"></div>
			<div style="max-height:550px; overflow-y:auto; overflow-x:hidden">
				<div class="row data_cont">
					
				</div>
			</div>
			<div class="popup_footer">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="form-msg text-left"></div>
					<div class="extra_ht"></div>
					<div><a class="btn btn-green" id="save_popup_data">Save</a></div>
				</div>
			</div>
			</div>
		</div>
		</form>
	</div>
	<!--main container end-->
	<?php include('includes/footer.php');?>
<script src="<?php echo HOST_URL?>js/main.js"></script>
<script>
	var month_obligate_data = new Array();
	var month_spend_data = new Array();
	var month_forecaster_data = new Array();
	var month_finance_label_arr = new Array();
	
	var quarter_obligate_data = new Array();
	var quarter_spend_data = new Array();
	var quarter_forecaster_data = new Array();
	var quarter_finance_label_arr = new Array();
	
	
	var month_arr = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
	setTimeout(function(){
		$('#submission_msg').html("");
	},10000);	
</script>
<script src="<?php echo HOST_URL?>js/exporting.js"></script>
<script src="<?php echo HOST_URL?>js/highcharts.js"></script>
<?php if(count($FY_finance_monthly)>0){
	foreach($FY_finance_monthly as $key => $obj){
	$tmp_arr = explode("-",$key);
		if($tmp_arr[1]>=10)
	       $month_name = substr($key,strlen($key)-2);
        else
	       $month_name = substr($key,strlen($key)-1);
	$two_wrd_year = substr($key,2,2);
	
?>
<script>
	month_obligate_data.push(parseInt("<?php echo $obj['planner'];?>"));
	month_spend_data.push(parseInt("<?php echo $obj['spend'];?>"));
	month_forecaster_data.push(parseInt("<?php echo $obj['forecaster'];?>"));
	var temp =parseInt("<?php echo $month_name;?>")-1;
	month_finance_label_arr.push(month_arr[temp]+" <?php echo $two_wrd_year;?>");
</script>
<?php }}?>
<?php if(count($FY_finance_quarterly)>0){
	foreach($FY_finance_quarterly as $key => $obj){
	$quarter_name = substr($key,strlen($key)-1);
	$two_wrd_year = substr($key,2,2);
?>
<script>
	quarter_obligate_data.push(parseInt("<?php echo $obj['planner'];?>"));
	quarter_spend_data.push(parseInt("<?php echo $obj['spend'];?>"));
	quarter_forecaster_data.push(parseInt("<?php echo $obj['forecaster'];?>"));
	var temp =parseInt("<?php echo $quarter_name;?>");
	quarter_finance_label_arr.push('Q'+temp+" <?php echo $two_wrd_year;?>");
</script>
<?php }}?>
<script type="text/javascript">
var disp_month_arr = new Array();
var disp_quarter_arr = new Array();

$(function () {
	Highcharts.setOptions({
		lang: {
			thousandsSep: ','
		}
	});
	Highcharts.setOptions({
    	colors: ['#8C0001','#ccc','#084A08']
	});
	
	var bar_chart;
	bar_chart = new Highcharts.Chart({
		chart: {
            zoomType : false,
			renderTo: 'barchart_container',
            type: 'column'
        },
		title: {
            text: ''
        },
		yAxis: [{
			title: {
                text: ' ',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
			labels: {
				format: '{value:,.0f}'
			},
			tickInterval: 10000,
			min: 0,
    		max: $('.total_budget_js').val()
		}],
		
        xAxis: {
            categories: month_finance_label_arr,
			crosshair: true
        },
		 series: [{
            type: 'column',
            name: 'Budget',
            data: month_obligate_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Forecast',
            data: month_forecaster_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Spend',
            data: month_spend_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }],
		legend: {
            layout: 'vertical',
            align: 'left',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
	
	});
	
	/* UPDATE value anytime
	var hybrid_chart = $('#hybridchart_container').highcharts();
	hybrid_chart.series[0].setData([400000, 550000, 400000, 580000, 350000, 450000, 950000, 450000, 300000, 200000, 100000, 55000]);*/
	
	/*====LINECHART======*/
	var line_chart;
	line_chart = new Highcharts.Chart({
		chart: {
            zoomType : false,
			renderTo: 'linechart_container',
            type: 'spline'
        },
		title: {
            text: ''
        },
        xAxis: {
            categories: month_finance_label_arr,
			crosshair: true
        },
		yAxis: [{
			title: {
                text: ' ',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
			labels: {
				format: '{value:,.0f}'
			},
			tickInterval: 10000,
			min: 0,
    		max: $('.total_budget_js').val()
		}],
        plotOptions: {
            series: {
                colorByPoint: false
            }
        },
		   series: [{
            type: 'spline',
            name: 'Budget',
           data: month_obligate_data,
		   maxPointWidth: 50,
		   tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'spline',
            name: 'Forecast',
            data: month_forecaster_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
             type: 'spline',
             name: 'Spend',
             data: month_spend_data,
			 maxPointWidth: 50,
			 tooltip: {
                valuePrefix: '$'
            }
        }],
		legend: {
            layout: 'vertical',
            align: 'Spend',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
	
	});
	
	
	/*====HYBRIDCHART =====*/
	
	var hybrid_chart;
	hybrid_chart = new Highcharts.Chart({
		chart: {
            zoomType : false,
			renderTo: 'hybridchart_container',
            type: 'column',
        },
		title: {
            text: ''
        },
        xAxis: {
            categories: month_finance_label_arr,
			crosshair: true
        },
		yAxis: [{
			opposite: true,
			title: {
                text: ' ',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
			labels: {
				format: '{value:,.0f}'
			},
			tickInterval: 10000,
			min: 0,
    		max: $('.total_budget_js').val()
           
		}, // Primary yAx
		{ 
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
			labels: {
				format: '{value:,.0f}'
			},
			tickInterval: 10000,
			min: 0,
    		max: $('.total_budget_js').val()

        }, { // Tertiary yAxis
            gridLineWidth: 0,
            title: {
                text: '',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            labels: {
                format: '{value}',
                style: {
                    color: Highcharts.getOptions().colors[2]
                }
            },
            opposite: true,
		
        },
		 { // Tertiary yAxis
			gridLineWidth: 0,
			title: {
				text: '',
				style: {
					color: Highcharts.getOptions().colors[2]
				}
			},
			labels: {
				format: '{value}',
				style: {
					color: Highcharts.getOptions().colors[2]
				}
			},
			opposite: true,
	     }
		
		],
        plotOptions: {
            series: {
                colorByPoint: false
            }
        },
		 series: [{
            type: 'column',
            name: 'Budget',
            data: month_obligate_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Forecast',
            data: month_forecaster_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'column',
            name: 'Spend',
            data: month_spend_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        },
		 {
            type: 'spline',
            name: 'Budget',
            data: month_obligate_data,
		    maxPointWidth: 50,
		   tooltip: {
                valuePrefix: '$'
            }
        }, {
            type: 'spline',
            name: 'Forecast',
            data: month_forecaster_data,
			maxPointWidth: 50,
			tooltip: {
                valuePrefix: '$'
            }
        }, {
             type: 'spline',
             name: 'Spend',
             data: month_spend_data,
			 maxPointWidth: 50,
			 tooltip: {
                valuePrefix: '$'
            }
        }],
		legend: {
            layout: 'vertical',
            align: 'left',
            x: 80,
            verticalAlign: 'top',
            y: 55,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
	
	});
});

$('#view_hybrid_chart').click(function(){
	$('.chart_blk').addClass('disp-none');
	$('.viewchart_btn').removeClass('btn-green').addClass('btn-blue');
	$(this).removeClass('btn-blue').addClass('btn-green');
	$('.hybridchart_blk').removeClass('disp-none');
});

$('#view_line_chart').click(function(){
	$('.chart_blk').addClass('disp-none');
	$('.viewchart_btn').removeClass('btn-green').addClass('btn-blue');
	$(this).removeClass('btn-blue').addClass('btn-green');
	$('.linechart_blk').removeClass('disp-none');
});

$('#view_bar_chart').click(function(){
	$('.chart_blk').addClass('disp-none');
	$('.viewchart_btn').removeClass('btn-green').addClass('btn-blue');
	$(this).removeClass('btn-blue').addClass('btn-green');
	$('.barchart_blk').removeClass('disp-none');
});
$('.chart_cont').width($('.barchart_blk .chart_cont').width());
//chart.series[0].data[[35.00,35.91,36.82,37.73,38.64]]; 

/*====toggle_years_data====*/
$('#toggle_years_data').click(function(){
	var hide_row = $('.other_year').filter(function(){
		return $(this).hasClass('disp-none');
	}).length;
	
	var elem_class=".data_row";
	if(hide_row>0){
		$(this).text("Hide All Years");
		$('#show_years_data_flag').val("yes");
		$('.openf_indiv_popup').removeClass('disp-none');
		$('.openp_indiv_popup').removeClass('disp-none');
	}
	else{
		var elem_class=".data_row.current_year";
		$(this).text("Show All Years");
		$('#show_years_data_flag').val("no");
		//$('.openf_indiv_popup').addClass('disp-none');
		//$('.openp_indiv_popup').addClass('disp-none');
	}
	
	var total_committed = sum_val(get_all_col_data($(elem_class).find('.committed_data')));
	var total_obligated = sum_val(get_all_col_data($(elem_class).find('.obligated_data')));
	var total_spent = sum_val(get_all_col_data($(elem_class).find('.actual_spend_data')));
	var total_available = sum_val(get_all_col_data($(elem_class).find('.available_data')));
	var total_forecast = sum_val(get_all_col_data($(elem_class).find('.forecast_data')));
	
	set_col_data('committed_data',total_committed);
	set_col_data('obligated_data',total_obligated);
	set_col_data('actual_spend_data',total_spent);
	set_col_data('available_data',total_available);
	set_col_data('forecast_data',total_forecast);
		
	$('.other_year').toggleClass('disp-none');
});

function get_all_col_data(elem){
	var arr = new Array();
	$(elem).each(function(index, el){
		var text = $(el).find('.value').val();
		text = text.replace(/,/g,'');
		arr.push(text);	
	});
	return arr;
}

function set_col_data(elem,val){
	$('#projects_finance_table tfoot').find('.'+elem).text('$'+Number(val).toLocaleString());
	$('#projects_finance_table tfoot').find('.'+elem+' .value').val(Number(val));
}

function sum_val(arr){
	var total = 0;
	$(arr).each(function(key, val){
		total = total + Number(val);
	});
	
	return total;
}


$('#open_forecaster_popup_btn').click(function(){
	var show_year_flag= $('#show_years_data_flag').val();
	var total_rows_disp = $('#projects_finance_table .data_row.disp-none').length;
	if(total_rows_disp!=0){
		var selected_year = $('#projects_finance_table').find('.data_row.current_year');
		show_popup($('#show_years_data_flag').val(),selected_year);
	}
	else{
		show_popup("yes");
	}
});

function get_dates_from_fiscal_year(act_start_date, act_end_date, finance_year, mode){
	var date_arr = new Array();
	var quarter_arr = new Array();
	quarter_arr[12] = "Q1";
	quarter_arr[3] = "Q2";
	quarter_arr[6] = "Q3";
	quarter_arr[9] = "Q4";
	var month_temp_arr = new Array();
	var year_diff = 0;
	var year_temp = 0;
	
	var act_start_date = act_start_date.split("/");
	var temp_act_start_date = new Date(Number(act_start_date[2]),Number(act_start_date[0])-1,1);
	
	
	var act_end_date = act_end_date.split("/");                                                                                                                                                            
	var temp_act_end_date = new Date(Number(act_end_date[2]),Number(act_end_date[0])-1,1);
	
	$(finance_year).each(function(index, year_val){ /*loop in all selected year*/
		var start_fiscal_year = Number(year_val); /*starting fiscal year of this year*/
		var start_fiscal_date = new Date(start_fiscal_year,9,1); /*starting fiscal year date of this year*/
		
		var end_fiscal_year = Number(year_val)+1; /*ending fiscal year of this year*/
		var end_fiscal_date = new Date(end_fiscal_year,8,30); /*ending fiscal year date of this year*/
		
		year_diff = Number(end_fiscal_year) - start_fiscal_year + 1; /*difference of years*/
		
		year_temp = start_fiscal_year;
			
		if(mode=="monthly"){ /*if mode is monthly*/
			for(var i=0; i < year_diff; i++){
				$(month_arr).each(function(month_in, month_name){
					
					var temp_date = new Date(year_temp,month_in,1);
					
					var disbled_attr = '';
					if(temp_date>=start_fiscal_date && temp_date<end_fiscal_date){
						if(temp_act_start_date>temp_date ||  temp_date>temp_act_end_date){
							/*fill month array*/
							//disp_month_arr.push(month_name); 
							/*disable month*/
							disbled_attr = "disabled";
						}
						/*if current date is lie between starting and ending fiscal year than add month objct*/
						var obj = new Object({'label':month_name,'year':year_temp,'disabled':disbled_attr});
						month_temp_arr.push(obj);								
					}
				});
				year_temp = year_temp +1;
			}
		}
	});
	
	return month_temp_arr;
}


/*===show popup===*/
function show_popup(year_flag,selected_year){
	$('body').scrollTop(0);
	//$('#open_forecaster_popup').find('.heading').text('Forecaster');
	//$('#open_forecaster_popup').find('.popup_type').val('Forecaster');
	var act_start_date = $('#activity_start_date').val();
	var act_end_date = $('#activity_end_date').val();
	var activity_id = $('#open_forecaster_popup').find('.popup_activity').val();
	var finance_type = $('#open_forecaster_popup').find('.popup_type').val(); 

	var mode = $('#mode_blk').find('input[name="mode"]:checked').val();
	var finance_year = new Array();
	var total_budget = 0;

	$('#open_forecaster_popup').find('.fiscal_year_start').find('.text-success').text("");
	$('#open_forecaster_popup').find('.fiscal_year_start').find('.value').text("");
	$('#open_forecaster_popup').find('.fiscal_year_end').find('.text-success').text("");
	$('#open_forecaster_popup').find('.fiscal_year_end').find('.value').text("");
		
	if(year_flag=="no"){
		finance_year.push($(selected_year).find('.finance_year').val());
		$('#open_forecaster_popup').find('.fiscal_year_start').find('.text-success').text("Fiscal Year :");
		$('#open_forecaster_popup').find('.fiscal_year_start').find('.value').text(finance_year[0]);
		total_budget = Number($(selected_year).find('.obligated_data .value').val());
		$('#open_forecaster_popup').find('.popup_datacont .total_budget').find('.value').val(total_budget);
		$('#open_forecaster_popup').find('.popup_datacont .total_budget').find('.disp_value').text('$'+total_budget.toLocaleString());
	}
	else{
		$('#projects_finance_table').find('.data_row').each(function(index, elem){
			var temp_year = $(elem).find('.finance_year').val();
			finance_year.push(temp_year);
			total_budget = total_budget + Number($(elem).find('.obligated_data .value').val());
		});
		
		$('#open_forecaster_popup').find('.fiscal_year_start').find('.text-success').text("Activity Start Date :");
		$('#open_forecaster_popup').find('.fiscal_year_start').find('.value').text(act_start_date);
		$('#open_forecaster_popup').find('.fiscal_year_end').find('.text-success').text("Activity End Date :");
		$('#open_forecaster_popup').find('.fiscal_year_end').find('.value').text(act_end_date);
		$('#open_forecaster_popup').find('.popup_datacont .total_budget').find('.value').val(total_budget);
		$('#open_forecaster_popup').find('.popup_datacont .total_budget').find('.disp_value').text('$'+total_budget.toLocaleString());
	}
	$('#open_forecaster_popup').find('.data_cont').html("");
	var activity_start_date = $('#activity_start_date').val();
	var activity_end_date = $('#activity_end_date').val();
	
	if(mode=="monthly"){	
		var monthly_date = get_dates_from_fiscal_year(activity_start_date,activity_end_date,finance_year,mode);
		
		for(var key in monthly_date ) {
			$(monthly_date[key]).each(function(i,a){
				var disabled_attr ='';
				var popup_budget_ip = 'popup_budget_ip';
				var month_index = month_arr.indexOf(monthly_date[key]['label'])+1;
				if(monthly_date[key]['disabled']=="disabled"){
					disabled_attr ="disabled='disabled'";
					popup_budget_ip = "";
				}
				if(disabled_attr==''){
					var html = '<div class="data_row col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="row"><div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 label_name bold text-right"><input type="hidden" value="'+month_index+'" class="finance_month" name="finance_month[]"/><input type="hidden" value="'+monthly_date[key]['year']+'" class="finance_year" name="finance_year[]"/>'+monthly_date[key]['label']+' '+monthly_date[key]['year']+'</div><div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ip-blk"><input type="text" class="form-control formatted_amount finance_value finance_amt '+popup_budget_ip+'" '+disabled_attr+' name="finance_value[]"/></div><div class="extra_ht clear"></div></div></div>';
				}
				else{
					var html = '<div class="data_row col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="row"><div class="col-lg-3 col-md-3 col-sm-3 col-xs-4 label_name bold text-right">'+monthly_date[key]['label']+' '+monthly_date[key]['year']+'</div><div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ip-blk"><input type="text" class="form-control finance_amt disabled '+popup_budget_ip+'" '+disabled_attr+' /></div><div class="extra_ht clear"></div></div></div>';
				}
				$('#open_forecaster_popup').find('.data_cont').append(html); 
			});
  		}
		
		//===fill popup===//
		$.ajax({
			url:'ajaxfiles/get_project_activity_finance.php',
			type:'POST',
			data: {get_finance:'get_finance', activity_id:activity_id, finance_year:finance_year, finance_type:finance_type},
			success:function(data){
				var data = JSON.parse(data);
				var fin_arr = data['data'];
				if(data['msg_type']=="Success" && Object.keys(data['data']).length>0){
					$('#open_forecaster_popup').find('.data_cont .data_row').each(function(index, elem){
						//===fill input which are not disabled===//
						if(!$(elem).find('.finance_amt').hasClass('disabled')){
							var month_label = $(elem).find('.finance_month').val();
							var year_label = $(elem).find('.finance_year').val();
							var disp_label = year_label+'-'+month_label;
							if(typeof fin_arr[disp_label] !== "undefined"){
								$(elem).find('.finance_amt').val(fin_arr[disp_label]['finance_value']);
							}
						}
					});
				}
			}
		});
		
		$('#open_forecaster_popup').css({display:'block'});
	}
}

/*close popup*/
$('#open_forecaster_popup .close_btn').click(function(){
	$('#open_forecaster_popup').find('.heading').text('Forecaster');
	$('#open_forecaster_popup').find('.popup_type').val('Forecaster');
	$('#open_forecaster_popup').css({'display':'none'});
	$('#open_forecaster_popup .form-msg').removeClass('error');
	$('#open_forecaster_popup').find('.form-msg').addClass('disp-none');
});

/*open popup*/
$('#open_planner_popup_btn').click(function(){
	$('#open_forecaster_popup').find('.heading').text('Planner');
	$('#open_forecaster_popup').find('.popup_type').val('Planner');
	$('#open_forecaster_popup_btn').trigger('click');	
});

/*===save popupdata===*/
$('#save_popup_data').click(function(){
 	var total_budget = 0;
	var invalid_ip = $('#open_forecaster_popup').find('.invalid_ip').length;
	var actual_total_budget = Number($('#open_forecaster_popup').find('.popup_datacont .total_budget').find('.value').val());
	var popup_type= $('#open_forecaster_popup').find('.heading').text();
	$('#open_forecaster_popup').find('.data_cont .popup_budget_ip').each(function(index, elem){
		var fin_val = $(elem).val();
		fin_val =  fin_val.replace(/,/g, '');
		fin_val =  fin_val.replace('$', '');
		total_budget = total_budget + Number(fin_val);
	});
	
	var error = "";
	var error_msg = "";
	
	if(total_budget==0){
		error ="error";	
		error_msg = "Please enter budget of aleast one timestamp";
	}
	else if(actual_total_budget < total_budget && popup_type=="Planner"){
		error ="error";	
		error_msg = "Total Budget can't be greater than actual budget";
	}
	else if(invalid_ip>0){
		error ="error";	
		error_msg = "Something went wrong...";
	}
	else{
		var form_data = $('#open_forecaster_form').serialize();  
		$('#open_forecaster_form').find('.finance_amt').attr('disabled','disabled');  
		$.ajax({
			url:'ajaxfiles/manage_project_activity_finance.php',
			type:'POST',
			data:form_data,
			success: function(data){
				var data = JSON.parse(data);
				if(data['msg_type']=='Error'){
					$('#open_forecaster_popup').find('.form-msg').text(data['msg']);	
					$('#open_forecaster_popup').find('.form-msg').removeClass('disp-none').addClass('error');
				}
				else{
					$('#open_forecaster_popup').find('.form-msg').removeClass('disp-none').addClass('success');
					$('#open_forecaster_popup').find('.form-msg').text(data['msg']);
					setTimeout(function(){
						window.location ="";
					},500);	
				}
			}
		});
	}	
		
	if(error!=""){
		$('#open_forecaster_popup').find('.form-msg').text(error_msg);	
		$('#open_forecaster_popup').find('.form-msg').removeClass('disp-none').addClass('error');
	}
	else{
		$('#open_forecaster_popup').find('.form-msg').addClass('disp-none').removeClass('error');
		$('#open_forecaster_popup').find('.form-msg').text("");	
	}

});


/*===validate ip===*/
$(document).on('keyup','.popup_budget_ip', function(){
	$(this).validate_ip();
});

$('.openf_indiv_popup').click(function(){
	$('#show_years_data_flag').val("no");
	var show_year_flag= $('#show_years_data_flag').val();
	$('#open_forecaster_popup').find('.heading').text('Forecaster');
	$('#open_forecaster_popup').find('.popup_type').val('Forecaster');	
	if(show_year_flag=="no"){
		var selected_year = $(this).closest('.data_row');
		show_popup($('#show_years_data_flag').val(),selected_year);
	}
	else{
		show_popup($('#show_years_data_flag').val());
	}	
});

$('.openp_indiv_popup').click(function(){
	$('#show_years_data_flag').val("no");
	var show_year_flag= $('#show_years_data_flag').val();
	$('#open_forecaster_popup').find('.heading').text('Planner');
	$('#open_forecaster_popup').find('.popup_type').val('Planner');	
	if(show_year_flag=="no"){
		var selected_year = $(this).closest('.data_row');
		show_popup($('#show_years_data_flag').val(),selected_year);
	}
	else{
		show_popup($('#show_years_data_flag').val());
	}
});

/*radio btn click*/
$('#mode_blk input[name="mode"]').click(function(){
	var val = $(this).val();
	disp_quarter_arr = ["Q1","Q2","Q3","Q4"];
	disp_month_arr = ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep'];
	var bar_chart = $('#barchart_container').highcharts();
	var line_chart = $('#linechart_container').highcharts();
	var hybrid_chart = $('#hybridchart_container').highcharts();
	if(val=="quarterly"){
		/*update bar chart*/
		bar_chart.xAxis[0].setCategories(quarter_finance_label_arr);
		bar_chart.series[0].setData(quarter_obligate_data);
		bar_chart.series[1].setData(quarter_forecaster_data);
		bar_chart.series[2].setData(quarter_spend_data);
	
		/*update line chart*/
		line_chart.xAxis[0].setCategories(quarter_finance_label_arr);
		line_chart.series[0].setData(quarter_obligate_data);
		line_chart.series[1].setData(quarter_forecaster_data);
		line_chart.series[2].setData(quarter_spend_data);
		
		/*update hybrid chart*/
		hybrid_chart.xAxis[0].setCategories(quarter_finance_label_arr);
		hybrid_chart.series[0].setData(quarter_obligate_data);
		hybrid_chart.series[1].setData(quarter_forecaster_data);
		hybrid_chart.series[2].setData(quarter_spend_data);
		hybrid_chart.series[3].setData(quarter_obligate_data);
		hybrid_chart.series[4].setData(quarter_forecaster_data);
		hybrid_chart.series[5].setData(quarter_spend_data);
	}
	else{
		/*update bar chart*/
		bar_chart.xAxis[0].setCategories(month_finance_label_arr);
		bar_chart.series[0].setData(month_obligate_data);
		bar_chart.series[1].setData(month_forecaster_data);
		bar_chart.series[2].setData(month_spend_data);
	
		/*update line chart*/
		line_chart.xAxis[0].setCategories(month_finance_label_arr);
		line_chart.series[0].setData(month_obligate_data);
		line_chart.series[1].setData(month_forecaster_data);
		line_chart.series[2].setData(month_spend_data);
		
		/*update hybrid chart*/
		hybrid_chart.xAxis[0].setCategories(month_finance_label_arr);
		hybrid_chart.series[0].setData(month_obligate_data);
		hybrid_chart.series[1].setData(month_forecaster_data);
		hybrid_chart.series[2].setData(month_spend_data);
		hybrid_chart.series[3].setData(month_obligate_data);
		hybrid_chart.series[4].setData(month_forecaster_data);
		hybrid_chart.series[5].setData(month_spend_data);
	}	
});


</script>
<?php unset($_SESSION['form_msg']);?>
</body>
</html>
