<?php

namespace Src\Api\Enrollments\Application;

use Src\Api\Enrollments\Domain\Entities\Enrollment;
use Src\Api\Enrollments\Domain\Interfaces\EnrollmentRepositoryInterface;

class CreateEnrollmentUseCase
{
	public function __construct(
		private EnrollmentRepositoryInterface $enrollmentRepository
	){}

	public function execute(Enrollment $enrollment): Enrollment
	{
		return $this->enrollmentRepository->create($enrollment);
	}
}