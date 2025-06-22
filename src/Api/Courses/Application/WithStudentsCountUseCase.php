<?php

namespace Src\Api\Courses\Application;

use Src\Api\Courses\Domain\Interfaces\CourseRepositoryInterface;

class WithStudentsCountUseCase
{
	public function __construct(
		private CourseRepositoryInterface $courseRepository,
	) {}

	public function execute(): array
	{
		return $this->courseRepository->allWithStudentsCount();
	}
}