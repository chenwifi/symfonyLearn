<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/25
 * Time: 11:23
 */
require_once './testEvent.php';

class testListener{
    public function onTestAction(testEvent $event){
        $event->addLists('from testListener');
    }
}