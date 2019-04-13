<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/12
 * Time: 16:06
 */
require "./Scheduler.php";
require "./SystemCall.php";

/*function task1() {
    for ($i = 1; $i <= 10; ++$i) {
        echo "This is task 1 iteration $i.\n";
        yield;
    }
}

function task2() {
    for ($i = 1; $i <= 5; ++$i) {
        echo "This is task 2 iteration $i.\n";
        yield;
    }
}

$scheduler = new Scheduler;

$scheduler->newTask(task1());
$scheduler->newTask(task2());

$scheduler->run();*/

function getTaskId(){
    return new SystemCall(
      function(Task $task,Scheduler $scheduler){
          $task->setSendValue($task->getTaskId());
          $scheduler->schedule($task);
      }
    );
}

function newTask(Generator $coroutine){
    return new SystemCall(
        function (Task $task,Scheduler $scheduler) use($coroutine){
            $task->setSendValue($scheduler->newTask($coroutine));
            $scheduler->schedule($task);
        }
    );
}

function killTask($tid){
    return new SystemCall(
        function (Task $task,Scheduler $scheduler) use ($tid){
            $task->setSendValue($scheduler->killTask($tid));
            $scheduler->schedule($task);
        }
    );
}

function childTask(){
    $tid = (yield getTaskId());
    while(true){
        echo "Child task $tid still alive!\n";
        yield;
    }
}

function task(){
    $tid = (yield getTaskId());
    $childTid = (yield newTask(childTask()));

    for($i=0; $i<=6;$i++){
        echo "Parent task $tid iteration $i.\n";
        yield;

        if($i==3){
            yield killTask($childTid);
        }
    }
}



/*function task($max){
    $tid = (yield getTaskId());
    for($i=0;$i<=$max;$i++){
        echo "This is task $tid iteration $i.\n";
        yield;
    }
}*/

$scheduler = new Scheduler();
$scheduler->newTask(task());
/*$scheduler->newTask(task(10));
$scheduler->newTask(task(5));*/

$scheduler->run();