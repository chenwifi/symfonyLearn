<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/25
 * Time: 9:51
 */
class Event{
    private $propagationStopped = false;

    public function isPropagationStopped(){
        return $this->propagationStopped;
    }

    public function stopPropagation(){
        $this->propagationStopped = true;
    }
}