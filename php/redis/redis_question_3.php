<?php
    function question3($redis) {
        $results = array();
        $departments = $redis->lrange("departments:dept_no", 0, -1);
        foreach ($departments as $dept) {
            $name = $redis->get("departments:" . $dept);
            $managers = $redis->lrange("dept_manager:dept_no:". $dept, 0, -1);
            $max_sal = 0;
            foreach ($managers as $man) {
                $salaries = $redis->lrange("salaries:emp_no:" . $man, 0, -1);
                $sal = avgSalary($salaries);
                if ($sal > $max_sal) {
                    $max_sal = $sal;
                    $max_man = $man;
                    $max_time = findWorkingTime($salaries);
                }
            }
            
            $emp = $redis->lrange("dept_emp:dept_no:". $dept, 0, -1);
            $count = 0;
            foreach ($emp as $e) {
                $salaries = $redis->lrange("salaries:emp_no:" . $man, 0, -1);
                $emp_time = findWorkingTime($salaries);
                if ($emp_time > $max_time) {
                    $count++;
                }
            }
            $results[$dept] = array($name, $count);
        }
        
        echo True;
    }
?>
