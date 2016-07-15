--TEST--
Correctly unserialize multiple object refs and non-refs.
--SKIPIF--
--INI--
igbinary.compact_strings = On
--FILE--
<?php
$a = array(new stdClass());
$a[1] = $a[0];
$a[2] = &$a[1];
$a[3] = $a[0];
var_dump($a);
printf("%s\n", serialize($a));
$ig_ser = igbinary_serialize($a);
printf("%s\n", bin2hex($ig_ser));
$ig = igbinary_unserialize($ig_ser);
printf("%s\n", serialize($ig));
var_dump($ig);
$f = &$ig[2];
$f = 'V';
var_dump($ig);
// Note: While the php7 unserializer consistently makes a distinction between refs to an object and non-refs,
// the php5 serializer does not yet.
--EXPECTF--
array(4) {
  [0]=>
  object(stdClass)#%d (0) {
  }
  [1]=>
  &object(stdClass)#%d (0) {
  }
  [2]=>
  &object(stdClass)#%d (0) {
  }
  [3]=>
  object(stdClass)#%d (0) {
  }
}
a:4:{i:0;O:8:"stdClass":0:{}i:1;R:2;i:2;R:2;i:3;r:2;}
00000002140406001708737464436c61737314000601252201060225220106032201
a:4:{i:0;O:8:"stdClass":0:{}i:1;R:2;i:2;R:2;i:3;r:2;}
array(4) {
  [0]=>
  object(stdClass)#%d (0) {
  }
  [1]=>
  &object(stdClass)#%d (0) {
  }
  [2]=>
  &object(stdClass)#%d (0) {
  }
  [3]=>
  object(stdClass)#%d (0) {
  }
}
array(4) {
  [0]=>
  object(stdClass)#%d (0) {
  }
  [1]=>
  &string(1) "V"
  [2]=>
  &string(1) "V"
  [3]=>
  object(stdClass)#%d (0) {
  }
}
