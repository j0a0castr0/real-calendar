<?php

namespace App\Tests\E2E;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class E2ETestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        // Create client (boots kernel automatically)
        $this->client = static::createClient();

        // Reset database schema per test
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

    protected function getResponseCode(): int
    {
        return $this->client->getResponse()->getStatusCode();
    }

    protected function getJsonResponse(): array
    {
        $content = $this->client->getResponse()->getContent();

        return $content ? json_decode($content, true, 512, JSON_THROW_ON_ERROR) : [];
    }
}
