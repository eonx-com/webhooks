<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Externals;

use EoneoPay\Externals\HttpClient\Interfaces\ClientInterface;
use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use EoneoPay\Externals\HttpClient\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * @coversNothing
 */
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

        return new Response(new EmptyResponse());
    }

    /**
     * {@inheritdoc}
     */
    public function sendRequest(RequestInterface $request, ?array $options = null): PsrResponseInterface
    {
        $this->requests[] = [
            'method' => $request->getMethod(),
            'uri' => $request->getUri()->__toString(),
            'options' => $options
        ];

        return new EmptyResponse();
    }
}
