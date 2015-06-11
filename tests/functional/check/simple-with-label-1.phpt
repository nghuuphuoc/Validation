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
        ->setLabel('Pattern')
        ->check('123');
} catch (MatchException $exception) {
    echo $exception->getMessage().PHP_EOL;
}
?>
--EXPECTF--
Pattern must match `/^[a-z]{3}$/`
