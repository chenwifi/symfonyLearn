<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/12
 * Time: 17:28
 */
class SystemCall{
    protected $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function __invoke(Task $task,Scheduler $scheduler)
    {
        // TODO: Implement __invoke() method.
        $callback = $this->callback;
        return $callback($task,$scheduler);
    }
}