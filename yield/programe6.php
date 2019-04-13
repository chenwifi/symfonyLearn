<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/12
 * Time: 14:43
 */
function gen(){
    $ret = (yield 'yield1');
    var_dump($ret);
    $ret = (yield 'yield2');
    var_dump($ret);
}

$gen = gen();
echo $gen->current();
var_dump($gen->current());
var_dump($gen->send('ret1'));
var_dump($gen->send('ret2'));