<?php

namespace Src\Api\Courses\Application;

use Src\Api\Courses\Domain\Interfaces\CourseRepositoryInterface;

class WithStudentsUseCase
{
	public function __construct(
		private CourseRepositoryInterface $courseRepository,
	) {}

	public function execute(): array
	{
		return $this->courseRepository->allWithStudents();
	}
}