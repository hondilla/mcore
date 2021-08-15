<?php declare(strict_types=1);

namespace Omatech\Mcore\Editora\Domain\Attribute;

use Omatech\Mcore\Editora\Domain\Value\ValueCollection;

final class Attribute
{
    private string $key;
    private string $type;
    private ValueCollection $valueCollection;
    private AttributeCollection $attributeCollection;

    public function __construct(array $properties)
    {
        $this->key = $properties['key'];
        $this->type = $properties['type'];
        $this->valueCollection = new ValueCollection($properties['values']);
        $this->attributeCollection = new AttributeCollection($properties['attributes']);
    }

    public function fill(array $values): void
    {
        $this->valueCollection->fill($values['values']);
        $this->attributeCollection->fill($values['attributes'] ?? []);
    }

    public function validate(): void
    {
        $this->valueCollection->validate();
        $this->attributeCollection->validate();
    }

    public function key(): string
    {
        return $this->key;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'type' => $this->type,
            'values' => $this->valueCollection->get(),
            'attributes' => $this->attributeCollection->get(),
        ];
    }
}
