<?php
require("mysql_connect.php");

$drop_tb = 'DROP TABLE IF EXISTS table_mng_sum_salary;';
$create_tb = 'CREATE TEMPORARY TABLE IF NOT EXISTS table_mng_sum_salary ' . ' AS ' .
				'SELECT dm.emp_no AS emp_no, dm.dept_no AS dept_no, SUM(s.salary) AS mng_salary
				FROM employees e 
					JOIN salaries s ON s.emp_no = e.emp_no
					JOIN dept_manager dm ON e.emp_no = dm.emp_no
				GROUP BY dm.emp_no;';

$query_one = 'SELECT d.dept_name, AVG(tmss.mng_salary)
				FROM table_mng_sum_salary tmss
					JOIN departments d ON d.dept_no = tmss.dept_no
				GROUP BY d.dept_no;';

query_one();

function query_one(){
	global $conn;
	global $duration;
	global $drop_tb, $create_tb, $query_one;

	/*$result = $conn->query($drop_tb);
	$result = $conn->query($create_tb);
	$result = $conn->query($query_one);

	if($result){
		//$num_rows = $result->num_rows;

		while($row = $result->fetch_assoc())
		{
			echo 'Success';
		}
	} else {
		 echo "Error: " . $conn->error;
	}*/

	$complete = execute_queries($drop_tb . $create_tb . $query_one);

	if($complete == true){
		echo true;
	} else {
		echo false;
	}
}

mysqli_close($conn);
?>