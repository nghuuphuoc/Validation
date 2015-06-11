--TEST--
The Not rule must deny any rule
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

$rule = v::create()
    ->not(v::match('/^[a-z]{3}$/'));

var_dump($rule('abc'));
?>
--EXPECTF--
bool(false)
