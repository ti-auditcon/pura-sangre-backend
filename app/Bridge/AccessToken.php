<?php

namespace App\Bridge;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class AccessToken implements AccessTokenEntityInterface
{
    use EntityTrait, TokenEntityTrait;

    public function __construct($userIdentifier, array $scopes = [])
    {
        $this->setUserIdentifier($userIdentifier);

        foreach ($scopes as $scope) {
            $this->addScope($scope);
        }
    }

    public function convertToJWT(CryptKey $privateKey)
    {
        $now = new \DateTimeImmutable();

        return (new Builder())
            ->setAudience($this->getClient()->getIdentifier())
            ->setId($this->getIdentifier(), false)
            ->setIssuedAt($now)
            ->setNotBefore($now)
            ->setExpiration(\DateTimeImmutable::createFromMutable($this->getExpiryDateTime()))
            ->setSubject($this->getUserIdentifier())
            ->set('scopes', $this->getScopes())
            ->getToken(new Sha256(), new Key($privateKey->getKeyPath(), $privateKey->getPassPhrase()));
    }
}
