<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Events\Interfaces\Http;

use EoneoPay\Webhooks\Events\Interfaces\EventInterface;

interface XmlEventInterface extends EventInterface
{
    /**
     * Set xml root node.
     *
     * @param null|string $rootNode Xml root node
     *
     * @return \EoneoPay\Webhooks\Events\Interfaces\Http\XmlEventInterface
     */
    public function setRootNode(?string $rootNode = null): XmlEventInterface;
}
