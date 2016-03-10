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

## Example

```php
// Set router instnce
$router = Start::instance();

// Set baseURI - optional (default gets value from $_SERVER['SERVER_NAME'])
// $router->baseURI = 'http://www.base.com/';

// Adds route by calling Start::add(string $name, string $pattern)
// Name must be unique for each defined routes
// It is possible to use regular expression
$router->add('name1', 'pattern/anotherPattern/[a-z]*/');

// Adds token for route {tokenName}
$router->add('name2', 'route/{alias}.{format}')
    ->controller('AnyController') // define controller class name
    ->token('format', '(html|xml)'); // token avilable only local
$router->addToken('alias', '\w+'); // token avilable for all routes
// It is posible to override global token

// Adds defined regular expressions
// Router provides some defined regular expressions such as:
//   @default  - use to define default route
//   @digit    - digits [0-9]
//   @alpha    - alphabetic characters [A-Za-z_-]
//   @alnum    - alphanumeric characters [A-Za-z0-9_-]
$router->add('name3', 'route/{page}/{post}/')->token('page', '@digit');
$router->addToken('post', '@alnum');

// Define custom regular expression. It will be avilable for all routes
$router->addPattern('custom', '[a-z]{2,5}');
$router->add('routeWithCustomRegEx', 'route/@custom/');

// Adds default route
// Defult route is active when no routes is match
$router->add('default', '@default')->controller('DefaultController');

// Initialize router
$router->run();

// Get request
// Display matched routes
echo '<pre>';
print_r(Client::getRoutes());
echo '</pre>';

// Get concrete route
echo Client::getRoute('tokenName');

// Get route name
echo Client::getName();

// Get route controller
echo Client::getController();
```
