<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/12
 * Time: 9:59
 */

function gen(){
    while(true){
        yield "gen\n";
    }
}

$gen = gen();

//var_dump($gen instanceof Iterator);
//echo 'haha';

$i = 0;
foreach ($gen as $key=>$value){
    echo $key . '---' . $value . "\n";
    if($i++>10){
        break;
    }
}