--TEST--
Should throw validator exception when asserting and display full message
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Exceptions\ValidatorException;
use Respect\Validation\Validator as v;

try {
    v::create()
        ->not(v::match('/^.+$/'))
        ->assert(123456);
} catch (ValidatorException $exception) {
    echo $exception->getFullMessage().PHP_EOL;
}
?>
--EXPECTF--
- All rules must pass for 123456
  - 123456 must not match `/^.+$/`
