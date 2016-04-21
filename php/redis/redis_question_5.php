<?php
    function question5($redis) {
        $results = array();
        // get array of all dept_no
        $departments = $redis->lrange("departments:dept_no", 0, -1);
        
        foreach ($departments as $dept) {
            $emp = $redis->lrange("dept_emp:dept_no:". $dept, 0, -1);
            $max_time = 0;
            foreach ($emp as $e) {
                $salaries = $redis->lrange("salaries:emp_no:" . $e, 0, -1);
                $sum = sumSalary($salaries);
                $workingTime = findWorkingTime($salaries);
                if ($workingTime > $max_time) {
                    $results[$dept] = $sum;
                }
            }
        }

        echo True;
    }
?>
