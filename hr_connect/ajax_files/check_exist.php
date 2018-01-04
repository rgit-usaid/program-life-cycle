<?php
include('../config/config.inc.php');

if($_REQUEST['supervisor_id'])
{
	$supervisor_id = trim($_REQUEST['supervisor_id']); 
	$_employee_id = trim($_REQUEST['employee_id']);
	if($supervisor_id!='')
	{
		if($supervisor_id==$_employee_id)
		{
			echo "USAID Supervisor Employee ID Number should not be same as self id";
		}
		else
		{
			$select_emp_id = "select employee_id from usaid_employee where employee_id = '".$supervisor_id."'";
			$result_emp_id = $mysqli->query($select_emp_id);
			$fetch_emp_id = $result_emp_id->fetch_array();
			if($fetch_emp_id['employee_id']=='')
			{	
				echo "There is no employee exist in HR with this employee id.";
			}
		}	 
	}
}
?>