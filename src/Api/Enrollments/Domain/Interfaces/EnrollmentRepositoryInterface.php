<?php

namespace Src\Api\Enrollments\Domain\Interfaces;

use Src\Api\Enrollments\Domain\Entities\Enrollment;

interface EnrollmentRepositoryInterface
{
	public function create(Enrollment $enrollment): Enrollment;

	public function findByStudentIdAndCourseId(int $studentId, int $courseId): ?Enrollment;
}