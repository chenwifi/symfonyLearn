<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/25
 * Time: 11:18
 */
require_once './EventSubscriberInterface.php';
require_once './testEvent.php';


class testSubscriber implements EventSubscriberInterface{
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            'test.action'=>['testA',2],
            'process.action'=>'testB',
            'end.action'=>[['testC',3],['testD']],
        ];
    }

    public function testA(testEvent $event){
        $event->addLists('testSubscriber::testA');
    }

    public function testB(testEvent $event){
        $event->addLists('testSubscriber::testB');
    }

    public function testC(testEvent $event){
        $event->addLists('testSubscriber::testC');
    }

    public function testD(testEvent $event){
        $event->addLists('testSubscriber::testD');
    }
}