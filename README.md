# ReactPHP MySQL Decorator

A decorator of the [ReactPHP MySQL](https://github.com/friends-of-reactphp/mysql) connection interface which augments its behaviour, e.g. providing binding of associative parameters.

## Installation

Using [Composer](https://getcomposer.org/):

```
$ composer require tonix-tuft/reactphp-mysql-decorator
```

## Usage

Currently, the only available decorator is `BindAssocParamsConnectionDecorator`, which provides the binding of associative parameters like in standard PHP `PDO` objects.

### BindAssocParamsConnectionDecorator

This decorator allows the binding of associative parameters in queries (named parameters). To use it just wrap a `React\MySQL\ConnectionInterface`:

```
<?php

// ...
use ReactPHP\MySQL\Decorator\BindAssocParamsConnectionDecorator;

// ...
$loop = React\EventLoop\Factory::create();
$factory = new Factory($loop);

$uri = 'username:password@localhost/dbname';
$connection = $factory->createLazyConnection($uri); // returns a React\MySQL\ConnectionInterface

$connectionWithBindAssocParams = new BindAssocParamsConnectionDecorator($connection);

// Now you can bind associative parameters when you execute your queries.
$value = 123;
$connectionWithBindAssocParams->query('SELECT * FROM table WHERE field = :value', [
   ':value' => $value
])->then(
   // ...
);

```

## Acknowledgements

[friends-of-reactphp/mysql](https://github.com/friends-of-reactphp/mysql) - Async MySQL database client for ReactPHP

## Authors

**Tonix-Tuft**

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.