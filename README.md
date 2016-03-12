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
use Ignaszak\Router\Start;
use Ignaszak\Router\Client;

include __DIR__ . '/autoload.php';

// Set router instnce
$router = Start::instance();

// Set baseURI - optional (default gets value from $_SERVER['SERVER_NAME'])
// $router->baseURI = 'http://example.com';

// Add route by calling Start::add(string $name, string $pattern)
// Name is not required but if is defined it must be unique for each defined routes
// It is possible to use regular expression
$router->add('name1', 'pattern/anotherPattern/([a-z]+)/');
$router->add(null, 'pattern/anotherPattern/([a-z]+)/'); // No defined name

// Add token for route
$router->add('name2', 'route/{alias}.{format}')
    ->controller('AnyController') // define controller class name
    ->token('format', '(html|xml)') // token avilable only local
    ->tokens([ // add many tokens in array
        'token' => 'pattern'
    ]);

// Define global tokens (local tokens overrides global tokens)
$router->addToken('alias', '\w+'); // token avilable for all routes
$router->addTokens([
    'token' => 'pattern',
]);

// Define controller from route
$router->add(null, 'route/{controller}/{action}/')
    ->controller('\\Namespace\\{controller}::{action}')
    ->tokens([
        'controller' => '([a-zA-Z]+)',
        'action' => '([a-zA-Z]+)'
    ]);

// Add defined regular expressions
// Router provides some defined regular expressions such as:
//   @base  - use to define default route
//   @404   - not found
//   @digit - digits [0-9]
//   @alpha - alphabetic characters [A-Za-z_-]
//   @alnum - alphanumeric characters [A-Za-z0-9_-]
$router->add('name3', 'route/{page}/{post}/')->token('page', '@digit');
$router->addToken('post', '@alnum');

// Define custom regular expression. It will be avilable for all routes
$router->addPattern('custom', '([a-z]{2,5})');
$router->add('routeWithCustomRegEx', 'route/@custom/');

// Add default route
$router->add('default', '@base')->controller('DefaultController');

// Not found
$router->add('error', '@404')->controller('ErrorController');

// Add attachment
$router->add('attachment', 'attach/{name}/([a-z]+)/{post}/@digit/')
    ->token('name', '@alpha')
    ->attach(function ($name, $string, $post, $digit) {
        echo "{$name}, {$string}, {$post}, {$digit}";
    });

// Initialize router
$router->run();

// Get request
// Display matched routes
echo 'Routes:<pre>';
print_r(Client::getRoutes());
echo '</pre>';

// Get concrete route
echo Client::getRoute('tokenName');

// Get route name
echo 'Route name: ';
echo Client::getName();
echo '<br />';

// Get route controller
echo 'Controller: ';
echo Client::getController();
echo '<br />';

// Get attachment
$attachment = Client::getAttachment();
$attachment();

// Get link
echo 'Link: ';
echo Client::getLink('attachment', [
    'name' => 'Tomek'
]);
```
