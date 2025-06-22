<?php

namespace Src\Api\Students\Application;

use Src\Api\Students\Domain\Interfaces\StudentRepostitoryInterface;

class DeleteStudentByIdUseCase
{
	public function __construct(
		private StudentRepostitoryInterface $studentRepository
	){}

	public function execute(string $id): void
	{
		$this->studentRepository->delete($id);
	}
}