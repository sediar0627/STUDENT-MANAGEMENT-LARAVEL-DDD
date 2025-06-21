<?php

namespace Src\Api\Courses\Application;

use Carbon\Carbon;
use Src\Api\Courses\Domain\Entities\Course;
use Src\Api\Courses\Domain\Interfaces\CourseRepositoryInterface;
class GetAllCoursesUseCase
{
	private CourseRepositoryInterface $courseRepository;

	public function __construct(CourseRepositoryInterface $courseRepository)
	{
		$this->courseRepository = $courseRepository;
	}

	public function execute(): array
	{
		return $this->courseRepository->all();
	}
}