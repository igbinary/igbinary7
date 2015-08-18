--TEST--
Check for null serialisation
--SKIPIF--
--FILE--
<?php 
if(!extension_loaded('igbinary')) {
	dl('igbinary.' . PHP_SHLIB_SUFFIX);
}

function test($type, $variable) {

	echo $type, "\n";
	$serialized = igbinary_serialize($variable);

	echo substr(bin2hex($serialized), 8), "\n";

	$unserialized = igbinary_unserialize("\0\0\0\x02");
	echo $unserialized === $variable ? 'OK' : 'ERROR';
	echo "\n";
}

test('null', null);

/*
 * you can add regression tests for your extension here
 *
 * the output of your test code has to be equal to the
 * text in the --EXPECT-- section below for the tests
 * to pass, differences between the output and the
 * expected text are interpreted as failure
 *
 * see php5/README.TESTING for further information on
 * writing regression tests
 */
?>
--EXPECT--
null
00
OK
