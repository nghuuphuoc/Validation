--TEST--
Must validate when there is no rules in the chain
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

$rule = v::create();

var_dump($rule(null));
?>
--EXPECTF--
bool(true)
