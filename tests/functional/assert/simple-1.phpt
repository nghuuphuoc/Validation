--TEST--
Should throw validator exception when asserting and display default message
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Exceptions\ValidatorException;
use Respect\Validation\Validator as v;

try {
    v::create()
        ->match('/^[a-z]{3}$/')
        ->assert('123');
} catch (ValidatorException $exception) {
    echo $exception->getMessage().PHP_EOL;
}
?>
--EXPECTF--
All rules must pass for '123'
