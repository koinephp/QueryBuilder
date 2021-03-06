# Koine Query Builder

Query Builder for easing the SQL composing

Code information:

[![Build Status](https://travis-ci.org/koinephp/QueryBuilder.png?branch=master)](https://travis-ci.org/koinephp/QueryBuilder)
[![Coverage Status](https://coveralls.io/repos/koinephp/QueryBuilder/badge.png)](https://coveralls.io/r/koinephp/QueryBuilder)
[![Code Climate](https://codeclimate.com/github/koinephp/QueryBuilder.png)](https://codeclimate.com/github/koinephp/QueryBuilder)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/koinephp/QueryBuilder/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/koinephp/QueryBuilder/?branch=master)

Package information:

[![Latest Stable Version](https://poser.pugx.org/koine/query-builder/v/stable.svg)](https://packagist.org/packages/koine/query-builder)
[![Total Downloads](https://poser.pugx.org/koine/query-builder/downloads.svg)](https://packagist.org/packages/koine/query-builder)
[![Latest Unstable Version](https://poser.pugx.org/koine/query-builder/v/unstable.svg)](https://packagist.org/packages/koine/query-builder)
[![License](https://poser.pugx.org/koine/query-builder/license.svg)](https://packagist.org/packages/koine/query-builder)
[![Dependency Status](https://gemnasium.com/koinephp/QueryBuilder.png)](https://gemnasium.com/koinephp/QueryBuilder)


## Installing

### Installing via Composer
Append the lib to your requirements key in your composer.json.

```javascript
{
    // composer.json
    // [..]
    require: {
        // append this line to your requirements
        "koine/query-builder": "dev-master"
    }
}
```

### Alternative install
- Learn [composer](https://getcomposer.org). You should not be looking for an alternative install. It is worth the time. Trust me ;-)
- Follow [this set of instructions](#installing-via-composer)

## Usage

### SELECT

This is an example of select query. 

- Applies [limit](#limit)
- Applies [where](#where)
- Applies [orderBy](#order-by)
- Applies [groupBy](#group-by)
- Applies [placeholders](#using-placeholders)

```php
$fields = array('u.name AS name', 'r.name AS role');

// Selecting via factory
$select = Koine\QueryBuilder::factorySelect($fields);

// Selecting via the select method
$select = Koine\QueryBuilder::factorySelect()
    ->select($fields);

// or alternatively
$select = new Koine\QueryBuilder\Statements\Select();
$select->select($fields);

// From
$select->from('users u');

// Adding joins
$select->innerJoin('roles r', 'u.id = r.user_id');

$select->toSql();

// SELECT u.name AS name, r.name AS role 
// FROM users u INNER JOIN roles r ON u.idi = r.user_id
```

### INSERT
- Applies [placeholders](#using-placeholders)

```php
// Using the factory
$insert = Koine\QueryBuilder::insert();

// Or alternatively
$insert = new Koine\QueryBuilder\Statements\Insert();

$insert->into('users')->values(array(
    'name'  => 'Jon Doe',
    'email' => 'jon@doe.com'
));

$insert->toSql();

// INSERT INTO users (name, email) VALUES ('Jon Doe', 'jon@doe.com');
```


### UPDATE
- Applies [limit](#limit)
- Applies [where](#where)
- Applies [orderBy](#order-by)
- Applies [groupBy](#group-by)
- Applies [placeholders](#using-placeholders)

```php
$update = Koine\QueryBuilder::update('users');

// or
$update = new Koine\QueryBuilder\Statements\Update;
$update->table('users');

// setting values and conditions

$update->set(array(
        'enabled' => 1
    ))->where('email', ':email');

$update->toSql(array(
    'email' => 'admin@email.com'
));

// UPDATE users SET enabled = 1 WHERE email = 'admin@email.com'
```

### DELETE

TODO: Implement

### WHERE

Every time a ```where()``` method is called, the condition is added to the query.


```php

// method signature
$query->where($field, $value, $operator);

// or
$query->where($condition);

// or
$query->where(array(
    array($field, $value, $operator),
    array($condition),
));

// Below some valid examples:

$query->where('email', 'admin@abc.com');
// WHERE email = 'admin@abc.com'

$query->where('email', 'admin@abc.com', '<>');
// WHERE email <> "admin@abc.com"

$query->where('email', '%@google.com', 'LIKE');
// WHERE email LIKE "%@google.com"

$query->where('age', 20);
// WHERE age = 20

$query->where('code', 001);
// WHERE code = 001

$query->where('code', array('value' => '001'));
// WHERE code = '001'

$query->where('(code = 1 OR code = 2)'));
// WHERE (code = 1 OR code = 2)

// multiple conditioins, one method call
$query->where(array(
    array('email', 'admin@abc.com', '<>'),
    array('email', '%@google.com', 'LIKE'),
    array('age', 20),
    array('(code = 1 OR code = 2)'),
    array('hash', array('value' => 'SOMEFUNCTION()')),
));

// WHERE condition 1 AND condition 2..
```

### ORDER BY
```php
$query->orderBy('name DESC');
// or
$query->orderBy(array('name DESC', 'age ASC'));
```

### GROUP BY
```php
$query->groupBy('a, b, c');
// or
$query->groupBy(array('a', 'b', 'b'));
```

### LIMIT
```php
$query->limit(2);
$query->limit(2, 1);
```

### Using placeholders

Placeholders are a good way for building your queries when you don't know what values are going to be used (because they depend on the result of a query yet to be executed, for instance).

```php
$insert->into('users')->values(array(
    'name'  => ':name',
    'email' => ':email'
));

$insert->toSql(array(
    'name'  => 'Jon Doe',
    'email' => 'jon@doe.com'
));

// INSERT INTO users (name, email) VALUES ('Jon Doe', 'jon@doe.com');
```

## Issues/Features proposals

[Here](https://github.com/koinephp/query-builder/issues) is the issue tracker.

## Contributing

Only TDD code will be accepted. Please follow the [PSR-2 code standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request

### How to run the tests:

```bash
phpunit --configuration tests/phpunit.xml
```

### To check the code standard run:

```bash
phpcs --standard=PSR2 lib
phpcs --standard=PSR2 tests

# alternatively

./bin/travis/run_phpcs.sh
```

## License
[MIT](MIT-LICENSE)

## Authors

- [Marcelo Jacobus](https://github.com/mjacobus)

