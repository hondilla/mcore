<?php declare(strict_types=1);

namespace Omatech\Mcore\Editora\Domain\Instance\Extraction;

use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\reduce;

final class Query
{
    private string $key;
    private array $attributes;
    private array $params;
    private array $relations;

    public function __construct(array $query)
    {
        $this->key = $query['key'];
        $this->attributes = $query['attributes'];
        $this->params = $query['params'];
        $this->relations = $query['relations'];
    }

    public function key(): string
    {
        return $this->key;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function relations(): array
    {
        return $this->relations;
    }

    public function param(?string $key): mixed
    {
        return $this->params[$key] ?? null;
    }

    public function params(): array
    {
        return $this->params;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'language' => $this->param('language'),
            'attributes' => map(
                static fn (Attribute $attribute) => $attribute->toQuery(),
                $this->attributes
            ),
            'params' => $this->params,
            'relations' => reduce(static function (
                array $acc,
                Query $query
            ): array {
                $acc[] = $query->toArray();
                return $acc;
            }, $this->relations, []),
        ];
    }
}
