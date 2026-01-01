<?php

namespace App\AuthContext\Validator;

use App\Shared\Validator\JWTValidatorInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\Clock\SystemClock;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Math\BigInteger;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Contracts\Cache\ItemInterface;
use DateTimeZone;

class CognitoJWTValidator implements JWTValidatorInterface
{
    private string $jwksUrl;
    private CacheItemPoolInterface $cache;
    private readonly LoggerInterface $logger;
    private readonly string $expectedAudience;

    public function __construct(
        CacheItemPoolInterface $cache,
        string $region,
        string $userPoolId,
        string $expectedAudience, // your App client ID
        LoggerInterface $logger = new NullLogger()
    ) {
        $this->logger = $logger;
        $this->cache = $cache;
        $this->expectedAudience = $expectedAudience;
        $this->jwksUrl = sprintf(
            'https://cognito-idp.%s.amazonaws.com/%s/.well-known/jwks.json',
            $region,
            $userPoolId
        );
    }

    public function validate(string $jwt): array
    {
        try {
            // Decode header to get kid
            $header = json_decode($this->urlsafeB64Decode(explode('.', $jwt)[0]), true);
            if (!isset($header['kid'])) {
                throw new \RuntimeException('JWT header missing kid');
            }

            $kid = $header['kid'];

            // Get PEM from cache or fetch from Cognito
            $publicKeyPem = $this->cache->get('cognito_key_' . $kid, function (ItemInterface $item) use ($kid) {
                $item->expiresAfter(3600); // 1 hour cache
                $jwks = json_decode(file_get_contents($this->jwksUrl), true);

                foreach ($jwks['keys'] as $jwk) {
                    if ($jwk['kid'] === $kid) {
                        return $this->jwkToPem($jwk);
                    }
                }

                throw new \RuntimeException('Public key not found for JWT kid: ' . $kid);
            });

            // Lcobucci JWT v4: use same PEM for private/public because we only verify
            $signer = new Sha256();
            $key = InMemory::plainText($publicKeyPem);
            $config = Configuration::forAsymmetricSigner($signer, $key, $key);

            $token = $config->parser()->parse($jwt);

            // Constraints: signature + expiration
            $constraints = [
                new SignedWith($signer, $key),
                new LooseValidAt(SystemClock::fromUTC()), // checks exp and nbf loosely
            ];

            if (!$config->validator()->validate($token, ...$constraints)) {
                throw new \RuntimeException('Invalid token signature or expired');
            }

            // Claims
            $claims = $token->claims();

            // Cognito AccessToken must have "token_use" = "access" and correct audience
            if ($claims->get('token_use') !== 'access') {
                throw new \RuntimeException('Token is not an access token');
            }

            if ($claims->has('aud') && $claims->get('aud') !== $this->expectedAudience) {
                throw new \RuntimeException('Token audience mismatch');
            }

            return [
                'username' => $claims->get('cognito:username'),
                'email' => $claims->get('email'),
                'sub' => $claims->get('sub'),
            ];
        } catch (\Throwable $e) {var_dump($e->getMessage()); die("s");
            $this->logger->warning('JWT validation failed: ' . $e->getMessage());
            throw new AccessDeniedHttpException('Invalid token', $e);
        }
    }

    private function jwkToPem(array $jwk): string
    {
        $modulus = $this->urlsafeB64Decode($jwk['n']);
        $exponent = $this->urlsafeB64Decode($jwk['e']);

        $rsa = PublicKeyLoader::load([
            'n' => new BigInteger($modulus, 256),
            'e' => new BigInteger($exponent, 256),
        ]);

        return $rsa->toString('PKCS8');
    }

    private function urlsafeB64Decode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
