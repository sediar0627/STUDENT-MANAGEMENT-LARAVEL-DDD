<?php

namespace Src\Api\Courses\Application;

use Src\Api\Courses\Domain\Interfaces\CourseRepositoryInterface;

class DeleteCourseByIdUseCase
{
	private CourseRepositoryInterface $courseRepository;

	public function __construct(CourseRepositoryInterface $courseRepository)
	{
		$this->courseRepository = $courseRepository;
	}

	public function execute(int $id): void
	{
		$this->courseRepository->delete($id);
	}
}