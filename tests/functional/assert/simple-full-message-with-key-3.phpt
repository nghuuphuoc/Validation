--TEST--
Should throw validator exception when asserting and display full message
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Exceptions\ValidatorException;
use Respect\Validation\Validator as v;

try {
    v::create()
        ->key('host', v::match('/\d+/'), true)
        ->key('user', v::match('/\d+/'), true)
        ->key('password', v::match('/\d+/'), true)
        ->key('schema', v::match('/\d+/'), true)
        ->assert([
            'host' => 'my_host',
            'schema' => 'my_schema',
        ]);
} catch (ValidatorException $exception) {
    echo $exception->getFullMessage().PHP_EOL;
}
?>
--EXPECTF--
- All rules must pass for `Array`
  - host must match `/\d+/`
  - user key must be present
  - password key must be present
  - schema must match `/\d+/`
