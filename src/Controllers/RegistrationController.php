<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Database\Connection;

class RegistrationController
{
    public static function registrate($app, Request $request, Response $response)
    {
        $userData = $request->getParsedBodyParam('user');

        $dbconect = new Connection();
        $user = ($dbconect->find($userData['email']));
        if ($user) {
            $app->get('flash')->addMessageNow('error', 'User with this email address already exists');
            $params = [
                'userData' => $userData,
                'flash' => $app->get('flash')->getMessages()
            ];
            unset($userData['password']);
            return $app->get('renderer')->render($response, 'forms/create.phtml', $params);
        }

        $dbconect->insert($userData);
        $_SESSION['user'] = 'authentificated';
        $app->get('flash')->addMessage('success', 'You account has been successfully created');
        return $response->withRedirect('/');
    }
}