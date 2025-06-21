<?php

namespace Src\Api\Students\Infrastructure\Resources;

use Src\Api\Students\Domain\Entities\Student;

class StudentResource
{
	public function toArrayByStudent(Student $student): array
	{
		return [
			'id' => $student->id(),
			'email' => $student->email()->value(),
			'first_name' => $student->firstName(),
			'last_name' => $student->lastName(),
		];
	}

	public function toArrayByStudents(array $students): array
	{
		return array_map(function (Student $student) {
			return $this->toArrayByStudent($student);
		}, $students);
	}
}