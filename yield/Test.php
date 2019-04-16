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

/*function killTask($tid){
    return new SystemCall(
        function (Task $task,Scheduler $scheduler) use ($tid){
            $task->setSendValue($scheduler->killTask($tid));
            $scheduler->schedule($task);
        }
    );
}*/

function killTask($tid) {
    return new SystemCall(
        function(Task $task, Scheduler $scheduler) use ($tid) {
            if ($scheduler->killTask($tid)) {
                $scheduler->schedule($task);
            } else {
                throw new InvalidArgumentException('Invalid task ID!');
            }
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

function waitForRead($socket){
    return new SystemCall(
        function (Task $task,Scheduler $scheduler) use ($socket){
            $scheduler->waitForRead($socket,$task);
        }
    );
}

function waitForWrite($socket){
    return new SystemCall(
        function (Task $task,Scheduler $scheduler) use ($socket){
            $scheduler->waitForWrite($socket,$task);
        }
    );
}

/*function task(){
    $tid = (yield getTaskId());
    $childTid = (yield newTask(childTask()));

    for($i=0; $i<=6;$i++){
        echo "Parent task $tid iteration $i.\n";
        yield;

        if($i==3){
            yield killTask($childTid);
        }
    }
}*/



function server($port) {
    echo "Starting server at port $port...\n";

    $socket = @stream_socket_server("tcp://localhost:$port", $errNo, $errStr);
    if (!$socket) throw new Exception($errStr, $errNo);

    stream_set_blocking($socket, 0);

    while (true) {
        yield waitForRead($socket);
        $clientSocket = stream_socket_accept($socket, 0);
        yield newTask(handleClient($clientSocket));
    }
}

function handleClient($socket) {
    yield waitForRead($socket);
    $data = fread($socket, 8192);

    $msg = "Received following request:\n\n$data";
    $msgLength = strlen($msg);

    $response = <<<RES
HTTP/1.1 200 OK\r
Content-Type: text/plain\r
Content-Length: $msgLength\r
Connection: close\r
\r
$msg
RES;

    yield waitForWrite($socket);
    fwrite($socket, $response);

    fclose($socket);
}

function task() {
    try {
        yield killTask(500);
    } catch (Exception $e) {
        echo 'Tried to kill task 500 but failed: ', $e->getMessage(), "\n";
    }
}

$scheduler = new Scheduler;
$scheduler->newTask(task());
$scheduler->run();


/*function echoTimes($msg,$time){
    for($i=0;$i<$time;$i++){
        echo "$msg iterator $i \n";
        yield;
    }
}

function task1(){
    echoTimes('foo', 10); // print foo ten times
    echo "---\n";
    echoTimes('bar', 5); // print bar five times
    yield; // force it to be a coroutine
}



$scheduler = new Scheduler;
$scheduler->newTask(task1());
$scheduler->run();*/

/*$scheduler = new Scheduler;
$scheduler->newTask(server(8000));
$scheduler->run();*/

/*function task($max){
    $tid = (yield getTaskId());
    for($i=0;$i<=$max;$i++){
        echo "This is task $tid iteration $i.\n";
        yield;
    }
}*/

//$scheduler = new Scheduler();
//$scheduler->newTask(task());
/*$scheduler->newTask(task(10));
$scheduler->newTask(task(5));*/

//$scheduler->run();