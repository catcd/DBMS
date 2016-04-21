<?php
    function timeDelta($date1, $date2) {
        $date1 = explode("-", $date1, 3);
        $date2 = explode("-", $date2, 3);
        $year1 = (int)$date1[0];
        $year2 = (int)$date2[0];
        $month1 = (int)$date1[1];
        $month2 = (int)$date2[1];
        $day1 = (int)$date1[2];
        $day2 = (int)$date2[2];
        
        return $day2 - $day1 + ($month2 - $month1) * 31 + ($year2 - $year1) * 365;
    }
    
    function findWorkingTime($salaries) {
        $begin = "3000-12-31";
        $end = "0";
        foreach ($salaries as $sal) {
            $date = explode(":", $sal, 2)[0];
            if ($date < $begin) {
                $begin = $date;
            }
            if ($date > $end) {
                $end = $date;
            }
        }
        return timeDelta($begin, $end);
    }
    
    function sumSalary($salaries) {
        $sum = 0;
        foreach ($salaries as $sal) {
            $sum += (int)explode(":", $sal, 2)[1];
        }
        return $sum;
    }
    
    function avgSalary($salaries) {
        $sum = sumSalary($salaries);
        return $sum / count($salaries);
    }
    
    function isManager($emp_no, $managers) {
        foreach ($managers as $man) {
            if ($man == $emp_no) {
                return true;
            }
        }
        return false;
    }
?>
