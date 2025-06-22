<?php

namespace Src\Api\Students\Infrastructure\Resources;

use Src\Api\Courses\Domain\Entities\Course;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;
use Src\Api\Students\Domain\Entities\Student;

class StudentResource
{
	public function toArrayByStudent(Student $student): array
	{
		$data = [
			'id' => $student->id(),
			'email' => $student->email()->value(),
			'first_name' => $student->firstName(),
			'last_name' => $student->lastName(),
		];

		if(count($student->courses()) > 0){
			$data['courses'] = array_map(function (Course $course) {
				return (new CourseResource())->toArrayByCourse($course);
			}, $student->courses());
		}

		return $data;
	}

	public function toArrayByStudents(array $students): array
	{
		return array_map(function (Student $student) {
			return $this->toArrayByStudent($student);
		}, $students);
	}
}