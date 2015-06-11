--TEST--
NotOptional rule must must not consider "NULL" as optional
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

$rule = v::create()
    ->match('/[a-z]/')
    ->notOptional();

var_dump($rule(null));
?>
--EXPECTF--
bool(false)
