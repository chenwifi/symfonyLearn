<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/12
 * Time: 14:00
 */
function logger($filename){
    $i = 0;
    while(true){
        echo $i++;
        echo (yield $i);
    }
}


//执行next的时候，第一个yield和第二个yield的方法将被执行
//由此可以判断执行完send的时候会执行next，因为这个的返回值
//next的时候yield是没有值的
$logger = logger('aaa');

//echo $logger->key();
//echo $logger->current();
//$logger->next();

//输出0wifi12原因：next被执行，所以第一个yield和第二个yield之间的代码被执行，echo $i++,echo yield（此时有值）
//echo $i++ 并且返回next之后的current，再次echo 2.
echo $logger->send('wifi');



//echo $logger->current();
//$logger->send('bigfat');