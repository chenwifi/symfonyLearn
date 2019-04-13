<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/12
 * Time: 11:02
 */
function gen3(){
    echo "test\n";
    echo (yield 1)."I\n";
    echo (yield 2)."II\n";
    echo (yield 3 + 1)."III\n";
}
$gen = gen3();
foreach ($gen as $key => $value) {
    echo "{$key} - {$value}\n";
}

/*$gen = gen3();
$gen->rewind();
echo $gen->key().' - '.$gen->current()."\n";
echo $gen->send("send value - ");*/


/*function gen4(){
    $id = 2;
    $id++;
    $id = yield $id;
    echo $id;
}

$gen = gen4();
$gen->send($gen->current() + 3);*/


/*function xrange($start, $limit, $step = 1) {
    for ($i = $start; $i <= $limit; $i += $step) {
        yield $i + 1 => $i; // 关键字 yield 表明这是一个 generator
    }
}

// 我们可以这样调用
foreach (xrange(0, 10, 2) as $key => $value) {
    printf("%d %d\n", $key, $value);
}*/