--TEST--
Correctly unserialize multiple references in objects
--SKIPIF--
--INI--
igbinary.compact_strings = On
--FILE--
<?php
class Foo{}
$a = new stdClass();
$a->x0 = NULL;
$a->x1 = &$a->x0;
$a->x2 = &$a->x1;
$a->x3 = &$a->x2;
$a->x4 = false;
$a->x5 = &$a->x4;
$a->x6 = new Foo();
$a->x7 = &$a->x6;
$a->x8 = &$a->x7;
$a->x9 = [33];
$a->x10 = new stdClass();
$a->x10->prop = &$a->x8;
$a->x11 = &$a->x10;
$a->x12 = $a->x9;
$ig_ser = igbinary_serialize($a);
printf("ig_ser=%s\n", bin2hex($ig_ser));
$ig = igbinary_unserialize($ig_ser);
$f = &$ig->x3;
$f = 'V';
$g = &$ig->x5;
$g = 'H';
$h = $ig->x10;
$h->prop = 'S';
var_dump($ig);
--EXPECTF--
ig_ser=000000021708737464436c617373140d1102783025001102783125010111027832250101110278332501011102783425041102783525010211027836251703466f6f14001102783725220311027838252203110278391401060006211103783130251a001401110470726f70252203110378313125220511037831320104
object(stdClass)#%d (13) {
  ["x0"]=>
  &string(1) "V"
  ["x1"]=>
  &string(1) "V"
  ["x2"]=>
  &string(1) "V"
  ["x3"]=>
  &string(1) "V"
  ["x4"]=>
  &string(1) "H"
  ["x5"]=>
  &string(1) "H"
  ["x6"]=>
  &string(1) "S"
  ["x7"]=>
  &string(1) "S"
  ["x8"]=>
  &string(1) "S"
  ["x9"]=>
  array(1) {
    [0]=>
    int(33)
  }
  ["x10"]=>
  &object(stdClass)#%d (1) {
    ["prop"]=>
    &string(1) "S"
  }
  ["x11"]=>
  &object(stdClass)#%d (1) {
    ["prop"]=>
    &string(1) "S"
  }
  ["x12"]=>
  array(1) {
    [0]=>
    int(33)
  }
}
