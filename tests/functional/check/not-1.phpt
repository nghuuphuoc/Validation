--TEST--
Should throw the child rule exception when "Not" rule fails
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Exceptions\MatchException;
use Respect\Validation\Validator as v;

try {
    v::create()
        ->not(v::match('/^[a-z]{3}$/'))
        ->check('abc');
} catch (MatchException $exception) {
    echo $exception->getMessage().PHP_EOL;
}
?>
--EXPECTF--
'abc' must not match `/^[a-z]{3}$/`
