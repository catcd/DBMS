<?php
require("mysql_connect.php");

$drop_first_table = 'DROP TABLE IF EXISTS table_emp_salary;';
$drop_second_table = 'DROP TABLE IF EXISTS table_max_emp_salary;';
$drop_third_table = 'DROP TABLE IF EXISTS table_title_max_salary;';

$create_first_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS 
						table_emp_salary 
						AS 
						SELECT e.emp_no AS emp_no, SUM(s.salary) AS emp_salary, t.title AS title
						FROM employees e
							JOIN salaries s ON e.emp_no = s.emp_no
							JOIN titles t ON e.emp_no = t.emp_no
						GROUP BY e.emp_no;';

$create_second_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS 
						table_max_emp_salary 
						AS 
						SELECT tes.title AS title, MAX(tes.emp_salary) AS max_salary
						FROM view_emp_salary tes
						GROUP BY tes.title;';

$create_third_table = 'CREATE TEMPORARY TABLE IF NOT EXISTS 
						table_title_max_salary 
						AS 
						SELECT tes.title AS title, tes.emp_no AS emp_no, tes.emp_salary AS emp_max_salary
						FROM table_emp_salary tes
							#Luong cao nhat cua mot nhan vien co title tuong ung
							JOIN table_max_emp_salary tmes ON tes.title = tmes.title
						WHERE tes.emp_salary = tmes.max_salary;';

$first_query = 'SELECT tes.title, COUNT(*) AS emp_number, ttms.emp_no AS emp_no, 
						ttms.emp_max_salary AS max_salary
				FROM table_emp_salary tes
					JOIN table_tilte_max_salary ttms ON ttms.title = tes.title
				GROUP BY tes.title;';

$second_query = 'SELECT tes.title, ttms.emp_no AS emp_no, de.dept_no
				FROM table_emp_salary tes
					JOIN table_title_max_salary ttms ON ttms.title = tes.title
					JOIN dept_emp de ON ttms.emp_no = de.emp_no;';

query_two();

function query_two(){
	global $conn;
	global $drop_first_table, $drop_second_table, $drop_third_table;
	global $create_first_table, $create_second_table, $create_third_table;
	global $second_query, $first_query;

	/*$result = $conn->query($drop_first_table);
	$result = $conn->query($create_first_table);

	$result = $conn->query($drop_second_table);
	$result = $conn->query($create_second_table);

	$result = $conn->query($drop_third_table);
	$result = $conn->query($create_third_table);

	$result = $conn->query($first_query);
	$result = $conn->query($second_query);


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
		$drop_second_table . $create_second_table . $drop_third_table .
		$create_third_table . $first_query . $second_query);

	if($complete == true){
		echo true;
	} else {
		echo false;
	}
}

mysqli_close($conn);
?>