<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthenticationController
{
    public static function verify($app, Request $request, Response $response, $users)
    {
        $userData = $request->getParsedBodyParam('user');
        $user = collect($users)->first(function ($user) use ($userData) {
            return $user['name'] === $userData['name']
                && hash('sha256', $userData['password']) === $user['passwordDigest'];
        });

        if (!$user) {
            $app->get('flash')->addMessageNow('error', 'Wrong password or name');
            $params = [
                'userData' => $userData,
                'flash' => $app->get('flash')->getMessages()
            ];
            unset($userData['password']);
            return $app->get('renderer')->render($response, 'auth.phtml', $params);
        }

        $_SESSION['user'] = $user;
        $app->get('flash')->addMessage('success', 'You has been successfully authorized');
        return $response->withRedirect('/');
    }
}
