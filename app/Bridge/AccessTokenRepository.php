<?php

namespace App\Bridge;

use Illuminate\Contracts\Events\Dispatcher;
use Laravel\Passport\Bridge\AccessTokenRepository as BaseAccessTokenRepository;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;

class AccessTokenRepository extends BaseAccessTokenRepository
{
    public function __construct(TokenRepository $tokenRepository, Dispatcher $events)
    {
        parent::__construct($tokenRepository, $events);
    }

    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        return new AccessToken($userIdentifier, $scopes);
    }
}
