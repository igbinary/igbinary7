--TEST--
Correctly unserialize cyclic object references
--SKIPIF--
--INI--
igbinary.compact_strings = On
--FILE--
<?php
$a = new stdClass();
$a->foo = &$a;
$a->bar = &$a;
$b = new stdClass();
$b->cyclic = &$a;
printf("%s\n", serialize($b));
$ig_ser = igbinary_serialize($b);
printf("%s\n", bin2hex($ig_ser));
$ig = igbinary_unserialize($ig_ser);
printf("%s\n", serialize($ig));
var_dump($ig);
$f = &$ig->cyclic->foo;
$f = 'V';
var_dump($ig);
// Note: While the php7 unserializer consistently makes a distinction between refs to an object and non-refs,
// the php5 serializer does not yet.
--EXPECTF--
O:8:"stdClass":1:{s:6:"cyclic";O:8:"stdClass":2:{s:3:"foo";R:2;s:3:"bar";R:2;}}
000000021708737464436c617373140111066379636c6963251a0014021103666f6f2522011103626172252201
O:8:"stdClass":1:{s:6:"cyclic";O:8:"stdClass":2:{s:3:"foo";R:2;s:3:"bar";R:2;}}
object(stdClass)#3 (1) {
  ["cyclic"]=>
  &object(stdClass)#4 (2) {
    ["foo"]=>
    *RECURSION*
    ["bar"]=>
    *RECURSION*
  }
}
object(stdClass)#3 (1) {
  ["cyclic"]=>
  &string(1) "V"
}
