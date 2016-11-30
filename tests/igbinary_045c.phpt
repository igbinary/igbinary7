--TEST--
APCu serializer registration - more data types
--SKIPIF--
<?php
if (!extension_loaded('apcu')) {
	echo "skip APCu not loaded";
}

$ext = new ReflectionExtension('apcu');
if (version_compare($ext->getVersion(), '4.0.2', '<')) {
	echo "skip require APCu version 4.0.2 or above";
}

--INI--
apc.enable_cli=1
apc.serializer=igbinary
--FILE--
<?php
echo ini_get('apc.serializer'), "\n";

class Bar {
	public $foo;
}

$a = new Bar;
$a->foo = $a;
apcu_store('objloop', $a);
unset($a);

var_dump(apcu_fetch('objloop'));

apcu_store('nullval', null);
var_dump(apcu_fetch('nullval'));

apcu_store('intval', 777);
var_dump(apcu_fetch('intval'));

$o = new stdClass();
$o->prop = 5;
$a = [$o, $o];
printf("%s\n", bin2hex(igbinary_serialize($a)));
apcu_store('simplearrayval', $a);
var_dump();
$unserialized = apcu_fetch('simplearrayval');
var_dump($unserialized);
var_dump($unserialized[0] === $unserialized[1]) ? 'SAME' : 'DIFFERENT');
unset($o);
unset($a);
unset($unserialized);

$o = new stdClass();
$o->prop = 6;
$a = [&$o, &$o];
apcu_store('refarrayval', $a);
$unserialized = apcu_fetch('refarrayval');
var_dump($unserialized);
var_dump($unserialized[0] === $unserialized[1]) ? 'SAME' : 'DIFFERENT');

--EXPECTF--
igbinary
object(Bar)#%d (1) {
  ["foo"]=>
  *RECURSION*
}
NULL
int(777)
array(2) {
  [0]=>
  object(stdClass)#%d (2) {
    ["prop"]=>
    int(5)
  }
  [1]=>
  object(stdClass)#%d (2) {
    ["prop"]=>
    int(5)
  }
}
SAME
array(2) {
  [0]=>
  &object(stdClass)#%d (1) {
    ["prop"]=>
    int(6)
  }
  &object(stdClass)#%d (1) {
    ["prop"]=>
    int(6)
  }
}
SAME
