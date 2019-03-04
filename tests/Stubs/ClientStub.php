<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Externals\HttpClient\Response;

class ClientStub implements ClientInterface
{
    /**
     * @var mixed[]
     */
    private $requests = [];

    /**
     * @inheritdoc
     */
    public function request(string $method, string $uri, ?array $options = null): ResponseInterface
    {
        $this->requests[] = \compact('method', 'uri', 'options');

        return new Response();
    }

    /**
     * Return requests
     *
     * @return mixed[]
     */
    public function getRequests(): array
    {
        return $this->requests;
    }
}
