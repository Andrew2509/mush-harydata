<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

// Prioritize current directory if it's already a valid Laravel root
$laravel_path = __DIR__;

if (!file_exists($laravel_path . '/vendor/autoload.php')) {
    // Try harydata subdirectory if not in root (common cPanel structure)
    $laravel_path = __DIR__ . '/harydata';
    
    if (!file_exists($laravel_path . '/vendor/autoload.php')) {
        header('HTTP/1.1 503 Service Unavailable');
        echo "<h1>Composer Autoloader Not Found</h1>";
        echo "<p>Please ensure that the <b>vendor</b> directory exists and is correctly uploaded.</p>";
        echo "<p>If you have access, run <code>composer install</code> in the project root.</p>";
        echo "<hr>";
        echo "<small>Looking in: " . __DIR__ . "/vendor/autoload.php and " . __DIR__ . "/harydata/vendor/autoload.php</small>";
        exit(1);
    }
}

if (file_exists($maintenance = $laravel_path . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require $laravel_path . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once $laravel_path . '/bootstrap/app.php';

// Bind the public path so that asset() helper works correctly on cPanel
$app->bind('path.public', function() {
    return __DIR__;
});

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);