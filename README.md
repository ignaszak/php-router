# ignaszak/router

Router based on mod rewrite module

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

```php
// This method returns an array of all matched parameters
Ignaszak\Router\Client::getAllRoutes();
```
For these added routs:
```php
$router->add('viewpost', 'post/firstpost', 'controller');
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
