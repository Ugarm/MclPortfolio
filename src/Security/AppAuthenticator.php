<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Routing\RouterInterface;

class AppAuthenticator extends AbstractGuardAuthenticator implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function supports(Request $request)
    {
        // Here you can check whether the request has a valid login form submission
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    public function getCredentials(Request $request): array
    {
        // Extract credentials from the login form (email, password)
        return [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
        ];
    }

    public function getUser($credentials, UserInterface $userProvider)
    {
        // Get the user from the database based on email
        return $userProvider->loadUserByUsername($credentials['email']);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // Check the password
        return password_verify($credentials['password'], $user->getPassword());
    }

    public function onAuthenticationSuccess(Request $request, UserInterface $user, string $providerKey): Response
    {
        // Redirect user to the admin page on successful authentication
        return new RedirectResponse($this->router->generate('admin_dashboard')); // Change 'admin_dashboard' to your actual route
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        // Handle authentication failure (e.g., display error message)
        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // Redirect to login page if authentication fails
        return new RedirectResponse($this->router->generate('app_login'));
    }

    public function supportsRememberMe(): bool
    {
        // Optionally enable "remember me" functionality
        return false;
    }
}
