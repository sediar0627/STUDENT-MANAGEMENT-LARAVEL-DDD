<?php

namespace Src\Api\Students\Application;

use Src\Api\Students\Domain\Entities\Student;
use Src\Api\Students\Domain\Interfaces\StudentRepostitoryInterface;
use Src\Api\Students\Domain\ValueObjects\StudentEmail;

class SaveStudentUseCase
{
	public function __construct(
		private StudentRepostitoryInterface $studentRepository
	){
	}

	public function execute(
		int|null $id,
		string $email,
		string $firstName,
		string $lastName
	): Student
	{
		$studentEmail = new StudentEmail($email);

		$student = new Student(
			id: $id,
			email: $studentEmail,
			first_name: $firstName,
			last_name: $lastName
		);

		return $this->studentRepository->save($student);
	}
}