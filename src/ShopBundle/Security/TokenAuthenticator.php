<?php

namespace ShopBundle\Security;

use ShopBundle\Services\MyJsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    public function __construct(JsonWebToken $jsonWebToken, Container $container)
    {
        $this->container  = $container;
        $this->jwtWebToken = $jsonWebToken;
    }

    public function getCredentials(Request $request)
    {
        $token = $this->jwtWebToken->extractToken($request);

        if (!$token) {
            // No token?
            $token = null;
        }
        // What you return here will be passed to getUser() as $credentials
        return array(
            'token' => $token,
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        $token = $credentials['token'];
        if (null === $token) {
            return;
        }
        $data = $this->jwtWebToken->decodeToken($token);
        $idUser = isset($data['id']) ? $data['id'] : '';

        if(empty($idUser)){
            return;
        }
        // if a User object, checkCredentials() is called
        return $userProvider->loadUserById($idUser);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
         return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
            // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new MyJsonResponse(MyJsonResponse::RSP_EXPIRED_SESSION,"expired session");
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            // you might translate this message
            'message' => 'Authentication Required'
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}