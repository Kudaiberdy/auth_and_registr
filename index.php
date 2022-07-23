<?php

require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use App\Controllers\{
    AuthenticationController
};

session_start();

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer('resources/views/');
});
$container->set('flash', function () {
    return new \Slim\Flash\Messages();
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$app->add(MethodOverrideMiddleware::class);

$users = [
    ['name' => 'admin', 'passwordDigest' => hash('sha256', 'secret')],
    ['name' => 'mike', 'passwordDigest' => hash('sha256', 'superpass')],
    ['name' => 'kate', 'passwordDigest' => hash('sha256', 'strongpass')]
];

$app->get('/', function (Request $request, Response $response) {
    $flash = $this->get('flash')->getMessages();
    $params = [
        'currentUser' => $_SESSION['user'] ?? null,
        'flash' => $flash
    ];
    return $this->get('renderer')->render($response, 'main.phtml', $params);
});

$app->get('/apage', function (Request $request, Response $response) {
    $flash = $this->get('flash')->getMessages();
    $params = [
        'currentUser' => $_SESSION['user'] ?? null,
        'flash' => $flash
    ];
    return $this->get('renderer')->render($response, 'apage.phtml', $params);
});

$router = $app->getRouteCollector()->getRouteParser();

$app->post('/', function (Request $request, Response $response) use ($users) {
    return AuthenticationController::verify($this, $request, $response, $users);
});

$app->delete('/', function (Request $request, Response $response) {
    $_SESSION = [];
    session_destroy();
    return $response->withRedirect('/');
});

$app->get('/session', function (Request $request, Response $response) {
    $params = [
        'user' => [],
        'flash' => []
    ];
    return $this->get('renderer')->render($response, 'auth.phtml', $params);
})->setName('auth');

$app->get('/create', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'create.phtml');
});

$app->run();
