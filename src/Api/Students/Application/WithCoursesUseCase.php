<?php

namespace Src\Api\Students\Application;

use Src\Api\Students\Domain\Interfaces\StudentRepostitoryInterface;

class WithCoursesUseCase
{
	public function __construct(
		private StudentRepostitoryInterface $studentRepository,
	) {}

	public function execute(): array
	{
		return $this->studentRepository->allWithCourses();
	}
}