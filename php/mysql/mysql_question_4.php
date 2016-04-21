<?php
require("mysql_connect.php");

$drop_first_table = 'DROP TABLE IF EXISTS table_emp_avg_salary;';
$drop_second_table = 'DROP TABLE IF EXISTS table_mng_avg_salary;';

$create_first_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS
						table_emp_avg_salary
						AS
						SELECT e.emp_no AS emp_no, AVG(s.salary) AS avg_emp_salary, de.dept_no AS dept_no
						FROM employees e JOIN salaries s ON e.emp_no = s.emp_no
							JOIN titles t ON e.emp_no = t.emp_no
							JOIN dept_emp de ON e.emp_no = de.emp_no
						WHERE e.emp_no NOT IN ( SELECT dm.emp_no FROM dept_manager dm)
						GROUP BY e.emp_no;';
$create_second_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS
						table_mng_avg_salary
						AS
						SELECT dm.dept_no AS dept_no, AVG(s.salary) AS avg_mng_salary
						FROM ((employees e JOIN salaries s ON s.emp_no = e.emp_no) 
							JOIN dept_manager dm ON e.emp_no = dm.emp_no)
						GROUP BY dm.emp_no;';

$query_four = 'SELECT teas.emp_no, teas.avg_emp_salary as avg_salary
				FROM table_emp_avg_salary teas
					JOIN table_mng_avg_salary tmas
					ON teas.dept_no = tmas.dept_no
				WHERE teas.avg_emp_salary > tmas.avg_mng_salary;';

query_four();

function query_four(){
	global $conn;
	global $drop_first_table, $drop_second_table;
	global $create_first_table, $create_second_table;
	global $query_four;

/*	$result = $conn->query($drop_first_table);
	$result = $conn->query($drop_second_table);
	$result = $conn->query($create_first_table);
	$result = $conn->query($create_second_table);
	$result = $conn->query($query_four);

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
								$create_second_table . $query_four);

	if($complete == true){
		echo true;
	} else {
		echo false;
	}
}

mysqli_close($conn);
?>