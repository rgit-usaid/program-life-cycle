<?php
include('config/config.inc.php');

$select_ou = "select * from test_operating_unit";
$result_ou = $mysqli->query($select_ou);
while($fetch_ou = $result_ou->fetch_array())
{
	$l1 = trim($fetch_ou['L1']);
	$l2 = trim($fetch_ou['L2']);
	$l3 = trim($fetch_ou['L3']);
	$l4 = trim($fetch_ou['L4']);
	$l5 = trim($fetch_ou['L5']);
	$l6 = trim($fetch_ou['L6']);
	$l7 = trim($fetch_ou['L7']);
	$l8 = trim($fetch_ou['L8']);
	$name = trim($fetch_ou['operating_unit_full_name']);
	$name_arr = explode(' ', $name);
	$first_name = trim($name_arr[0]);
	if($first_name=='USAID' or $first_name=='usaid')
	{
		$name = str_replace('USAID', '', $name);
	}
	$abb = trim($fetch_ou['abbreviation']);
	$type = 'Other';
	####top level 
	if($l2=='00')
	{
		$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
		insertData($ou_id,$p_id,$name,$abb,'Organization',$l1,$l2,$l3,$l4,$l5,$l6);
	}

	if($l2!='00')
	{
		### level 2 at organization========== 
		if($l2!='00' and $l3=='00' and $l4=='0000' and $l5=='00' and $l6=='00')
		{
			$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
			$p_id = getFormatId($l1,'00',$l3,$l4,$l5,$l6); 
			insertData($ou_id,$p_id,$name,$abb,'Organization',$l1,$l2,$l3,$l4,$l5,$l6);
		}

		### level 3 ================
		if($l2!='00' and $l3!='00' and $l4=='0000' and $l5=='00'  and $l6=='00')
		{
			$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
			$p_id = getFormatId($l1,$l2,'00',$l4,$l5,$l6); 
			insertData($ou_id,$p_id,$name,$abb,'Bureau',$l1,$l2,$l3,$l4,$l5,$l6);
		}

		### level 4====================
		if($l2!='00' and $l3!='00' and $l4!='0000' and $l5=='00'  and $l6=='00')
		{
			$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
			$p_id = getFormatId($l1,$l2,$l3,'0000',$l5,$l6);  
			insertData($ou_id,$p_id,$name,$abb,'Office',$l1,$l2,$l3,$l4,$l5,$l6);
		}

		### level 5==================
		if($l2!='00' and $l3!='00' and $l4!='0000' and $l5!='00'  and $l6=='00')
		{
			$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
			$p_id = getFormatId($l1,$l2,$l3,$l4,'00',$l6);  
			insertData($ou_id,$p_id,$name,$abb,'Sub Office',$l1,$l2,$l3,$l4,$l5,$l6);
		}
		### level 6==================
		if($l2!='00' and $l3!='00' and $l4!='0000' and $l5!='00' and $l6!='00')
		{
			$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
			$p_id = getFormatId($l1,$l2,$l3,$l4,$l5,'00');  
			insertData($ou_id,$p_id,$name,$abb,'Branch',$l1,$l2,$l3,$l4,$l5,$l6);
		}
	}
	/*if($l2=='20')
	{
		### level 2 at organization========== 
		if($l2!='00' and $l3=='00' and $l4=='0000' and $l5=='00' and $l6=='00')
		{
			$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
			$p_id = getFormatId($l1,'00',$l3,$l4,$l5,$l6); 
			insertData($ou_id,$p_id,$name,$abb,'Overseas',$l1,$l2,$l3,$l4,$l5,$l6);
		}

		### level 3 ================
		if($l2!='00' and $l3!='00' and $l4=='0000' and $l5=='00'  and $l6=='00')
		{
			$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
			$p_id = getFormatId($l1,$l2,'00',$l4,$l5,$l6); 
			insertData($ou_id,$p_id,$name,$abb,'Bureau',$l1,$l2,$l3,$l4,$l5,$l6);
		}

		### level 4====================
		if($l2!='00' and $l3!='00' and $l4!='0000' and $l5=='00'  and $l6=='00')
		{
			$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
			$p_id = getFormatId($l1,$l2,$l3,'0000',$l5,$l6);  
			insertData($ou_id,$p_id,$name,$abb,'Office',$l1,$l2,$l3,$l4,$l5,$l6);
		}

		### level 5==================
		if($l2!='00' and $l3!='00' and $l4!='0000' and $l5!='00'  and $l6=='00')
		{
			$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
			$p_id = getFormatId($l1,$l2,$l3,$l4,'00',$l6);  
			insertData($ou_id,$p_id,$name,$abb,'Sub Office',$l1,$l2,$l3,$l4,$l5,$l6);
		}
		### level 6==================
		if($l2!='00' and $l3!='00' and $l4!='0000' and $l5!='00' and $l6!='00')
		{
			$ou_id = getFormatId($l1,$l2,$l3,$l4,$l5,$l6);
			$p_id = getFormatId($l1,$l2,$l3,$l4,$l5,'00');  
			insertData($ou_id,$p_id,$name,$abb,'Branch',$l1,$l2,$l3,$l4,$l5,$l6);
		}
	}*/
}

function insertData($ou_id,$p_id,$name,$abb,$type,$l1,$l2,$l3,$l4,$l5,$l6)
{
	global $mysqli;
	$insert = "insert into usaid_operating_unit set
				operating_unit_id='".$ou_id."',
				operating_unit_description='".$name."',
				operating_unit_abbreviation='".$abb."',
				parent_operating_unit_id='".$p_id."',
				L1='".$l1."',
				L2='".$l2."',
				L3='".$l3."',
				L4='".$l4."',
				L5='".$l5."',
				L6='".$l6."',
				type='".$type."'
				";
	$mysqli->query($insert);			
}

function getFormatId($l1,$l2,$l3,$l4,$l5,$l6)
{
	$ou_id = $l1.'-'.$l3.'-'.$l4.'-'.$l5.'-'.$l6;
	return $ou_id;
}
?>