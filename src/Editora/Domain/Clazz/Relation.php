<?php declare(strict_types=1);

namespace Omatech\Mcore\Editora\Domain\Clazz;

use Omatech\Mcore\Editora\Domain\Clazz\Exceptions\InvalidRelationClassException;

final class Relation
{
    private string $key;
    private array $classes;

    public function __construct(string $key, array $classes)
    {
        $this->key = $key;
        $this->classes = $classes;
    }

    public function validate(array $classes): void
    {
        $diff = array_diff($classes, $this->classes);
        if (count($diff)) {
            InvalidRelationClassException::withRelationClasses($this->key, $diff);
        }
    }

    public function key(): string
    {
        return $this->key;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'classes' => $this->classes,
        ];
    }
}
