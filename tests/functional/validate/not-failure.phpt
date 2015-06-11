--TEST--
The Not rule must deny any rule
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

var_dump(
    v::create()
        ->not(v::match('/^[a-z]{3}$/'))
        ->validate('abc')
);
?>
--EXPECTF--
bool(false)
