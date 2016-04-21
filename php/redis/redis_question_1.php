<?php
    function question1($redis) {
        $results = array();
        // get array of all dept_no
        $departments = $redis->lrange("departments:dept_no", 0, -1);
        foreach ($departments as $dept) {
            // get dept_name of dept_no
            $name = $redis->get("departments:" . $dept);
            // get all managers of dept_no
            $managers = $redis->lrange("dept_manager:dept_no:". $dept, 0, -1);
            $sum = 0;
            foreach ($managers as $man) {
                $small_sum = 0;
                // get all salaries of current manager
                $salaries = $redis->lrange("salaries:emp_no:" . $man, 0, -1);
                $sum += avgSalary($salaries);
            }
            $results[$name] = $sum / count($managers);
        }

        echo True;
    }
?>
