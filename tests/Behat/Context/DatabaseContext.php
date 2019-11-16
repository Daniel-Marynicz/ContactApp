<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use RuntimeException;

class DatabaseContext implements Context
{
    /** @var SchemaTool */
    private $schemaTool;

    /** @var ClassMetadata[] */
    private $classMetadatas;

    public function __construct(
        ManagerRegistry $managerRegistry
    ) {
        $entityManager = $managerRegistry->getManager();
        if (! $entityManager instanceof EntityManagerInterface) {
            throw new RuntimeException(
                'Object manager is not instance of class EntityManager. Please check your configuration.'
            );
        }
        $this->schemaTool     = new SchemaTool($entityManager);
        $this->classMetadatas = $entityManager->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @throws ToolsException
     *
     * @BeforeScenario
     */
    public function createDatabase() : void
    {
        $this->schemaTool->dropDatabase();
        $this->schemaTool->createSchema($this->classMetadatas);
    }

    /**
     * @AfterScenario
     */
    public function dropDatabase() : void
    {
        $this->schemaTool->dropDatabase();
    }
}
