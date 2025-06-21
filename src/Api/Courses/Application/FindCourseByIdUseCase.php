<?php

namespace Src\Api\Courses\Application;

use Src\Api\Courses\Domain\Entities\Course;
use Src\Api\Courses\Domain\Interfaces\CourseRepositoryInterface;

class FindCourseByIdUseCase
{
	private CourseRepositoryInterface $courseRepository;

	public function __construct(CourseRepositoryInterface $courseRepository)
	{
		$this->courseRepository = $courseRepository;
	}

	public function execute(int $id): ?Course
	{
		return $this->courseRepository->findById($id);
	}
}