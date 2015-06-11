--TEST--
NotOptional rule must must not consider "NULL" as optional
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

var_dump(
    v::create()
        ->match('/[a-z]/')
        ->notOptional()
        ->validate(null)
);
?>
--EXPECTF--
bool(false)
