<?php
function requestByCURL($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);                               
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch);
	curl_close($ch);
	$data_arr = json_decode($output,true);
	return $data_arr;
}

##function for get date format to insert into db y-m-d ========
function dateFormat($date)
{
	$date_formated = '';
   	if($date!='')
   	{
     	$date_formated = date('Y-m-d',strtotime($date));
   	}
   	return $date_formated; 
}
##==change dateTimeFormat===
function dateTimeFormat($datetime)
{
	$datetime = date('m/d/Y h:i',strtotime($datetime));
	return $datetime;
}
### function for remove Requisition and also delete thier CLIN levels 1 to 4=========
function removeRequisition($requisition_number)
{
	global $mysqli;
	$url = API_HOST_URL."get_all_clin_l1_by_requisition.php?requisition_number=".$requisition_number;
	$clin_l1_arr = requestByCURL($url);
	if(count($clin_l1_arr['data'])>0)
	{
		for($i=0; $i<count($clin_l1_arr['data']); $i++)
		{
			$clin_l1_number = $clin_l1_arr['data'][$i]['clin_number'];
			$url = API_HOST_URL."get_all_clin_l2_by_clin1.php?clin_l1_number=".$clin_l1_number;
		  	$clin_l2_arr = requestByCURL($url);
			if(count($clin_l2_arr['data'])>0)
			{  
				for($j=0; $j<count($clin_l2_arr['data']); $j++)
				{ 
					$clin_l2_number = $clin_l2_arr['data'][$j]['clin_number'];
					$url = API_HOST_URL."get_all_clin_l3_by_clin2.php?clin_l2_number=".$clin_l2_number;
					$clin_l3_arr = requestByCURL($url);
					if(count($clin_l3_arr['data'])>0)
					{	 
						for($k=0; $k<count($clin_l3_arr['data']); $k++)
						{
							$clin_l3_number = $clin_l3_arr['data'][$k]['clin_number'];
							$url = API_HOST_URL."get_all_clin_l4_by_clin3.php?clin_l3_number=".$clin_l3_number;
							$clin_l4_arr = requestByCURL($url);
							if(count($clin_l4_arr['data'])>0)
							{ 
								for($l=0; $l<count($clin_l4_arr['data']); $l++)
								{
									$clin_l4_number = $clin_l4_arr['data'][$l]['clin_number']; 
									### Update as remove ===================
									updateReqClinAsRemove($clin_l4_number);
									updateReqClinAsRemove($clin_l3_number);
									updateReqClinAsRemove($clin_l2_number);
									updateReqClinAsRemove($clin_l1_number);
									updateRequisitionAsRemove($requisition_number);
								}
							}
							else
							{
								updateReqClinAsRemove($clin_l3_number);
								updateReqClinAsRemove($clin_l2_number);
								updateReqClinAsRemove($clin_l1_number);
								updateRequisitionAsRemove($requisition_number);
							} 	
						}
					}
					else{
						updateReqClinAsRemove($clin_l2_number);
						updateReqClinAsRemove($clin_l1_number);
						updateRequisitionAsRemove($requisition_number);
					}			
				}
			}
			else
			{
				updateReqClinAsRemove($clin_l1_number);
				updateRequisitionAsRemove($requisition_number);
			}
		}
	}
	else
	{
		updateRequisitionAsRemove($requisition_number);
	}
}

##delete Requisition CLIN l1=========================
function removeRequisitionClin_l1($clin_l1_number)
{  
	$url = API_HOST_URL."get_all_clin_l2_by_clin1.php?clin_l1_number=".$clin_l1_number;
	$clin_l2_arr = requestByCURL($url);
	if(count($clin_l2_arr['data'])>0)
	{
		for($j=0; $j<count($clin_l2_arr['data']); $j++)
		{
			$clin_l2_number = $clin_l2_arr['data'][$j]['clin_number'];
			$url = API_HOST_URL."get_all_clin_l3_by_clin2.php?clin_l2_number=".$clin_l2_number;
			$clin_l3_arr = requestByCURL($url);
			if(count($clin_l3_arr['data'])>0)
			{
				for($k=0; $k<count($clin_l3_arr['data']); $k++)
				{
					$clin_l3_number = $clin_l3_arr['data'][$k]['clin_number'];
					$url = API_HOST_URL."get_all_clin_l4_by_clin3.php?clin_l3_number=".$clin_l3_number;
					$clin_l4_arr = requestByCURL($url);
					if(count($clin_l4_arr['data'])>0)
					{
						for($l=0; $l<count($clin_l4_arr['data']); $l++)
						{
							$clin_l4_number = $clin_l4_arr['data'][$l]['clin_number'];
							### Update as remove ===================
							updateReqClinAsRemove($clin_l4_number);
							updateReqClinAsRemove($clin_l3_number);
							updateReqClinAsRemove($clin_l2_number);
							updateReqClinAsRemove($clin_l1_number);
						}
					}
					else
					{
						updateReqClinAsRemove($clin_l3_number);
						updateReqClinAsRemove($clin_l2_number);
						updateReqClinAsRemove($clin_l1_number);
					}
				}
			}
			else
			{
				updateReqClinAsRemove($clin_l2_number);
				updateReqClinAsRemove($clin_l1_number);
			}
		}
	}
	else
	{
		updateReqClinAsRemove($clin_l1_number);	
	}
}

##delete Requisition CLIN l2===========
function removeRequisitionClin_l2($clin_l2_number)
{
	$url = API_HOST_URL."get_all_clin_l3_by_clin2.php?clin_l2_number=".$clin_l2_number;
	$clin_l3_arr = requestByCURL($url); 
	if(count($clin_l3_arr['data'])>0)
	{
		for($k=0; $k<count($clin_l3_arr['data']); $k++)
		{ 
			$clin_l3_number = $clin_l3_arr['data'][$k]['clin_number'];
			$url = API_HOST_URL."get_all_clin_l4_by_clin3.php?clin_l3_number=".$clin_l3_number;
			$clin_l4_arr = requestByCURL($url);
			if(count($clin_l4_arr['data'])>0)
			{
				for($l=0; $l<count($clin_l4_arr['data']); $l++)
				{
					$clin_l4_number = $clin_l4_arr['data'][$l]['clin_number'];
					### Update as remove ===================
					updateReqClinAsRemove($clin_l4_number);
					updateReqClinAsRemove($clin_l3_number);
					updateReqClinAsRemove($clin_l2_number);
				}
			}
			else
			{
				updateReqClinAsRemove($clin_l3_number);
				updateReqClinAsRemove($clin_l2_number);
			}
		}
	}
	else
	{
		updateReqClinAsRemove($clin_l2_number);
	}
}

##delete Requisition CLIN l3===========
function removeRequisitionClin_l3($clin_l3_number)
{
	$url = API_HOST_URL."get_all_clin_l4_by_clin3.php?clin_l3_number=".$clin_l3_number;
	$clin_l4_arr = requestByCURL($url);
	if(count($clin_l4_arr['data'])>0)
	{
		for($l=0; $l<count($clin_l4_arr['data']); $l++)
		{
			$clin_l4_number = $clin_l4_arr['data'][$l]['clin_number'];
			### Update as remove ===================
			updateReqClinAsRemove($clin_l4_number);
			updateReqClinAsRemove($clin_l3_number);
		}
	}
	else{
		updateReqClinAsRemove($clin_l3_number);
	}	 
}

##delete Requisition CLIN l4===========
function removeRequisitionClin_l4($clin_l4_number)
{
	updateReqClinAsRemove($clin_l4_number);
}


###update status as remove of a Requisition=================
function updateRequisitionAsRemove($requisition_number)
{
	global $mysqli;
	updateBudgetAsRemove($requisition_number);
	$update_data = "update usaid_requisition set status = 'Remove' where requisition_number='".$requisition_number."'";
	$result_data = $mysqli->query($update_data);

	###remove all award of this requisition
	removeRequisitionAward($requisition_number);
}


###update status as remove of a CLIN by clin number=================
function updateReqClinAsRemove($clin_number)
{
	global $mysqli;
	updateBudgetAsRemove($clin_number);
	$update_data = "update usaid_requisition_clin set status = 'Remove' where clin_number='".$clin_number."'";
	$result_data = $mysqli->query($update_data);

}

### function for remove all award of a requisition==========
function removeRequisitionAward($requisition_number)
{
	global $mysqli;
	$url = API_HOST_URL."get_all_award_by_requisition.php?requisition_number=".$requisition_number;
	$all_requisition_award_arr = requestByCURL($url);
	for($k=0; $k<count($all_requisition_award_arr['data']); $k++)
	{
		$award_number = $all_requisition_award_arr['data'][$k]['award_number'];
		removeAward($award_number);
	} 
}



### function for remove award and also delete thier CLIN levels 1 to 4=========
function removeAward($award_number)
{
	global $mysqli;
	$url = API_HOST_URL."get_all_award_clin_l1_by_award.php?award_number=".$award_number;
	$award_clin_l1_arr = requestByCURL($url);
	if(count($award_clin_l1_arr['data'])>0)
	{
		for($i=0; $i<count($award_clin_l1_arr['data']); $i++)
		{
			$clin_l1_number = $award_clin_l1_arr['data'][$i]['clin_number'];
			$url = API_HOST_URL."get_all_award_clin_l2_by_clin1.php?clin_l1_number=".$clin_l1_number;
		  	$award_clin_l2_arr = requestByCURL($url);
			if(count($award_clin_l2_arr['data'])>0)
			{  
				for($j=0; $j<count($award_clin_l2_arr['data']); $j++)
				{ 
					$clin_l2_number = $award_clin_l2_arr['data'][$j]['clin_number'];
					$url = API_HOST_URL."get_all_award_clin_l3_by_clin2.php?clin_l2_number=".$clin_l2_number;
					$award_clin_l3_arr = requestByCURL($url);
					if(count($award_clin_l3_arr['data'])>0)
					{	 
						for($k=0; $k<count($award_clin_l3_arr['data']); $k++)
						{
							$clin_l3_number = $award_clin_l3_arr['data'][$k]['clin_number'];
							$url = API_HOST_URL."get_all_award_clin_l4_by_clin3.php?clin_l3_number=".$clin_l3_number;
							$award_clin_l4_arr = requestByCURL($url);
							if(count($award_clin_l4_arr['data'])>0)
							{ 
								for($l=0; $l<count($award_clin_l4_arr['data']); $l++)
								{
									$clin_l4_number = $award_clin_l4_arr['data'][$l]['clin_number'];
									### Update as remove ===================
									updateClinAsRemove($clin_l4_number);
									updateClinAsRemove($clin_l3_number);
									updateClinAsRemove($clin_l2_number);
									updateClinAsRemove($clin_l1_number);
									updateAwardAsRemove($award_number);
								}
							}
							else
							{
								updateClinAsRemove($clin_l3_number);
								updateClinAsRemove($clin_l2_number);
								updateClinAsRemove($clin_l1_number);
								updateAwardAsRemove($award_number);
							} 	
						}
					}
					else{
						updateClinAsRemove($clin_l2_number);
						updateClinAsRemove($clin_l1_number);
						updateAwardAsRemove($award_number);
					}			
				}
			}
			else
			{
				updateClinAsRemove($clin_l1_number);
				updateAwardAsRemove($award_number);
			}
		}
	}
	else
	{
		updateAwardAsRemove($award_number);
	}
}

##delete Award CLIN l1=========================
function removeAwardClin_l1($clin_l1_number)
{  
	$url = API_HOST_URL."get_all_award_clin_l2_by_clin1.php?clin_l1_number=".$clin_l1_number;
	$award_clin_l2_arr = requestByCURL($url);
	if(count($award_clin_l2_arr['data'])>0)
	{
		for($j=0; $j<count($award_clin_l2_arr['data']); $j++)
		{
			$clin_l2_number = $award_clin_l2_arr['data'][$j]['clin_number'];
			$url = API_HOST_URL."get_all_award_clin_l3_by_clin2.php?clin_l2_number=".$clin_l2_number;
			$award_clin_l3_arr = requestByCURL($url);
			if(count($award_clin_l3_arr['data'])>0)
			{
				for($k=0; $k<count($award_clin_l3_arr['data']); $k++)
				{
					$clin_l3_number = $award_clin_l3_arr['data'][$k]['clin_number'];
					$url = API_HOST_URL."get_all_award_clin_l4_by_clin3.php?clin_l3_number=".$clin_l3_number;
					$award_clin_l4_arr = requestByCURL($url);
					if(count($award_clin_l4_arr['data'])>0)
					{
						for($l=0; $l<count($award_clin_l4_arr['data']); $l++)
						{
							$clin_l4_number = $award_clin_l4_arr['data'][$l]['clin_number'];
							### Update as remove ===================
							updateClinAsRemove($clin_l4_number);
							updateClinAsRemove($clin_l3_number);
							updateClinAsRemove($clin_l2_number);
							updateClinAsRemove($clin_l1_number);
						}
					}
					else
					{
						updateClinAsRemove($clin_l3_number);
						updateClinAsRemove($clin_l2_number);
						updateClinAsRemove($clin_l1_number);
					}
				}
			}
			else
			{
				updateClinAsRemove($clin_l2_number);
				updateClinAsRemove($clin_l1_number);
			}
		}
	}
	else
	{
		updateClinAsRemove($clin_l1_number);	
	}
}

##delete Award CLIN l2===========
function removeAwardClin_l2($clin_l2_number)
{
	$url = API_HOST_URL."get_all_award_clin_l3_by_clin2.php?clin_l2_number=".$clin_l2_number;
	$award_clin_l3_arr = requestByCURL($url);

	if(count($award_clin_l3_arr['data'])>0)
	{
		for($k=0; $k<count($award_clin_l3_arr['data']); $k++)
		{ 
			$clin_l3_number = $award_clin_l3_arr['data'][$k]['clin_number'];
			$url = API_HOST_URL."get_all_award_clin_l4_by_clin3.php?clin_l3_number=".$clin_l3_number;
			$award_clin_l4_arr = requestByCURL($url);
			if(count($award_clin_l4_arr['data'])>0)
			{
				for($l=0; $l<count($award_clin_l4_arr['data']); $l++)
				{
					$clin_l4_number = $award_clin_l4_arr['data'][$l]['clin_number'];
					### Update as remove ===================
					updateClinAsRemove($clin_l4_number);
					updateClinAsRemove($clin_l3_number);
					updateClinAsRemove($clin_l2_number);
				}
			}
			else
			{
				updateClinAsRemove($clin_l3_number);
				updateClinAsRemove($clin_l2_number);
			}
		}
	}
	else
	{
		updateClinAsRemove($clin_l2_number);
	}
}

##delete Award CLIN l3===========
function removeAwardClin_l3($clin_l3_number)
{
	$url = API_HOST_URL."get_all_award_clin_l4_by_clin3.php?clin_l3_number=".$clin_l3_number;
	$award_clin_l4_arr = requestByCURL($url);
	if(count($award_clin_l4_arr['data'])>0)
	{
		for($l=0; $l<count($award_clin_l4_arr['data']); $l++)
		{
			$clin_l4_number = $award_clin_l4_arr['data'][$l]['clin_number'];
			### Update as remove ===================
			updateClinAsRemove($clin_l4_number);
			updateClinAsRemove($clin_l3_number);
		}
	}
	else{
		updateClinAsRemove($clin_l3_number);
	}	 
}

##delete Award CLIN l4===========
function removeAwardClin_l4($clin_l4_number)
{
	updateClinAsRemove($clin_l4_number);
}


###update status as remove of a award=================
function updateAwardAsRemove($award_number)
{
	global $mysqli;
	updateBudgetAsRemove($award_number);
	$update_data = "update usaid_requisition_award set status = 'Remove' where award_number='".$award_number."'";
	$result_data = $mysqli->query($update_data);
}


###update status as remove of a CLIN by id=================
function updateClinAsRemove($clin_number)
{
	global $mysqli;
	updateBudgetAsRemove($clin_number);
	$update_data = "update usaid_requisition_award_clin set status = 'Remove' where clin_number='".$clin_number."'";
	$result_data = $mysqli->query($update_data);

}

##update status in budget as remove===========
function updateBudgetAsRemove($clin_number)
{
	global $mysqli;
	$update_data = "update usaid_requisition_clin_budget set status = 'Remove' where budget_number='".$clin_number."'";
	$result_data = $mysqli->query($update_data);
}

## function for amount to remove comma and dollar sign============
function getNumericAmount($amount)
{
	$nu_amount = str_replace('$', '', $amount);
	$num_amount = str_replace(',', '', $nu_amount);
	return $num_amount;
}
?>