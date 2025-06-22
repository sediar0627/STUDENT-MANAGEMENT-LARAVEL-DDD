<?php

namespace Src\Api\Courses\Domain\Interfaces;

use Src\Api\Courses\Domain\Entities\Course;

interface CourseRepositoryInterface
{
	public function all(): array;

	public function findById(int $id): ?Course;

	public function save(Course $course): Course;

	public function delete(int $id): void;

	public function allWithStudents(): array;
}