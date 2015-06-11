--TEST--
Should throw validator exception when asserting and display full message
--FILE--
<?php
require 'vendor/autoload.php';

use Respect\Validation\Exceptions\ValidatorException;
use Respect\Validation\Validator as v;

try {
    v::create()
        ->key(
            'mysql',
            v::create()
                ->key('host', v::match('/\d+/'), true)
                ->key('user', v::match('/\d+/'), true)
                ->key('password', v::match('/\d+/'), true)
                ->key('schema', v::match('/\d+/'), true),
            true
        )
        ->key(
            'postgresql',
            v::create()
                ->key('host', v::match('/\d+/'), true)
                ->key('user', v::match('/\d+/'), true)
                ->key('password', v::match('/\d+/'), true)
                ->key('schema', v::match('/\d+/'), true),
            true
        )
        ->assert([
            'mysql' => [
                'host' => 'my_host',
                'schema' => 'my_schema',
            ],
            'postgresql' => [
                'user' => 'my_user',
                'password' => 'my_password',
            ]
        ]);
} catch (ValidatorException $exception) {
    echo $exception->getFullMessage().PHP_EOL;
}
?>
--EXPECTF--
- All rules must pass for `Array`
  - All rules must pass for mysql
    - host must match `/\d+/`
    - user key must be present
    - password key must be present
    - schema must match `/\d+/`
  - All rules must pass for postgresql
    - host key must be present
    - user must match `/\d+/`
    - password must match `/\d+/`
    - schema key must be present
