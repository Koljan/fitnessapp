<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    private $apiTokenRepo;
    private $authKeyName;

    public function __construct(ApiTokenRepository $apiTokenRepo)
    {
        $this->apiTokenRepo = $apiTokenRepo;
        $this->authKeyName = 'auth';
    }

    public function supports(Request $request)
    {
        return $request->query->get($this->authKeyName) && 'app_api_account' === $request->attributes->get('_route');
    }

    public function getCredentials(Request $request)
    {
        return $request->query->get($this->authKeyName);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $this->apiTokenRepo->findOneBy([
            'token' => $credentials
        ]);

        if (!$token) {
            throw new CustomUserMessageAuthenticationException('Invalid API Token');
        }

        if($token->isExpired()){
            throw new CustomUserMessageAuthenticationException('Token Expired');
        }
        return $token->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ) {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];
        return new JsonResponse($data,Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ) {
    }

    public function start(
        Request $request,
        AuthenticationException $authException = null
    ) {
        throw new \Exception('Test usage'); // did not get it
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
