<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/4/12
 * Time: 10:16
 */
class Number implements Iterator{

    protected $count;
    protected $key;
    protected $value;
    protected $step = 1;

    public function __construct($count)
    {
        echo '第' . $this->step++ . '步，对象初始化<br />';
        $this->count = $count;
    }

    public function current()
    {
        // TODO: Implement current() method.
        echo '第' . $this->step++ . '步，current被调用<br />';
        return $this->value;
    }

    public function key()
    {
        // TODO: Implement key() method.
        echo '第' . $this->step++ . '步，key被调用<br />';
        return $this->key;
    }

    public function next()
    {
        // TODO: Implement next() method.
        echo '第' . $this->step++ . '步，next被调用<br />';
        $this->key += 1;
        $this->value += 2;
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
        $this->key = 0;
        $this->value = 0;
        echo '第' . $this->step++ . '步，rewind被调用<br />';
    }

    public function valid()
    {
        // TODO: Implement valid() method.
        echo '第' . $this->step++ . '步，valid被调用<br />';
        return $this->key < $this->count;
    }
}

$num = new Number(5);
foreach ($num as $key=>$value){
    echo $key . '---' . $value . "\n";
}