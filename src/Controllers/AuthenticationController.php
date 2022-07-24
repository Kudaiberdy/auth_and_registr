<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Database\Connection;

class AuthenticationController
{
    public static function verify($app, Request $request, Response $response)
    {
        $userData = $request->getParsedBodyParam('user');
        $dbconect = new Connection();
        $user = $dbconect->find($userData['email']);

        if (!$user) {
            $app->get('flash')->addMessageNow('error', 'User with the specified email will not be found');
            $params = [
                'userData' => $userData,
                'flash' => $app->get('flash')->getMessages()
            ];
            unset($userData['password']);
            return $app->get('renderer')->render($response, 'forms/auth.phtml', $params);
        }

        if ($userData['password'] !== $user[0]['password']) {
            $app->get('flash')->addMessageNow('error', 'Password is invalid');
            $params = [
                'userData' => $userData,
                'flash' => $app->get('flash')->getMessages()
            ];
            unset($userData['password']);
            return $app->get('renderer')->render($response, 'forms/auth.phtml', $params);
        }

        $_SESSION['user'] = 'authentificated';
        $app->get('flash')->addMessage('success', 'You has been successfully authorized');
        return $response->withRedirect('/');
    }
}
