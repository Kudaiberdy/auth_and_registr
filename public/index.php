<?php

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\Container;
use App\Controller\{
    AuthenticationController
};

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../resources/views/');
});
$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'main.phtml');
});

$app->post('/', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'main.phtml');
});

$app->get('/session', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'auth.phtml');
});

$app->get('/create', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'registration.phtml');
});
$app->run();
