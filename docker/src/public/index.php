<?php
header("X-ERROR: 0");
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
header("X-ERROR: 1");
define('LARAVEL_START', microtime(true));
header("X-ERROR: 2");
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

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}
header("X-ERROR: 3");
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

require __DIR__.'/../vendor/autoload.php';
header("X-ERROR: 4");
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

$app = require_once __DIR__.'/../bootstrap/app.php';
header("X-ERROR: 5");
$kernel = $app->make(Kernel::class);
header("X-ERROR: 6");
$response = $kernel->handle(
    $request = Request::capture()
)->send();
header("X-ERROR: 7");
$kernel->terminate($request, $response);
