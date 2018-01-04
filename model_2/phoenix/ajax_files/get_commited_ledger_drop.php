<?php
include('../config/config.inc.php');
include('../include/function.inc.php');

### get all commited ledger id by ou ===========
if(isset($_REQUEST['operating_unit_id']))
{
	$operating_unit_id = trim($_REQUEST['operating_unit_id']);
	## get all unique commited fund by type ==========
	$url = API_HOST_URL."get_all_commited_ledger_by_ou_id.php?operating_unit_id=".$operating_unit_id.""; 
	$unique_fund_arr = requestByCURL($url);
	 ?> 
		<select class="form-control strip_year" name="strip_year" onchange="getcurrentFiscalyear();">
			<option value="">Selected</option>
		<?php
		for($i=0; $i<count($unique_fund_arr['data']); $i++)
		{ 
			$unique_total_fund_arr = getClosingBalance($unique_fund_arr['data'][$i]['ledger_type_id_cr'],$unique_fund_arr['data'][$i]['fund_beginning_fiscal_year'],$unique_fund_arr['data'][$i]['fund_ending_fiscal_year'],$unique_fund_arr['data'][$i]['fund_id'],$unique_fund_arr['data'][$i]['ledger_type'],$unique_fund_arr['data'][$i]['program_element_id'],'Commit');
		?>
			<option value="<?php echo $unique_fund_arr['data'][$i]['gp_year'].' =>'.$unique_fund_arr['data'][$i]['ledger_type'].'>'.$unique_fund_arr['data'][$i]['ledger_type_id_cr'].'>>'.$unique_total_fund_arr['closing_balance']; ?>"><?php echo $unique_fund_arr['data'][$i]['gp_year'] .' ('.$unique_fund_arr['data'][$i]['ledger_type_id_cr'].' '.$unique_fund_arr['data'][$i]['ledger_type'].')';if($unique_total_fund_arr['closing_balance']!='')echo ' ($'.number_format($unique_total_fund_arr['closing_balance']).')'; ?></option>
		<?php
		}

		$url = API_HOST_URL."get_ou_fund_unique.php?operating_unit_id=".$operating_unit_id."";
		$ou_unique_fund_arr = requestByCURL($url);
		for($i=0; $i<count($ou_unique_fund_arr['data']); $i++)
		{ 
			$unique_total_fund_arr = getClosingBalance($operating_unit_id,$ou_unique_fund_arr['data'][$i]['fund_beginning_fiscal_year'],$ou_unique_fund_arr['data'][$i]['fund_ending_fiscal_year'],$ou_unique_fund_arr['data'][$i]['fund_id'],'Operating Unit',$ou_unique_fund_arr['data'][$i]['program_element_id']);
		?>
			<option value="<?php echo $ou_unique_fund_arr['data'][$i]['gp_year'].' =>Operating Unit'.'>>'.$unique_total_fund_arr['closing_balance']; ?>"><?php echo $ou_unique_fund_arr['data'][$i]['gp_year'].' (Available)';if($unique_total_fund_arr['closing_balance']!='')echo ' ($'.number_format($unique_total_fund_arr['closing_balance']).')'; ?></option>
		<?php
		}
		?>	
		</select>
	<?php     
}
?>
