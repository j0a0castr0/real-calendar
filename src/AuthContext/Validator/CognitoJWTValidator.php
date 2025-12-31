<?php

namespace App\AuthContext\Validator;

use App\Shared\Validator\JWTValidatorInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Math\BigInteger;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Contracts\Cache\ItemInterface;

class CognitoJWTValidator implements JWTValidatorInterface
{
    private string $jwksUrl;
    private CacheItemPoolInterface $cache;

    public function __construct(CacheItemPoolInterface $cache, string $region, string $userPoolId)
    {
        $this->cache = $cache;
        $this->jwksUrl = sprintf(
            'https://cognito-idp.%s.amazonaws.com/%s/.well-known/jwks.json',
            $region,
            $userPoolId
        );
    }

    public function validate(string $jwt): array
    {
        try {
            $header = json_decode(base64_decode(explode('.', $jwt)[0]), true);
            if (!isset($header['kid'])) {
                throw new \RuntimeException('JWT does not contain kid');
            }

            $kid = $header['kid'];
            $publicKeyPem = $this->cache->get('cognito_key_' . $kid, function (ItemInterface $item) use ($kid) {
                $item->expiresAfter(3600); // cache for 1 hour
                $jwks = json_decode(file_get_contents($this->jwksUrl), true);

                foreach ($jwks['keys'] as $jwk) {
                    if ($jwk['kid'] === $kid) {
                        return $this->jwkToPem($jwk);
                    }
                }

                throw new \RuntimeException('Public key not found for JWT kid');
            });

            $config = Configuration::forAsymmetricSigner(
                new Sha256(),
                InMemory::empty(),          // no private key needed
                InMemory::plainText($publicKeyPem)
            );

            $token = $config->parser()->parse($jwt);

            if (!$config->validator()->validate($token, ...[])) {
                throw new \RuntimeException('Invalid token');
            }

            $claims = $token->claims();

            return [
                'username' => $claims->get('cognito:username'),
                'email' => $claims->get('email'),
            ];
        } catch (\Throwable $e) {
            throw new AccessDeniedHttpException('Invalid token', $e);
        }
    }

    private function jwkToPem(array $jwk): string
    {
        $modulus = base64_decode(strtr($jwk['n'], '-_', '+/'));
        $exponent = base64_decode(strtr($jwk['e'], '-_', '+/'));

        // Create key
        $rsa = PublicKeyLoader::load([
            'n' => new BigInteger($modulus, 256),
            'e' => new BigInteger($exponent, 256),
        ]);

        return $rsa->getPublicKey()->toString('PKCS8');
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
