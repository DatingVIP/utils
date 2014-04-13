utils
=====

Various utilitiy classes

Env
===

Yet another environment utility class. Once it is setup with map of environment
names to (array of) regular expression(s) to compare server HTTP host to, it
provides a couple of methods to check where are we executing code. Environments
can tagged as `debug`.

With no setup provided `production` environment is considered default, and
`development` is tagged as `debug`.

It is considered that HTTP host belongs to a single environment, and setup
ordering defines precedence if a host name matches multiple regular expressions.

Class also provides methods to check if we are running in CLI or Web. HTTPS
checker is available as well.

```php
require_once 'vendor/autoload.php';

use DatingVIP\utils\Env;

Env::setup ([
	'development' => ['/\.dev$/', '/\.test$/'], // just to display multiple regexes
	'staging' => '/^dev-/',
	'scary' => '/^hitchcock/',
	'production' => '/\.com$/',
]);

var_dump (Env::isDevelopment ('www.mysite.dev'));
var_dump (Env::isStaging ('dev-www.mysite.com'));
var_dump (Env::isScary ('hitchcock.mysite.com')); // not production, precedence
var_dump (Env::isProduction ('www.mysite.com'));

if (Env::debug ())
{
	// i can haz debug!
}

if (Env::isHTTPS ())
{
	// much secure
}

if (Env::isCLI ())
{
	// type type type
}
```