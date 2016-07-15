--TEST--
igbinary_unserialize causes segfault on 3rd call for objects with dynamic property
--FILE--
<?php
class Obj
{
    public $bar = "test";
}
$value = new Obj();
$value->i = 1;
$igb = igbinary_serialize($value);
for ($i=0; $i < 30; $i++)
{
    // This used to segfault at the third attempt
    echo igbinary_unserialize($igb)->bar . PHP_EOL;
}
--EXPECT--
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
test
