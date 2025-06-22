<?php

namespace Src\Api\Courses\Infrastructure\Resource;

use Src\Api\Courses\Domain\Entities\Course;
use Src\Api\Students\Domain\Entities\Student;
use Src\Api\Students\Infrastructure\Resources\StudentResource;

class CourseResource
{
	public function toArrayByCourse(Course $course): array
	{
		$data = [
			'id' => $course->id(),
			'title' => $course->title(),
			'description' => $course->description(),
			'start_date' => $course->startDate()->toFormat(),
			'end_date' => $course->endDate()->toFormat(),
		];

		if(count($course->students()) > 0){
			$data['students'] = array_map(function (Student $student) {
				
				return (new StudentResource())->toArrayByStudent($student);

			}, $course->students());
		}

		return $data;
	}

	public function toArrayByCourses(array $courses): array
	{
		return array_map(function (Course $course) {
			return $this->toArrayByCourse($course);
		}, $courses);
	}
}