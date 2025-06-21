<?php

namespace Src\Api\Courses\Application;

use Carbon\Carbon;
use Src\Api\Courses\Domain\Entities\Course;
use Src\Api\Courses\Domain\Interfaces\CourseRepositoryInterface;
use Src\Api\Courses\Domain\ValueObjects\CourseDate;

class SaveCourseUseCase
{
	private CourseRepositoryInterface $courseRepository;

	public function __construct(CourseRepositoryInterface $courseRepository)
	{
		$this->courseRepository = $courseRepository;
	}

	public function execute(
		int|null $id,
		string $title,
		string|null $description,
		string|Carbon $startDate,
		string|Carbon $endDate
	): Course
	{
		$course = new Course(
			id: $id,
			title: $title,
			description: $description,
			startDate: new CourseDate($startDate),
			endDate: new CourseDate($endDate)
		);

		return $this->courseRepository->save($course);
	}
}