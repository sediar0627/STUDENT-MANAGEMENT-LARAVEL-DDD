<?php

namespace Src\Api\Students\Domain\ValueObjects;

class StudentEmail
{
    public function __construct(private string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}
