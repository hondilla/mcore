<?php declare(strict_types=1);

namespace Tests\Editora\Domain\Value;

use Omatech\Mcore\Editora\Domain\Value\Types\Value;
use PHPUnit\Framework\TestCase;

final class ValueTest extends TestCase
{
    /** @test */
    public function createAndFillValue(): void
    {
        $value = new Value('test', 'es', [
            'rules' => [],
            'configuration' => [],
        ]);
        $value->fill([
            'value' => 'hola',
            'extraData' => [
                'ext' => 'jpeg',
            ],
            'uuid' => '1',
        ]);
        $this->assertEquals('1', $value->uuid());
        $this->assertEquals([
            'ext' => 'jpeg',
        ], $value->extraData());
        $this->assertEquals('hola', $value->value());
    }
}
