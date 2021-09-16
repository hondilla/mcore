<?php declare(strict_types=1);

namespace Tests\Editora\Application;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Omatech\Mcore\Editora\Application\ExtractInstance\ExtractInstanceCommand;
use Omatech\Mcore\Editora\Application\ExtractInstance\ExtractInstanceCommandHandler;
use Omatech\Mcore\Editora\Domain\Instance\Contracts\ExtractionRepositoryInterface;
use Omatech\Mcore\Editora\Domain\Instance\Extraction\Pagination;
use Omatech\Mcore\Editora\Domain\Instance\Extraction\Results;
use Omatech\Mcore\Editora\Domain\Instance\InstanceBuilder;
use PHPUnit\Framework\TestCase;

class ExtractInstanceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @test */
    public function extractPaginatedInstancesByClassSuccessfully(): void
    {
        $command = new ExtractInstanceCommand('{
            ClassOne(preview: false, language: es, limit: 10, page: 1)
        }');

        $instance = (new InstanceBuilder())
            ->setLanguages(['es', 'en'])
            ->setStructure([
                'attributes' => [
                    'AttributeOne' => [],
                    'AttributeTwo' => [],
                ],
            ])
            ->setClassName('ClassOne')
            ->build();

        $instance->fill([
            'metadata' => [
                'id' => 1,
                'key' => 'instance-key',
                'publication' => [
                    'startPublishingDate' => '1989-03-08 09:00:00',
                ],
            ],
            'attributes' => [
                'attribute-one' => [
                    'values' => [
                        [
                            'language' => 'es',
                            'value' => 'value-one',
                        ],
                    ],
                    'attributes' => [],
                ],
                'attribute-two' => [
                    'values' => [
                        [
                            'language' => 'es',
                            'value' => 'value-two',
                        ],
                    ],
                    'attributes' => [],
                ],
            ],
            'relations' => [],
        ]);

        $repository = Mockery::mock(ExtractionRepositoryInterface::class);

        $repository->shouldReceive('instancesBy')
            ->with([
                'key' => null,
                'class' => 'class-one',
                'preview' => false,
                'language' => 'es',
                'limit' => 10,
                'page' => 1,
            ])
            ->andReturn(
                new Results(
                    [
                        $instance,
                        $instance,
                    ],
                    new Pagination([
                        'page' => 1,
                        'limit' => 10,
                    ], 2)
                )
            )->once();

        $extractions = (new ExtractInstanceCommandHandler($repository))->__invoke($command);
        $this->assertEquals([
            [
                'key' => 'instance-key',
                'attributes' => [
                    [
                        'id' => null,
                        'key' => 'attribute-one',
                        'value' => 'value-one',
                        'attributes' => [],
                    ], [
                        'id' => null,
                        'key' => 'attribute-two',
                        'value' => 'value-two',
                        'attributes' => [],
                    ],
                ],
                'relations' => [],
            ], [
                'key' => 'instance-key',
                'attributes' => [
                    [
                        'id' => null,
                        'key' => 'attribute-one',
                        'value' => 'value-one',
                        'attributes' => [],
                    ], [
                        'id' => null,
                        'key' => 'attribute-two',
                        'value' => 'value-two',
                        'attributes' => [],
                    ],
                ],
                'relations' => [],
            ],
        ], $extractions->toArray());
    }

    /** @test */
    public function extractInstancesByClassSuccessfully(): void
    {
        $command = new ExtractInstanceCommand('{
            ClassOne(preview: false, language: es)
            ClassTwo(preview: false, language: en)
        }');

        $instance = (new InstanceBuilder())
            ->setLanguages(['es', 'en'])
            ->setStructure([
                'attributes' => [
                    'AttributeOne' => [],
                    'AttributeTwo' => [],
                ],
            ])
            ->setClassName('ClassOne')
            ->build();

        $instance->fill([
            'metadata' => [
                'id' => 1,
                'key' => 'instance-key',
                'publication' => [
                    'startPublishingDate' => '1989-03-08 09:00:00',
                ],
            ],
            'attributes' => [
                'attribute-one' => [
                    'values' => [
                        [
                            'language' => 'es',
                            'value' => 'value-one',
                        ],
                    ],
                    'attributes' => [],
                ],
                'attribute-two' => [
                    'values' => [
                        [
                            'language' => 'es',
                            'value' => 'value-two',
                        ],
                    ],
                    'attributes' => [],
                ],
            ],
            'relations' => [],
        ]);

        $instance2 = (new InstanceBuilder())
            ->setLanguages(['es', 'en'])
            ->setStructure([
                'attributes' => [
                    'AttributeThree' => [],
                    'AttributeFour' => [],
                ],
            ])
            ->setClassName('ClassTwo')
            ->build();

        $instance2->fill([
            'metadata' => [
                'id' => 2,
                'key' => 'instance-key',
                'publication' => [
                    'startPublishingDate' => '1989-03-08 09:00:00',
                ],
            ],
            'attributes' => [
                'attribute-three' => [
                    'values' => [
                        [
                            'language' => 'en',
                            'value' => 'value-three',
                        ],
                    ],
                    'attributes' => [],
                ],
                'attribute-four' => [
                    'values' => [
                        [
                            'language' => 'en',
                            'value' => 'value-four',
                        ],
                    ],
                    'attributes' => [],
                ],
            ],
            'relations' => [],
        ]);

        $repository = Mockery::mock(ExtractionRepositoryInterface::class);
        $repository->shouldReceive('instancesBy')
            ->with([
                'key' => null,
                'class' => 'class-one',
                'preview' => false,
                'language' => 'es',
                'limit' => 0,
                'page' => 1,
            ])
            ->andReturn(
                new Results(
                    [
                        $instance,
                        $instance,
                    ],
                    new Pagination([
                        'page' => 1,
                        'limit' => 0,
                    ], 2)
                )
            )->once();
        $repository->shouldReceive('instancesBy')
            ->with([
                'key' => null,
                'class' => 'class-two',
                'preview' => false,
                'language' => 'en',
                'limit' => 0,
                'page' => 1,
            ])
            ->andReturn(
                new Results(
                    [
                        $instance2,
                        $instance2,
                    ],
                    new Pagination([
                        'page' => 1,
                        'limit' => 0,
                    ], 2)
                )
            )->once();

        $extractions = (new ExtractInstanceCommandHandler($repository))->__invoke($command);

        $this->assertEquals([
            [
                [
                    'key' => 'instance-key',
                    'attributes' => [
                        [
                            'id' => null,
                            'key' => 'attribute-one',
                            'value' => 'value-one',
                            'attributes' => [],
                        ], [
                            'id' => null,
                            'key' => 'attribute-two',
                            'value' => 'value-two',
                            'attributes' => [],
                        ],
                    ],
                    'relations' => [],
                ], [
                    'key' => 'instance-key',
                    'attributes' => [
                        [
                            'id' => null,
                            'key' => 'attribute-one',
                            'value' => 'value-one',
                            'attributes' => [],
                        ], [
                            'id' => null,
                            'key' => 'attribute-two',
                            'value' => 'value-two',
                            'attributes' => [],
                        ],
                    ],
                    'relations' => [],
                ],
            ], [
                [
                    'key' => 'instance-key',
                    'attributes' => [
                        [
                            'id' => null,
                            'key' => 'attribute-three',
                            'value' => 'value-three',
                            'attributes' => [],
                        ], [
                            'id' => null,
                            'key' => 'attribute-four',
                            'value' => 'value-four',
                            'attributes' => [],
                        ],
                    ],
                    'relations' => [],
                ], [
                    'key' => 'instance-key',
                    'attributes' => [
                        [
                            'id' => null,
                            'key' => 'attribute-three',
                            'value' => 'value-three',
                            'attributes' => [],
                        ], [
                            'id' => null,
                            'key' => 'attribute-four',
                            'value' => 'value-four',
                            'attributes' => [],
                        ],
                    ],
                    'relations' => [],
                ],
            ],
        ], $extractions->toArray());
    }

    /** @test */
    public function extractMultiInstancesSuccessfully(): void
    {
        $query = '{
            class(key: InstanceKey, language: es)
            class(key: InstanceKey, preview: true, language: en, limit: 4, page: 2)
        }';
        $command = new ExtractInstanceCommand($query);

        $instance = (new InstanceBuilder())
            ->setLanguages(['es', 'en'])
            ->setStructure([
                'attributes' => [
                    'AttributeOne' => [],
                ],
            ])
            ->setClassName('ClassOne')
            ->build();

        $instance->fill([
            'metadata' => [
                'id' => 1,
                'key' => 'instance-key',
                'publication' => [
                    'startPublishingDate' => '1989-03-08 09:00:00',
                ],
            ],
            'attributes' => [
                'attribute-one' => [
                    'values' => [
                        [
                            'language' => 'es',
                            'value' => 'value-one',
                        ],
                    ],
                    'attributes' => [],
                ],
            ],
            'relations' => [],
        ]);

        $instance2 = (new InstanceBuilder())
            ->setLanguages(['es', 'en'])
            ->setStructure([
                'attributes' => [
                    'AttributeTwo' => [],
                ],
            ])
            ->setClassName('ClassOne')
            ->build();

        $instance2->fill([
            'metadata' => [
                'id' => 2,
                'key' => 'instance-key',
                'publication' => [
                    'startPublishingDate' => '1989-03-08 09:00:00',
                ],
            ],
            'attributes' => [
                'attribute-two' => [
                    'values' => [
                        [
                            'language' => 'en',
                            'value' => 'value-two',
                        ],
                    ],
                    'attributes' => [],
                ],
            ],
            'relations' => [],
        ]);

        $repository = Mockery::mock(ExtractionRepositoryInterface::class);
        $repository->shouldReceive('instancesBy')
            ->with([
                'class' => null,
                'key' => 'instance-key',
                'preview' => false,
                'language' => 'es',
                'limit' => '0',
                'page' => '1',
            ])
            ->andReturn(
                new Results(
                    [
                        $instance,
                    ],
                    new Pagination([
                        'page' => '1',
                        'limit' => '0',
                    ], 1)
                )
            )->once();
        $repository->shouldReceive('instancesBy')
            ->with([
                'class' => null,
                'key' => 'instance-key',
                'preview' => true,
                'language' => 'en',
                'limit' => 4,
                'page' => 2,
            ])
            ->andReturn(
                new Results(
                    [
                        $instance2,
                        $instance2,
                        $instance2,
                        $instance2,
                        $instance2,
                    ],
                    new Pagination([
                        'page' => 2,
                        'limit' => 4,
                    ], 5)
                )
            )->once();

        $extractions = (new ExtractInstanceCommandHandler($repository))->__invoke($command);

        $this->assertEquals([
            [
                'key' => 'instance-key',
                'attributes' => [
                    [
                        'id' => null,
                        'key' => 'attribute-one',
                        'value' => 'value-one',
                        'attributes' => [],
                    ],
                ],
                'relations' => [],
            ], [
                [
                    'key' => 'instance-key',
                    'attributes' => [
                        [
                            'id' => null,
                            'key' => 'attribute-two',
                            'value' => 'value-two',
                            'attributes' => [],
                        ],
                    ],
                    'relations' => [],
                ], [
                    'key' => 'instance-key',
                    'attributes' => [
                        [
                            'id' => null,
                            'key' => 'attribute-two',
                            'value' => 'value-two',
                            'attributes' => [],
                        ],
                    ],
                    'relations' => [],
                ], [
                    'key' => 'instance-key',
                    'attributes' => [
                        [
                            'id' => null,
                            'key' => 'attribute-two',
                            'value' => 'value-two',
                            'attributes' => [],
                        ],
                    ],
                    'relations' => [],
                ], [
                    'key' => 'instance-key',
                    'attributes' => [
                        [
                            'id' => null,
                            'key' => 'attribute-two',
                            'value' => 'value-two',
                            'attributes' => [],
                        ],
                    ],
                    'relations' => [],
                ], [
                    'key' => 'instance-key',
                    'attributes' => [
                        [
                            'id' => null,
                            'key' => 'attribute-two',
                            'value' => 'value-two',
                            'attributes' => [],
                        ],
                    ],
                    'relations' => [],
                ],
            ],
        ], $extractions->toArray());

        $this->assertEquals($query, $extractions->query());
        $this->assertEquals(md5($query), $extractions->hash());
        $this->assertInstanceOf('dateTime', $extractions->date());
        $this->assertIsArray($extractions->queries());
        $this->assertEquals(1, $extractions->queries()[0]->pagination()->realLimit());
        $this->assertEquals(0, $extractions->queries()[0]->pagination()->offset());
        $this->assertSame([
            'total' => 1,
            'limit' => 0,
            'current' => 1,
            'pages' => 1,
        ], $extractions->queries()[0]->pagination()->toArray());
        $this->assertFalse($extractions->queries()[0]->param('preview'));
        $this->assertEquals(4, $extractions->queries()[1]->pagination()->realLimit());
        $this->assertEquals(4, $extractions->queries()[1]->pagination()->offset());
        $this->assertSame([
            'total' => 5,
            'limit' => 4,
            'current' => 2,
            'pages' => 2,
        ], $extractions->queries()[1]->pagination()->toArray());
        $this->assertTrue($extractions->queries()[1]->param('preview'));
    }

    /** @test */
    public function extractInstanceSuccessfully(): void
    {
        $command = new ExtractInstanceCommand('{
            class(key: InstanceKey, preview: false, language: es) {
                DefaultAttribute
                RelationKey1(limit:1) {
                    RelationKey2(limit:1)
                }
            }
        }');

        $instance = (new InstanceBuilder())
            ->setLanguages(['es', 'en'])
            ->setStructure([
                'relations' => [
                    'RelationKey1' => [
                        'ClassTwo',
                    ],
                ],
                'attributes' => [
                    'DefaultAttribute' => [],
                ],
            ])
            ->setClassName('ClassOne')
            ->build();

        $instance->fill([
            'metadata' => [
                'id' => 1,
                'key' => 'instance-key',
                'publication' => [
                    'startPublishingDate' => '1989-03-08 09:00:00',
                ],
            ],
            'attributes' => [
                'default-attribute' => [
                    'values' => [
                        [
                            'language' => 'es',
                            'value' => 'hola',
                        ],
                    ],
                    'attributes' => [],
                ],
            ],
            'relations' => [
                'relation-key1' => [
                    2 => 'class-two',
                ],
            ],
        ]);

        $instance2 = (new InstanceBuilder())
            ->setLanguages(['es', 'en'])
            ->setStructure([
                'relations' => [
                    'RelationKey2' => [
                        'ClassThree',
                    ],
                ],
                'attributes' => [
                    'DefaultAttribute' => [],
                ],
            ])
            ->setClassName('ClassTwo')
            ->build();

        $instance2->fill([
            'metadata' => [
                'id' => 2,
                'key' => 'instance-key2',
                'publication' => [
                    'startPublishingDate' => '1989-03-08 09:00:00',
                ],
            ],
            'attributes' => [
                'default-attribute' => [
                    'values' => [
                        [
                            'language' => 'es',
                            'value' => 'hola',
                        ],
                    ],
                    'attributes' => [],
                ],
            ],
            'relations' => [
                'relation-key2' => [
                    3 => 'class-three',
                ],
            ],
        ]);

        $instance3 = (new InstanceBuilder())
            ->setLanguages(['es', 'en'])
            ->setStructure([
                'attributes' => [
                    'DefaultAttribute' => [],
                ],
            ])
            ->setClassName('ClassThree')
            ->build();

        $instance3->fill([
            'metadata' => [
                'id' => 3,
                'key' => 'instance-key3',
                'publication' => [
                    'startPublishingDate' => '1989-03-08 09:00:00',
                ],
            ],
            'attributes' => [
                'default-attribute' => [
                    'values' => [
                        [
                            'language' => 'es',
                            'value' => 'hola',
                        ],
                    ],
                    'attributes' => [],
                ],
            ],
            'relations' => [],
        ]);

        $repository = Mockery::mock(ExtractionRepositoryInterface::class);
        $repository->shouldReceive('instancesBy')
            ->with([
                'class' => null,
                'preview' => false,
                'key' => 'instance-key',
                'language' => 'es',
                'limit' => 0,
                'page' => 1,
            ])
            ->andReturn(
                new Results(
                    [
                        $instance,
                    ],
                    new Pagination([
                        'page' => 1,
                        'limit' => 0,
                    ], 1)
                )
            )->once();
        $repository->shouldReceive('findChildrenInstances')
            ->with(1, [
                'class' => 'relation-key1',
                'key' => null,
                'limit' => 1,
                'language' => 'es',
                'preview' => false,
                'page' => 1,
            ])
            ->andReturn(
                new Results(
                    [
                        $instance2,
                    ],
                    new Pagination([
                        'page' => 1,
                        'limit' => 10,
                    ], 1)
                )
            )->once();

        $repository->shouldReceive('findChildrenInstances')
            ->with(2, [
                'class' => 'relation-key2',
                'key' => null,
                'limit' => 1,
                'language' => 'es',
                'preview' => false,
                'page' => 1,
            ])
            ->andReturn(
                new Results(
                    [
                        $instance3,
                    ],
                    new Pagination([
                        'page' => 1,
                        'limit' => 20,
                    ], 1)
                )
            )->once();

        $extraction = (new ExtractInstanceCommandHandler($repository))->__invoke($command);

        $this->assertEquals([
            'key' => 'instance-key',
            'attributes' => [
                [
                    'id' => null,
                    'key' => 'default-attribute',
                    'value' => 'hola',
                    'attributes' => [],
                ],
            ],
            'relations' => [
                'relation-key1' => [
                    [
                        'key' => 'instance-key2',
                        'attributes' => [
                            [
                                'id' => null,
                                'key' => 'default-attribute',
                                'value' => 'hola',
                                'attributes' => [],
                            ],
                        ],
                        'relations' => [
                            'relation-key2' => [
                                [
                                    'key' => 'instance-key3',
                                    'attributes' => [
                                        [
                                            'id' => null,
                                            'key' => 'default-attribute',
                                            'value' => 'hola',
                                            'attributes' => [],
                                        ],
                                    ],
                                    'relations' => [],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $extraction->toArray());

        $pagination = $extraction->queries()[0]->relations()[0]->pagination();
        $this->assertEquals(10, $pagination->realLimit());
        $this->assertEquals(0, $pagination->offset());
        $this->assertSame([
            'total' => 1,
            'limit' => 10,
            'current' => 1,
            'pages' => 1,
        ], $pagination->toArray());
    }
}
