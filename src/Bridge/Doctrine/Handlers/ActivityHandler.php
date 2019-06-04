<?php
declare(strict_types=1);

namespace EoneoPay\Webhooks\Bridge\Doctrine\Handlers;

use Doctrine\Instantiator\Exception\ExceptionInterface;
use Doctrine\ORM\EntityManagerInterface;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\DoctrineMisconfiguredException;
use EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\EntityNotCreatedException;
use EoneoPay\Webhooks\Bridge\Doctrine\Handlers\Interfaces\ActivityHandlerInterface;
use EoneoPay\Webhooks\Model\ActivityInterface;

final class ActivityHandler implements ActivityHandlerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $entityManager;

    /**
     * Constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\EntityNotCreatedException
     */
    public function create(): ActivityInterface
    {
        try {
            /**
             * @var \EoneoPay\Webhooks\Model\ActivityInterface $instance
             */
            $instance = $this->entityManager->getClassMetadata(ActivityInterface::class)
                ->newInstance();
        } catch (ExceptionInterface $exception) {
            throw new EntityNotCreatedException(
                \sprintf(
                    'An error occurred creating an %s instance.',
                    ActivityInterface::class
                ),
                0,
                $exception
            );
        }

        return $instance;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \EoneoPay\Webhooks\Bridge\Doctrine\Exceptions\DoctrineMisconfiguredException
     */
    public function get(int $activityId): ?ActivityInterface
    {
        $activity = $this->entityManager->find(ActivityInterface::class, $activityId);

        if ($activity !== null &&
            ($activity instanceof ActivityInterface) === false
        ) {
            throw new DoctrineMisconfiguredException(\sprintf(
                'When querying for a "%s" object, Doctrine returned "%s"',
                ActivityInterface::class,
                \get_class($activity)
            ));
        }

        return $activity;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ActivityInterface $activity): void
    {
        $this->entityManager->persist($activity);
        $this->entityManager->flush();
    }
}
