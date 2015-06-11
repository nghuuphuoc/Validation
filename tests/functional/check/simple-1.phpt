--TEST--
Should throw the child rule exception when rule fails
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Exceptions\MatchException;
use Respect\Validation\Validator as v;

try {
    v::create()
        ->match('/^[a-z]{3}$/')
        ->check('123');
} catch (MatchException $exception) {
    echo $exception->getMessage().PHP_EOL;
}
?>
--EXPECTF--
'123' must match `/^[a-z]{3}$/`
