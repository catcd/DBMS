<?php
require("mysql_connect.php");

$drop_first_table = 'DROP TABLE IF EXISTS table_esd;';
$drop_second_table = 'DROP TABLE IF EXISTS table_dept_max_workday;';

$create_first_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS
						table_esd
						AS
						SELECT e.emp_no AS emp_no, de.dept_no AS dept_no, 
							DATEDIFF(MAX(s.from_date), MIN(s.from_date)) AS duration 
						FROM employees e
							JOIN salaries s ON e.emp_no = s.emp_no
							JOIN dept_emp de ON e.emp_no = de.emp_no
						GROUP BY e.emp_no;';
$create_second_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS
						table_dept_max_workday
						AS
						SELECT table_esd.dept_no AS dept_no, 
							MAX(table_esd.duration) AS max_duration 
						FROM table_esd
						GROUP BY table_esd.dept_no;';

$query_five = 'SELECT  esd.dept_no, COUNT(*) AS emp_number
				FROM table_esd esd
					JOIN table_dept_max_workday tdmw
					ON esd.dept_no = tdmw.dept_no
				WHERE esd.duration = tdmw.max_duration
				GROUP BY esd.dept_no;';

query_five();

function query_five(){
	global $conn;
	global $drop_first_table, $drop_second_table;
	global $create_first_table, $create_second_table;
	global $query_five;

	/*$result = $conn->query($drop_first_table);
	$result = $conn->query($drop_second_table);
	$result = $conn->query($create_first_table);
	$result = $conn->query($create_second_table);
	$result = $conn->query($query_five);

	if($result){
		//$num_rows = $result->num_rows;

		while($row = $result->fetch_assoc())
		{
			echo 'Success';
		}
	} else {
		 echo "Error: " . $conn->error;
	}*/
	
	$complete = execute_queries($drop_first_table. $create_first_table . $drop_second_table .
								$create_second_table . $query_five);
	
	if($complete == true){
		echo true;
	} else {
		echo false;
	}
}

mysqli_close($conn);
?>