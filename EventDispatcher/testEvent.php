<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/5/25
 * Time: 11:23
 */
require_once './Event.php';

class testEvent extends Event{
    private $testLists = [];

    public function addLists($list){
        $this->testLists[] = $list;
    }

    public function getLists(){
        return $this->testLists;
    }
}