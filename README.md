# ignaszak/router

Router based on mod_rewrite module

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
use Ignaszak\Router\Route;
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
    ->token('name', '([a-z]+)')
    ->attach(function ($name) {
        echo $name;
    });

// Match routes
$router = new Router($route);
$router->addToken('globalToken', '([0-9]+)');
$response = $router->run(new Host());
$response->getParams();
```

### Create routes

#### Add routes
```php
use Ignaszak\Router\Route;

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
$route->add(null, '/test/{test}/{name}/{id}')
    ->token('test', '(\w+)') // Add one token or
    ->tokens([ // Add many tokens in array
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
$route->add('defined', '/regex/@alpha/{id}')->token('id', '@digit');

// Add default route
$route->add('default', '/@base')->controller('DefaultController');

// Not found
$route->add('error', '/@notfound')->attach(function () {
    throw new Exception('404 Not found');
});
```

### Create router
```php
use Ignaszak\Router\Route;
use Ignaszak\Router\Router;

include __DIR__ . '/autoload.php';

$route = Route::start();
/* Define routes */

// Add defined routes to router
$router = new Router($route);

```

#### Add global tokens and patterns
Global tokens and patterns are avilable for all routes
```php
// Global tokens
$router->addToken('slug', '(\w+)/');
$router->addTokens([
    'user' => '(\w+)',
    'page' => '(\d+)'
]);

// Create new patterns
$router->addPattern('day', '([0-9]{2})');
$router->addPatterns([
    'month' => '([0-9]{2})',
    'year' => '([0-9]{4})'
]);
// Example: $route->add(null, '/@year/@month/@day/');
```

#### Parse
```php
use Ignaszak\Router\Route;
use Ignaszak\Router\Router;
use Ignaszak\Router\Conf\Host;

include __DIR__ . '/autoload.php';

$route = Route::start();
/* Define routes */
$router = new Router($route);
/* Define global tokens */

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
// Get route group
$response->getGroup();
// Get matched params in array
$response->getParams();
// Get concrete param
$response->getParam('token');
$attachment();
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
