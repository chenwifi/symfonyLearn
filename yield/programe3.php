<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/12
 * Time: 10:50
 */
function gen(){
    yield 1;
    echo "bigfat\n";
    yield 2;
    yield 3+2;
}

/*$gen = gen();
foreach ($gen as $key=>$value){
    echo "$key---$value\n";
}*/

$gen = gen();
var_dump($gen->valid());
echo $gen->key().' - '.$gen->current()."\n";
$gen->next();
var_dump($gen->valid());
echo $gen->key().' - '.$gen->current()."\n";
$gen->next();
var_dump($gen->valid());
echo $gen->key().' - '.$gen->current()."\n";
$gen->next();
var_dump($gen->valid());