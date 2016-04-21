<?php
    function question4($redis) {
        $results = array();
        // get array of all dept_no
        $departments = $redis->lrange("departments:dept_no", 0, -1);
        
        foreach ($departments as $dept) {
            $results[$dept] = array();
            // get all managers of dept_no
            $managers = $redis->lrange("dept_manager:dept_no:". $dept, 0, -1);
            $sum = 0;
            foreach ($managers as $man) {
                $salaries = $redis->lrange("salaries:emp_no:" . $man, 0, -1);
                $sum += avgSalary($salaries);
            }
            $man_avg_sal = $sum / count($managers);
            
            $emp = $redis->lrange("dept_emp:dept_no:". $dept, 0, -1);
            foreach ($emp as $e) {
                if (isManager($e, $managers)) {
                    break;
                }
                $salaries = $redis->lrange("salaries:emp_no:" . $e, 0, -1);
                $avg = avgSalary($salaries);
                if ($avg > $man_avg_sal) {
                    $results[$dept][$e] = $avg;
                }
            }
        }
        
        echo True;
    }
?>
