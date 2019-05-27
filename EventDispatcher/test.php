<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/25
 * Time: 11:16
 */
require_once './EventDispatcher.php';
require_once './testEvent.php';
require_once './testListener.php';
require_once './testSubscriber.php';

$dispatcher = new EventDispatcher();

$listener = new testListener();
$subscriber = new testSubscriber();
$event = new testEvent();

$dispatcher->addListener('test.action',function (testEvent $event){
    $event->addLists('function');
});
$dispatcher->addListener('test.action',[$listener,'onTestAction']);
$dispatcher->addSubscriber($subscriber);
//print_r($dispatcher->listeners);exit;
print_r($dispatcher->getListeners());
$dispatcher->removeListener('test.action',[$listener,'onTestAction']);
//print_r($dispatcher->listeners);exit;
print_r($dispatcher->getListeners());
$dispatcher->removeSubscriber($subscriber);
//print_r($dispatcher->listeners);exit;
print_r($dispatcher->getListeners());

//$dispatcher->dispatch(null,$event);

//print_r($event->getLists());


