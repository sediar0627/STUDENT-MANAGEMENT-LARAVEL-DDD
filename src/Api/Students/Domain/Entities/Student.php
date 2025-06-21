<?php

namespace Src\Api\Students\Domain\Entities;

use Src\Api\Students\Domain\ValueObjects\StudentEmail;

class Student
{
    public function __construct(
        private int|null $id,
        private StudentEmail $email,
        private string $first_name,
        private string $last_name,
    ) {
    }

    public function id(): int|null
    {
        return $this->id;
    }

    public function email(): StudentEmail
    {
        return $this->email;
    }

    public function firstName(): string
    {
        return $this->first_name;
    }

    public function lastName(): string
    {
        return $this->last_name;
    }

    public function fullName(): string
    {
        return $this->firstName() . ' ' . $this->lastName();
    }
}
