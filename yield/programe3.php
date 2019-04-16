<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/12
 * Time: 10:50
 */

function test($mix){
    var_dump($mix);
    //echo 'hello world';
    return 'ddd';
}

function test1($mix){
    echo $mix;
    return 'tttt';
}

//yield 是函数，则先执行
//函数的返回值是协程的返回值
//如果yield是作为函数参数，则先执行参数的内容，函数在next后执行。

function gen(){
    yield test(test1( yield 'can'=> 'aaa'));
    echo "bigfat\n";
    return ;
    yield 2;
    yield 3+2;
}

/*$gen = gen();
foreach ($gen as $key=>$value){
    echo "$key---$value\n";
}*/

$gen = gen();
var_dump($gen->valid());
echo $gen->key().' - '."\n";
var_dump($gen->current());
$gen->send('chenwifi');
echo $gen->current();
$gen->send('haha');
var_dump($gen->valid());
exit;
var_dump($gen->valid());
echo $gen->key().' - '.$gen->current()."\n";
$gen->next();
var_dump($gen->valid());
echo $gen->key().' - '.$gen->current()."\n";
$gen->next();
var_dump($gen->valid());