<?php declare(strict_types=1);

namespace Tests\Editora\Application;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Omatech\Mcore\Editora\Application\ReadInstance\ReadInstanceCommand;
use Omatech\Mcore\Editora\Application\ReadInstance\ReadInstanceCommandHandler;
use Omatech\Mcore\Editora\Domain\Instance\Contracts\InstanceRepositoryInterface;
use Omatech\Mcore\Editora\Domain\Instance\Exceptions\InstanceDoesNotExistsException;
use Omatech\Mcore\Editora\Domain\Instance\Instance;
use PHPUnit\Framework\TestCase;

class ReadInstanceTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /** @test */
    public function readInstanceSuccessfully(): void
    {
        $command = new ReadInstanceCommand('df86408b-b2e6-4922-83e2-b762b000a335');

        $instance = Mockery::mock(Instance::class);
        $instance->shouldReceive('toArray')
            ->andReturn([
                'class' => [],
                'metadata' => [
                    'uuid' => 'df86408b-b2e6-4922-83e2-b762b000a335',
                ],
                'attributes' => [],
                'relations' => [],
            ])
            ->once();

        $repository = Mockery::mock(InstanceRepositoryInterface::class);
        $repository->shouldReceive('find')
            ->with($command->uuid())
            ->andReturn($instance)
            ->once();

        $instance = (new ReadInstanceCommandHandler($repository))->__invoke($command);
        $this->assertSame([
            'class' => [],
            'metadata' => [
                'uuid' => 'df86408b-b2e6-4922-83e2-b762b000a335',
            ],
            'attributes' => [],
            'relations' => [],
        ], $instance->toArray());
    }

    /** @test */
    public function failedToReadInstance(): void
    {
        $this->expectException(InstanceDoesNotExistsException::class);

        $command = new ReadInstanceCommand('df86408b-b2e6-4922-83e2-b762b000a335');

        $repository = Mockery::mock(InstanceRepositoryInterface::class);
        $repository->shouldReceive('find')
            ->with($command->uuid())
            ->andReturn(null)
            ->once();

        (new ReadInstanceCommandHandler($repository))->__invoke($command);
    }
}
