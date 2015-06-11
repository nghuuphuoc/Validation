--TEST--
Type "NULL" must be considered as optional
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

$rule = v::create()
    ->match('/[a-z]/');

var_dump($rule(null));
?>
--EXPECTF--
bool(true)
