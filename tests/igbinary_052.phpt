--TEST--
Object Serializable interface can be serialized in references
--SKIPIF--
--FILE--
<?php 
if(!extension_loaded('igbinary')) {
	dl('igbinary.' . PHP_SHLIB_SUFFIX);
}

function test($variable) {
	$serialized = igbinary_serialize($variable);
	$unserialized = igbinary_unserialize($serialized);
}

class Obj implements Serializable {
	private static $count = 1;

	public $a;
	public $b;

	function __construct($a, $b) {
		$this->a = $a;
		$this->b = $b;
	}

	public function serialize() {
		$c = self::$count++;
		echo "call serialize\n";
		return pack('NN', $this->a, $this->b);
	}

	public function unserialize($serialized) {
		$tmp = unpack('N*', $serialized);
		$this->__construct($tmp[1], $tmp[2]);
		$c = self::$count++;
		echo "call unserialize\n";
	}
}

function main() {
	$a = new Obj(1, 0);
	$b = new Obj(42, 43);
	$variable = [&$a, &$a, $b];
	$serialized = igbinary_serialize($variable);
	printf("%s\n", bin2hex($serialized));
	$unserialized = igbinary_unserialize($serialized);
	var_dump($unserialized);
	$unserialized[0] = 'A';
	var_dump($unserialized[1]);
}
main();
--EXPECTF--
call serialize
call serialize
00000002140306002517034f626a1d080000000100000000060125220106021a001d080000002a0000002b
call unserialize
call unserialize
array(3) {
  [0]=>
  &object(Obj)#%d (2) {
    ["a"]=>
    int(1)
    ["b"]=>
    int(0)
  }
  [1]=>
  &object(Obj)#%d (2) {
    ["a"]=>
    int(1)
    ["b"]=>
    int(0)
  }
  [2]=>
  object(Obj)#%d (2) {
    ["a"]=>
    int(42)
    ["b"]=>
    int(43)
  }
}
string(1) "A"
