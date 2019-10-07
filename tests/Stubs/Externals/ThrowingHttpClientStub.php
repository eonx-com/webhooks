<?php
declare(strict_types=1);

namespace Tests\EoneoPay\Webhooks\Stubs\Externals;

use EoneoPay\Externals\HttpClient\Interfaces\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Throwable;

/**
 * @coversNothing
 */
class ThrowingHttpClientStub extends HttpClientStub
{
    /**
     * @var \Throwable
     */
    private $exception;

    /**
     * Constructor.
     *
     * @param \Throwable $exception
     */
    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Throwable
     */
    public function request(string $method, string $uri, ?array $options = null): ResponseInterface
    {
        parent::request($method, $uri, $options);

        throw $this->exception;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Throwable
     */
    public function sendRequest(RequestInterface $request, ?array $options = null): PsrResponseInterface
    {
        parent::sendRequest($request, $options);

        throw $this->exception;
    }
}
