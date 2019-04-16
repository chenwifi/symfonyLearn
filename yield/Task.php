<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/12
 * Time: 15:32
 */
class Task{
    protected $taskId;
    protected $coroutine;
    protected $sendValue = null;
    protected $beforeFirstYield = true;

    protected $exception = null;

    public function setException($exception) {
        $this->exception = $exception;
    }

    public function __construct($taskId,Generator $coroutine)
    {
        $this->taskId = $taskId;
        //$this->coroutine = $coroutine;
        $this->coroutine = StackedCoroutine($coroutine);
    }

    public function getTaskId(){
        return $this->taskId;
    }

    public function setSendValue($sendValue){
        $this->sendValue = $sendValue;
    }

    /*public function run(){
        if($this->beforeFirstYield){
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        }else{
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }*/

    public function run() {
        if ($this->beforeFirstYield) {
            $this->beforeFirstYield = false;
            return $this->coroutine->current();
        } elseif ($this->exception) {
            $retval = $this->coroutine->throw($this->exception);
            $this->exception = null;
            return $retval;
        } else {
            $retval = $this->coroutine->send($this->sendValue);
            $this->sendValue = null;
            return $retval;
        }
    }

    public function isFinished(){
        return !$this->coroutine->valid();
    }
}

function stackedCoroutine(Generator $gen) {
    $stack = new SplStack;
    $exception = null;

    for (;;) {
        try {
            if ($exception) {
                $gen->throw($exception);
                $exception = null;
                continue;
            }

            $value = $gen->current();

            if ($value instanceof Generator) {
                $stack->push($gen);
                $gen = $value;
                continue;
            }

            $isReturnValue = $value instanceof CoroutineReturnValue;
            if (!$gen->valid() || $isReturnValue) {
                if ($stack->isEmpty()) {
                    return;
                }

                $gen = $stack->pop();
                $gen->send($isReturnValue ? $value->getValue() : NULL);
                continue;
            }

            try {
                $sendValue = (yield $gen->key() => $value);
            } catch (Exception $e) {
                $gen->throw($e);
                continue;
            }

            $gen->send($sendValue);
        } catch (Exception $e) {
            if ($stack->isEmpty()) {
                throw $e;
            }

            $gen = $stack->pop();
            $exception = $e;
        }
    }
}

class CoroutineReturnValue {
    protected $value;

    public function __construct($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }
}

function retval($value) {
    return new CoroutineReturnValue($value);
}