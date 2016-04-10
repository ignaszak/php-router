# ignaszak/router

[![Build Status](https://travis-ci.org/ignaszak/php-router.svg?branch=master)](https://travis-ci.org/ignaszak/php-router) [![Coverage Status](https://coveralls.io/repos/github/ignaszak/php-router/badge.svg?branch=master)](https://coveralls.io/github/ignaszak/php-router?branch=master)

Simple object oriented PHP Router

## Installing

The package is avilable via [Composer/Packagist](https://packagist.org/packages/ignaszak/router), so just add following lines to your composer.json file:

```json
"require" : {
    "ignaszak/router" : "*"
}
```

or:

```sh
php composer.phar require ignaszak/router
```
## Configuration
The easiest way is to configure mod_rewrite via .htaccess file in site base directory. Example:

```
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php [L]
```
## Running Tests

Just run phpunit from the working directory

```sh
php phpunit.phar
```

## Usage

### Demo
```php
use Ignaszak\Router\Collection\Route;
use Ignaszak\Router\Router;
use Ignaszak\Router\Conf\Host;

include __DIR__ . '/autoload.php';

// Define routes
$route = Route::start();
$route->add('name', '/test/{test}/{id}/{globlToken}', 'GET|POST')
    ->tokens([
        'test' => '(\w+)',
        'id' => '(\d+)'
    ]);
$route->get('get', '/get/test')->controller('AnyController');
$route->post('post', '/post/{name}')
    ->tokens(['name' => '([a-z]+)'])
    ->attach(function ($name) {
        echo $name;
    });
$route->addTokens(['globalToken' => '([0-9]+)']);

// Match routes
$router = new Router($route);
$response = $router->run(new Host());
$response->getParams();
```

### Create routes

#### Add routes
```php
use Ignaszak\Router\Collection\Route;

include __DIR__ . '/autoload.php';

$route = Route::start();

// Define name (is not required but if is defined it must be unique for each defined routes),
// pattern and http method (it is possible to combine all http methods e.g.:
// 'GET|POST', not required, if is empty - route match for all methods).
$route->add('name', '/test/(\w+)/', 'GET');

// There are two more add methods:
$route->get(null, '/match/only/get');
$route->post(null, '/match/only/post');
```

#### Add tokens
```php
$route->add(null, '/test/{test}/{name}/{id}')->tokens([
    'test' => '(\w+)',
    'name' => '(\w+)',
    'id' => '(\d+)'
]);
```

#### Add controller
```php
$route->add('user', '/user')->controller('UserController');

// Define controller from route
$route->add(null, '/test/{controller}/{action}')
    ->controller('\\Namespace\\{controller}::{action}')
    ->tokens([
        'controller' => '([a-zA-Z]+)',
        'action' => '([a-zA-Z]+)'
    ]);
```

#### Add attachment
```php
$route->add('attach', '/attach/{name}/(\w+)/{id}/')
    ->tokens([
        'name' => '(\w+)',
        'id' => '(\d+)'
    ])->attach(function ($name, $string, $id) {
        echo "{$name}, {$string}, {$id}";
    });

// Disable auto calling
$route->add(null, '/attach/test/')->attach(function () {
    /* Do something */
}, false);
```

#### Group routes
```php
// Every added route after this method will be in the same group
$route->group('groupName');
// Disable group
$route->group();
```

#### Add defined patterns
Router provides some defined regular expressions such as:
* *@base* - use to define default route
* *@notfound* - not found
* *@digit* - digits [0-9]
* *@alpha* - alphabetic characters [A-Za-z_-]
* *@alnum* - alphanumeric characters [A-Za-z0-9_-]
```php
$route->add('defined', '/regex/@alpha/{id}')->tokens(['id' => '@digit']);

// Add default route
$route->add('default', '/@base')->controller('DefaultController');

// Not found
$route->add('error', '/@notfound')->attach(function () {
    throw new Exception('404 Not Found');
});
```

#### Add global tokens and patterns
Global tokens and patterns are avilable for all routes
```php
// Global tokens
$route->addTokens([
    'slug' => '(\w+)',
    'user' => '(\w+)',
    'page' => '(\d+)'
]);

// Create new patterns
$route->addPatterns([
    'day' => '([0-9]{2})',
    'month' => '([0-9]{2})',
    'year' => '([0-9]{4})'
]);
// Example: $route->add(null, '/@year/@month/@day/');
```

### Create router
```php
use Ignaszak\Router\Collection\Route;
use Ignaszak\Router\Router;

include __DIR__ . '/autoload.php';

$route = Route::start();
/* Define routes */

// Add defined routes to router
$router = new Router($route);

```

#### Parse
```php
use Ignaszak\Router\Collection\Route;
use Ignaszak\Router\Router;
use Ignaszak\Router\Conf\Host;

include __DIR__ . '/autoload.php';

$route = Route::start();
/* Define routes */
$router = new Router($route);

// Parse routes and get response
$response = $router->run(new Host());

// Or define custom request and http method
$response = $router->run(null, '/custom/request', 'GET');

```
##### Host class
```php
new Host([string $baseQuery]);
```
Class provides current request and http method. Argument *$baseQuery* defines folder via site is avilable e.g.:
```http://localhost/~user/ => $baseQuery = /~user``` (without slash on end).

#### Get response
```php
$response = $router->run(new Host());

// Get route name
$response->getName();
// Get route controller
$response->getController();
// Get attachment
$attachment = $response->getAttachment();
$attachment();
// Get route group
$response->getGroup();
// Get matched params in array
$response->getParams();
// Get concrete param
$response->getParam('token');
```

#### Get link
Link can be generated for any defined route with name. Example: ```$route->get('user', '/user/{name}')->token('name', '@alpha');```
```php
$response->getLink('user', [
    'name' => 'UserName'
]);
```
Output, if ```Host()``` class is used: ```http://servername/user/UserName```, or for custom request: ```/user/UserName```.

#### ResponseStatic

Response is also avilable via static methods
```php
use Ignaszak\Router\ResponseStatic;

include __DIR__ . '/autoload.php';

ResponseStatic::getName();
ResponseStatic::getController();
ResponseStatic::getAttachment();
ResponseStatic::getGroup();
ResponseStatic::getParams();
ResponseStatic::getParam('token');
ResponseStatic::getLink('user', [
    'name' => 'UserName'
]);
```

### Load routes from Yaml

#### Yaml file example

You can define routes, global tokens and patterns. Attachment is not available in yaml file.
**example.yml:**
```yaml
routes:
    test:
        path: '/test/{controller}/{action}'
        method: GET
        controller: '\Namespace\{controller}::{action}'
        group: groupName
        tokens:
            controller: '@custom'
    default:
        path: /@base
        controller: DefaultController
    error:
        path: /@notfound
        controller: ErrorController

tokens:
    action: '@alnum'

patterns:
    custom: ([a-zA-Z]+)
```

#### Yaml class

```php
use Ignaszak\Router\Collection\Yaml;
use Ignaszak\Router\Router;

$yaml = new Yaml();
// Add yaml files
$yaml->add('example.yml');
$yaml->add('anotherExample.yml');

$router = new Router($yaml);
/* Run router and get response */
```

### Cache

It is possible to generate cache of routes defined in yaml file or Route class. Cache stores converted routes to regex, so it is no need to read yaml file and convert routes at every request.

```php
use Ignaszak\Router\Collection\Yaml;
use Ignaszak\Router\Collection\Cache;
use Ignaszak\Router\Router;

$yaml = new Yaml();
$yaml->add('example.yml');

$cache = new Cache($yaml);
$cache->tmpDir = __DIR__; // Define custom tmp dir - optional

$router = new Router($cache);
/* Run router and get response */
```
