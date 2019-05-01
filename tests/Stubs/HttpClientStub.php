<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Externals\HttpClient\Response;

class HttpClientStub implements ClientInterface
{
    /**
     * @var mixed[]
     */
    private $requests = [];

    /**
     * Return requests
     *
     * @return mixed[]
     */
    public function getRequests(): array
    {
        return $this->requests;
    }

    /**
     * {@inheritdoc}
     */
    public function request(string $method, string $uri, ?array $options = null): ResponseInterface
    {
        $this->requests[] = \compact('method', 'uri', 'options');

        return new Response(null, 204);
    }
}
