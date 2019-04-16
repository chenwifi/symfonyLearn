<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/16
 * Time: 15:46
 */
function gen() {
    echo "Foo\n";
    try {
        yield 'ddddd';
        var_dump('bigfat');
        yield 'zzzzz';
    } catch (Exception $e) {
        echo "Exception: {$e->getMessage()}\n";
    }
    echo yield 'cccc';
    echo "Bar\n";
}

$gen = gen();
$gen->rewind();// echos "Foo"
echo $gen->current();
//$gen->throw(new Exception('Test')); // echos "Exception: Test"
echo $gen->current();
echo $gen->send('aaa');
// and "Bar"