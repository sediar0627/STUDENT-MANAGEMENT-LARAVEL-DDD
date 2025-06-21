<?php

namespace Src\Api\Students\Domain\Interfaces;

use Src\Api\Students\Domain\Entities\Student;

interface StudentRepostitoryInterface
{
    public function all(): array;

    public function findById(int $id): ?Student;

    public function save(Student $student): Student;

    public function delete(int $id): void;
}
