--TEST--
Must validate when there is no rules in the chain
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Validator as v;

var_dump(
    v::create()
        ->validate(null)
);
?>
--EXPECTF--
bool(true)
