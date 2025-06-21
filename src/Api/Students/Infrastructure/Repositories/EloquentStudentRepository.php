<?php

namespace Src\Api\Students\Infrastructure\Repositories;

use App\Models\Students\Student as EloquentStudent;
use Src\Api\Students\Domain\Entities\Student;
use Src\Api\Students\Domain\Interfaces\StudentRepostitoryInterface;
use Src\Api\Students\Domain\ValueObjects\StudentEmail;

class EloquentStudentRepository implements StudentRepostitoryInterface
{
	public function all(): array
	{
		return EloquentStudent::all()
			->map(function (EloquentStudent $student) {
				return new Student(
					$student->id,
					new StudentEmail($student->email),
					$student->first_name,
					$student->last_name
				);
			})
			->toArray();
	}

    public function findById(int $id): ?Student 
	{
		$dbStudent = EloquentStudent::find($id);

		if (!$dbStudent) {
			return null;
		}

		return new Student(
			id: $dbStudent->id,
			email: new StudentEmail($dbStudent->email),
			first_name: $dbStudent->first_name,
			last_name: $dbStudent->last_name
		);
	}

    public function save(Student $student): Student 
	{
		$dbStudent = EloquentStudent::updateOrCreate(
			['id' => $student->id()],
			[
				'email' => $student->email()->value(),
				'first_name' => $student->firstName(),
				'last_name' => $student->lastName()
			]
		);

		return new Student(
			id: $dbStudent->id,
			email: new StudentEmail($dbStudent->email),
			first_name: $dbStudent->first_name,
			last_name: $dbStudent->last_name
		);
	}

    public function delete(int $id): void 
	{
		EloquentStudent::destroy($id);
	}
}