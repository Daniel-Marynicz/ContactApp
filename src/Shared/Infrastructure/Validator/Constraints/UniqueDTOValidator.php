<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Validator\Constraints;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use ReflectionException;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueDTOValidator extends ConstraintValidator
{
    /** @var RequestStack */
    private $requestStack;

    /** @var ManagerRegistry */
    private $registry;

    public function __construct(
        RequestStack $requestStack,
        ManagerRegistry $registry
    ) {
        $this->requestStack = $requestStack;
        $this->registry     = $registry;
    }

    /**
     * @param string|mixed $value
     * @param Constraint   &UniqueDTO $constraint
     */
    public function validate($value, Constraint $constraint) : void
    {
        $constraint->entityClass;
        $manager = $this->registry->getManagerForClass($constraint->entityClass);
        if (! $manager instanceof ObjectManager) {
            throw new RuntimeException(
                'No Object Manager found for ' . $constraint->entityClass . ' Please check your configuration.'
            );
        }
        $repository = $manager->getRepository($constraint->entityClass);

        $request = $this->requestStack->getCurrentRequest();
        if ($request instanceof Request && $request->isMethod('PUT')) {
            $uuid   = $request->attributes->get($constraint->requestKey);
            $entity = $repository->findOneBy([$constraint->uuidFieldName => $uuid]);

            $entityPropertyVal = $this->getEntityPropertyValue($constraint, $entity);
            if ($entityPropertyVal && $entityPropertyVal === $value) {
                return;
            }
        }
        $entity = $repository->findOneBy([$constraint->entityPropertyName => $value]);
        if (! $entity) {
            return;
        }

        $this
            ->context
            ->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }

    /**
     * @return mixed|null
     *
     * @throws ReflectionException
     */
    private function getEntityPropertyValue(UniqueDTO $constraint, ?object $entity)
    {
        if ($entity === null) {
            return null;
        }
        $entityProperty = new ReflectionProperty($entity, $constraint->entityPropertyName);
        $entityProperty->setAccessible(true);

        return $entityProperty->getValue($entity);
    }
}
