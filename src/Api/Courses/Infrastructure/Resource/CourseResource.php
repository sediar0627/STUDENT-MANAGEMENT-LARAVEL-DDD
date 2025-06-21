<?php

namespace Src\Api\Courses\Infrastructure\Resource;

use Src\Api\Courses\Domain\Entities\Course;

class CourseResource
{
	public function toArray(Course $course): array
	{
		return [
			'id' => $course->id(),
			'title' => $course->title(),
			'description' => $course->description(),
			'start_date' => $course->startDate()->toFormat(),
			'end_date' => $course->endDate()->toFormat(),
		];
	}
}