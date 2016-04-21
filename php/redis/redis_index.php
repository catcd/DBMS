<?php
    //Connecting to Redis server on localhost
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);

    require("redis_helper_functions.php");
    require("redis_question_1.php");
    require("redis_question_2.php");
    require("redis_question_3.php");
    require("redis_question_4.php");
    require("redis_question_5.php");

    question1($redis);
    question2($redis);
    question3($redis);
    question4($redis);
    question5($redis);
?>
