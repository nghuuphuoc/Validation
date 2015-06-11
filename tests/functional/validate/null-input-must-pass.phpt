--TEST--
Type "NULL" must be considered as optional
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

var_dump(
    v::create()
        ->match('/[a-z]/')
        ->validate(null)
);
?>
--EXPECTF--
bool(true)
