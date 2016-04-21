<?php
require("mysql_connect.php");


$drop_first_table = 'DROP TABLE IF EXISTS table_dms;';
$drop_second_table = 'DROP TABLE IF EXISTS table_mng_info;';
$drop_third_table = 'DROP TABLE IF EXISTS table_etd;';
$drop_fourth_table = 'DROP TABLE IF EXISTS table_mng_max_salary;';
$drop_fifth_table = 'DROP TABLE IF EXISTS table_dept_max_salary;';

$create_first_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS
						table_dms
						AS
						SELECT d.dept_name AS dept_name, dm.emp_no AS emp_no, dm.dept_no AS dept_no
						FROM dept_manager dm LEFT JOIN departments d ON dm.dept_no = d.dept_no;';
$create_second_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS 
						table_mng_info
						AS
						SELECT dms.emp_no AS emp_no, dms.dept_name AS dept_name, 
								dms.dept_no AS dept_no, SUM(s.salary) AS mng_salary,
								DATEDIFF(MAX(s.from_date), MIN(s.from_date)) AS duration
						FROM employees e JOIN salaries s ON e.emp_no = s.emp_no
							JOIN table_dms dms
							ON e.emp_no = dms.emp_no
						GROUP BY dms.emp_no;';

$create_third_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS 
						table_etd
						AS
						SELECT e.emp_no AS emp_no, de.dept_no AS dept_no,
							DATEDIFF(MAX(s.from_date), MIN(s.from_date)) AS duration
						FROM employees e JOIN titles t ON e.emp_no = t.emp_no
							JOIN salaries s ON e.emp_no = s.emp_no
							JOIN dept_emp de ON e.emp_no = de.emp_no
						GROUP BY e.emp_no;';

$create_fourth_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS
						table_mng_max_salary
						AS
						SELECT tmi.dept_no AS dept_no, MAX(tmi.mng_salary) AS max_mng_salary
						FROM table_mng_info tmi 
						GROUP BY tmi.dept_no;';
						
$create_fifth_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS
						table_dept_max_salary
						AS
						SELECT tmi.dept_name AS dept_name, tmi.dept_no AS dept_no, 
							tmi.emp_no AS mng_no, tmi.duration AS duration
						FROM table_mng_info tmi 
							JOIN table_mng_max_salary tmms
							ON tmms.dept_no = tmi.dept_no
						WHERE tmi.mng_salary = tmms.max_mng_salary;';

$query_three = 'SELECT tdms.dept_name, COUNT(*) AS emp_number
				FROM table_etd etd
					JOIN table_dept_max_salary tdms
					ON etd.dept_no = tdms.dept_no
				WHERE etd.duration >= tdms.duration
				GROUP BY etd.dept_no;';

query_three();

function query_three(){
	global $conn;
	global $drop_first_table, $drop_second_table, $drop_third_table, $drop_fourth_table, $drop_fifth_table;
	global $create_first_table, $create_second_table, $create_third_table, $create_fourth_table, $create_fifth_table;
	global $query_three;

/*	$result = $conn->query($drop_first_table);
	$result = $conn->query($create_first_table);

	$result = $conn->query($drop_second_table);
	$result = $conn->query($create_second_table);

	$result = $conn->query($drop_third_table);
	$result = $conn->query($create_third_table);

	$result = $conn->query($drop_fourth_table);
	$result = $conn->query($create_fourth_table);

	$result = $conn->query($drop_fifth_table);
	$result = $conn->query($create_fifth_table);

	$result = $conn->query($query_three);

	if($result){
		//$num_rows = $result->num_rows;

		while($row = $result->fetch_assoc())
		{
			echo 'Success';
		}
	} else {
		 echo "Error: " . $conn->error;
	}*/

	$complete = execute_queries($drop_first_table . $create_first_table . 
								$drop_second_table . $create_second_table . 
								$drop_third_table . $create_third_table . 
								$drop_fourth_table . $create_fourth_table .
								$drop_fifth_table . $create_fifth_table . $query_three);

	if($complete == true){
		echo true;
	} else {
		echo false;
	}
}

mysqli_close($conn);
?>