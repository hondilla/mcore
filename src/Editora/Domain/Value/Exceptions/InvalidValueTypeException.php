<?php declare(strict_types=1);

namespace Omatech\Mcore\Editora\Domain\Value\Exceptions;

use Exception;

final class InvalidValueTypeException extends Exception
{
    public static function withType(string $type): self
    {
        throw new self("${type} do not exists.");
    }
}
