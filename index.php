<?php

require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use DI\Container;
use App\Controller\{
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

$app->get('/apage')

$app->post('/', function (Request $request, Response $response) use ($users) {
    $userData = $request->getParsedBodyParam('user');
    $user = collect($users)->first(function ($user) use ($userData) {
        return $user['name'] === $userData['name']
            && hash('sha256', $userData['password']) === $user['passwordDigest'];
    });

    if (!$user) {
        $this->get('flash')->addMessageNow('error', 'Wrong password or name');
        $params = [
            'userData' => $userData,
            'flash' => $this->get('flash')->getMessages()
        ];
        return $this->get('renderer')->render($response, 'auth.phtml', $params);
    }

    $_SESSION['user'] = $user;
    $this->get('flash')->addMessage('success', 'You has been successfully authorized');
    return $response->withRedirect('/');
});

$app->delete('/', function (Request $request, Response $response) {
    $_SESSION = [];
    session_destroy();
    return $response->withRedirect('/');
});

$app->get('/session', function (Request $request, Response $response) {
    $params = [
        'userData' => [],
        'flash' => []
    ];
    return $this->get('renderer')->render($response, 'auth.phtml', $params);
});

$app->get('/create', function (Request $request, Response $response) {
    return $this->get('renderer')->render($response, 'create.phtml');
});

$app->run();
