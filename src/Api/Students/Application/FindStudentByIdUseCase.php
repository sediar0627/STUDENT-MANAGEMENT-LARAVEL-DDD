<?php

namespace Src\Api\Students\Application;

use Src\Api\Students\Domain\Entities\Student;
use Src\Api\Students\Domain\Interfaces\StudentRepostitoryInterface;

class FindStudentByIdUseCase
{
	public function __construct(
		private StudentRepostitoryInterface $studentRepository
	){}

	public function execute(string $id): ?Student
	{
		return $this->studentRepository->findById($id);
	}
}