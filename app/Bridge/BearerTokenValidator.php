<?php

namespace App\Bridge;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\ValidationData;
use League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class BearerTokenValidator implements AuthorizationValidatorInterface
{
    /** @var AccessTokenRepositoryInterface */
    private $accessTokenRepository;

    /** @var CryptKey */
    protected $publicKey;

    public function __construct(AccessTokenRepositoryInterface $accessTokenRepository)
    {
        $this->accessTokenRepository = $accessTokenRepository;
    }

    public function setPublicKey(CryptKey $key)
    {
        $this->publicKey = $key;
    }

    public function validateAuthorization(ServerRequestInterface $request)
    {
        if ($request->hasHeader('authorization') === false) {
            throw OAuthServerException::accessDenied('Missing "Authorization" header');
        }

        $header = $request->getHeader('authorization');
        $jwt = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $header[0]));

        try {
            $token = (new Parser())->parse($jwt);

            try {
                $publicKey = new Key(
                    $this->publicKey->getKeyPath(),
                    $this->publicKey->getPassPhrase()
                );

                if ($token->verify(new Sha256(), $publicKey) === false) {
                    throw OAuthServerException::accessDenied('Access token could not be verified');
                }
            } catch (\BadMethodCallException $exception) {
                throw OAuthServerException::accessDenied('Access token is not signed', null, $exception);
            }

            $data = new ValidationData();
            $data->setCurrentTime(time());

            if ($token->validate($data) === false) {
                throw OAuthServerException::accessDenied('Access token is invalid');
            }

            if ($this->accessTokenRepository->isAccessTokenRevoked($token->getClaim('jti'))) {
                throw OAuthServerException::accessDenied('Access token has been revoked');
            }

            return $request
                ->withAttribute('oauth_access_token_id', $token->getClaim('jti'))
                ->withAttribute('oauth_client_id', $token->getClaim('aud'))
                ->withAttribute('oauth_user_id', $token->getClaim('sub'))
                ->withAttribute('oauth_scopes', $token->getClaim('scopes'));

        } catch (OAuthServerException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            throw OAuthServerException::accessDenied($exception->getMessage(), null, $exception);
        }
    }
}
