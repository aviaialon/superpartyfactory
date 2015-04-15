<?php
require_once './Core/Application.php';
$Application = \Core\Application::getInstance(array(
    $_SERVER['DOCUMENT_ROOT'] . getenv('BASE') . '/mvc/config/config.ini'
));
\Core\Net\HttpRequest::getInstance()->run();
exit;

require 'vendor/autoload.php';
require_once './Core/Application.php';

$router = new \Slim\Slim(array(
    'debug'                 => false,
    'mode'                  => getenv('DEVELOPMENT_DOMAIN'),
    'log.level'             => \Slim\Log::DEBUG,
    'log.enabled'           => true,
    'cookies.encrypt'       => true,
    'cookies.lifetime'      => '10 minutes',
    'cookies.path'          => '/',
    'cookies.secure'        => true,
    'cookies.secret_key'    => "!H.q@}v*=6(c!Aky'sJ|w",
    'cookies.cipher'        => MCRYPT_RIJNDAEL_256,
    'cookies.cipher_mode'   => MCRYPT_MODE_CBC,
    'templates.path'        => './views'
));

$router->container->singleton('Application', function() {
    return \Core\Application::getInstance();
});

//
// Configure Development scope
//

// Only invoked if mode is "production"
$router->configureMode('production', function () use ($router) {
    $router->config(array(
        'log.enable'     => true,
        'debug'     => false
    ));
});

// Only invoked if mode is "development"
$router->configureMode('development', function () use ($router) {
    $router->config(array(
        'log.enable'     => false,
        'debug'     => true
    ));

    $router->error(function (\Exception $e) use ($router) {
        var_dump($e);
    });
});

// Temp api key validation
$router->hook('slim.before.dispatch', function () use ($router) {
    $route = $router->router()->getCurrentRoute();
    $router->contentType('application/json');

    // Skip the token validation on index.
    if ($route->getName() === "index") {
        return true;
    }

    // Provide a better validation here... up to you!
    try {
        if ($route->getParam('token') !== "59F1D46E-DC52-11E1-A9DD-B6EE6188709B") {
            $router->halt(403, "Please provide a valid API key");
        }
    } catch (\InvalidArgumentException $tokenNotDefinedException) {
        echo json_encode(array('error' => 'Please provide an API key'));
        $router->stop();
    }
});

//
// Begin Routes
//
$router->get('/', function() use ($router) {
    $router->view->test = 1;
    $router->flash('error', 'Login required');
    var_dump($router->container->Application->getDatabase());
    //$router->redirect($router->urlFor('access-denied'));
    //$router->render('test.php');
    die;
    $router->redirect($router->urlFor('login_hospital'));
})->name('index');


// API group
$router->group('/api/:apiVersion/:token', function($token, $apiVersion) use($router) {
    // Hostpital group
    $router->group('/hospital', function() use ($router, $token, $apiVersion) {
        // TODO: Validate API version here (or API key!)
        // $router->validateApiKey($apiVersion);

        $router->get('/login', function($apiVersion, $token) {
            echo "Hospital api login request [api-version: {$apiVersion} | token: {$token}]";
        })->via('GET')->name('login_hospital');

        // Login request
        $router->get('/login/:id', function($apiVersion, $token, $id = null) {
            echo "Hospital api login request [api-version: {$apiVersion} | id: {$id} | token: {$token}]";
        })->conditions(array(
            'id' => '[0-9]{1,}'
        ))->via('GET', 'POST'); // TODO: Change this to implement the POST version (for login requests);
    });
});


$router->get('/denied', function() use ($router) {
    # Create the data
    $errorData = array('error' => 'Permission Denied');

    # Send a HTTP status of 403
    #$app->render('error.php', $errorData, 403);

    $router->halt(403, 'Permission Denied');

})->name('access-denied');



$router->run();

