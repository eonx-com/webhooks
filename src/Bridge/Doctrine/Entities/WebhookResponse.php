<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Entities;

use DateTime as BaseDateTime;
use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Webhooks\Bridge\Doctrine\Entities\Schemas\WebhookResponseSchema;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\UnexpectedObjectException;
use EoneoPay\Webhooks\Model\WebhookRequestInterface;
use EoneoPay\Webhooks\Model\WebhookResponseInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="event_activity_responses",
 *     indexes={
 *         @ORM\Index(name="idx_created_at_webhook_response", columns={"created_at"}),
 *         @ORM\Index(name="idx_status_code_webhook_response", columns={"status_code"})
 *     }
 * )
 */
class WebhookResponse extends Entity implements WebhookResponseInterface
{
    use WebhookResponseSchema {
        // Import the public method populate as a private method traitPopulate
        // so we can redefine, but still call the trait method.
        populate as private traitPopulate;
    }

    /**
     * @ORM\ManyToOne(targetEntity="EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest")
     *
     * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest
     */
    protected $request;

    /**
     * The WebhookResponse entity in this package is not intended to be created
     * manually. Use the WebhookPersister to create new WebhookResponse objects.
     *
     * @noinspection MagicMethodsValidityInspection PhpMissingParentConstructorInspection
     */
    private function __construct()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): ?BaseDateTime
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorReason(): ?string
    {
        return $this->errorReason;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): WebhookRequestInterface
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseId(): string
    {
        return $this->responseId;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful(): bool
    {
        return $this->successful;
    }

    /**
     * {@inheritdoc}
     */
    public function populate(
        WebhookRequestInterface $request,
        ResponseInterface $response,
        string $truncatedResponse
    ): void {
        $this->setRequest($request);

        $this->traitPopulate($request, $response, $truncatedResponse);
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(BaseDateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorReason(string $message): void
    {
        $this->errorReason = $message;
    }

    /**
     * Sets the request association.
     *
     * @param \EoneoPay\Webhooks\Model\WebhookRequestInterface $request
     *
     * @return void
     */
    public function setRequest(WebhookRequestInterface $request): void
    {
        if ($request instanceof WebhookRequest === false) {
            throw new UnexpectedObjectException(\sprintf(
                'The %s class expects a %s for the %s property, got %s',
                __CLASS__,
                WebhookRequest::class,
                'request',
                \get_class($request)
            ));
        }

        /**
         * @var \EoneoPay\Webhooks\Bridge\Doctrine\Entities\WebhookRequest $request
         *
         * @see https://youtrack.jetbrains.com/issue/WI-37859 - typehint required until PhpStorm recognises === check
         */

        // We're not able to call associate because it relies on being able
        // to call getActivity, which wont work because we have a null state.
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function setSuccessful(bool $successful): void
    {
        $this->successful = $successful;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            'created_at' => $this->formatDate($this->createdAt),
            'error_reason' => $this->getErrorReason(),
            'id' => $this->getResponseId(),
            'request' => $this->request->toArray(),
            'status_code' => $this->getStatusCode(),
            'successful' => $this->isSuccessful()
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getIdProperty(): string
    {
        return 'responseId';
    }
}
