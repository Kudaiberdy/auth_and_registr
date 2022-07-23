<?php

require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\Container;
use App\Controller\{
    AuthenticationController
};

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer('resources/views/');
});
$app = AppFactory::createFromContainer($container);
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'main.phtml');
});

$app->delete('/', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'main.phtml');
});

$app->post('/apage', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'apage.phtml');
});

$app->get('/session', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'auth.phtml');
});

$app->get('/create', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'create.phtml');
});

$app->run();
