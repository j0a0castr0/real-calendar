<?php

namespace App\Tests\E2E;

use App\Shared\Validator\InMemory\InMemoryJWTValidator;
use App\Shared\Validator\JWTValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Builder;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class E2ETestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->resetDatabase();
    }

    protected static function getKernelClass(): string
    {
        return \App\Kernel::class;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return static::getContainer()->get('doctrine')->getManager();
    }

    protected function resetDatabase(): void
    {
        $em = $this->getEntityManager();
        $metadata = $em->getMetadataFactory()->getAllMetadata();

        if (!empty($metadata)) {
            $tool = new SchemaTool($em);
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }
    }

    protected function request(
        string $method,
        string $uri,
        array $parameters = [],
        array $files = [],
        array $server = []
    ): void {
        $this->client->request($method, $uri, $parameters, $files, $server);
    }

    protected function jsonRequest(string $method, string $uri, array $data = []): void
    {
        $this->client->jsonRequest($method, $uri, $data);
    }

    protected function jsonRequestWithAuth(
        string $method,
        string $uri,
        array $data = [],
        string $email = 'test@agent.com'
    ): void {
        $token = $this->getTestToken($email);

        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $token);
        $this->client->setServerParameter('HTTP_X_ID_TOKEN', $token);
        $this->client->jsonRequest($method, $uri, $data);
    }

    protected function getResponseCode(): int
    {
        return $this->client->getResponse()->getStatusCode();
    }

    protected function getJsonResponse(): array
    {
        $content = $this->client->getResponse()->getContent();

        return $content ? json_decode($content, true, 512, JSON_THROW_ON_ERROR) : [];
    }

    private function getTestToken(string $email = 'test@agent.com'): string
    {
        $signer = new Sha256();
        $key = InMemory::plainText('this-is-a-very-long-test-secret-key-of-at-least-32-bytes');

        $config = Configuration::forSymmetricSigner($signer, $key);

        $now = new \DateTimeImmutable();

        $token = $config->builder()
            ->issuedBy('test-issuer')       // iss claim
            ->permittedFor('test-audience') // aud claim
            ->issuedAt($now)                // iat claim
            ->expiresAt($now->modify('+1 hour')) // exp claim
            ->withClaim('email', $email)
            ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }
}
