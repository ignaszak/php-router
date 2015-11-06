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
composer require ignaszak/router
```
## Configuration
The easiest way is to configure mod_rewrite via .htaccess file in site base directory. Example:

```
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . {directory}index.php [L]
```
## Running the Tests

Just run phpunit from the working directory

```sh
php phpunit.phar
```

## Example

### Getting started

```php
$router = Ignaszak\Router\Start::instance();
$router->baseURL = 'www.base.url.com';
```

### Add routes

```php
// Add route name, pattern and controller (not required)
$router->add('name', 'post/alias', 'controller');

// Add route with token
$router->add('name', 'post/{token}', 'controller');

// Add token
$router->addToken('token', '([a-z0-9_-]*)');

// Add controller
$router->addController('controller', array('file' => 'file.php'));
```

### Run router

```php
$router->run();
```

### Get variables

#### Get all matched variables in array:

```php
$router->add('viewpost', 'post/firstpost', 'controller');

print_r( Ignaszak\Router\Client::getAllRoutes() );
```

Method will return:

```php
Array
(
    [name] => 'viewpost'
    [controller] => 'controller'
    [route1] => 'post'
    [route2] => 'firstpost'
)
```

For routes with token:

```php
$router->add('viewpost', 'post/{token}', 'controller');

print_r( Ignaszak\Router\Client::getAllRoutes() );
```

Method will return:

```php
Array
(
    [name] => 'viewpost'
    [controller] => 'controller'
    [route1] => 'post'
    [token] => 'firstpost'
)
```

#### Getting single matched route:

```php
// If $route is empty, method will return 'route1'
Ignaszak\Router\Client::getRoute($route = null);
```

#### Getting route name:

```php
Ignaszak\Router\Client::getRouteName();
```

#### Getting controller file path:

```php
Ignaszak\Router\Client::getControllerFile();
```