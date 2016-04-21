<?php
    function question2($redis) {
        $results = array();
        // get all titles
        $titles = $redis->smembers("titles:title");
        foreach ($titles as $title) {
            // get all employees have that title
            $emp = $redis->lrange("titles:title:" . $title, 0, -1);
            $max_sal = 0;
            foreach ($emp as $e) {
                $emp_no = explode(":", $e, 2)[0];
                // get all salaries of current employee
                $salaries = $redis->lrange("salaries:emp_no:" . $emp_no, 0, -1);
                $avg = avgSalary($salaries);
                if ($avg > $max_sal) {
                    $max_sal = $avg;
                    $max_emp = $emp_no;
                }
            }
            $depts = array();
            $dept_no_list = $redis->lrange("dept_emp:emp_no:" . $max_emp, 0, -1);
            foreach ($dept_no_list as $d) {
                $depts[$d] = $redis->get("departments:" . $d);
            }
            $results[$title] = array(count($emp), $max_emp, $max_sal, $depts);
        }
        
        echo True;
    }
?>
