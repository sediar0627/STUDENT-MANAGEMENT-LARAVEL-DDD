<?php

namespace Src\Api\Enrollments\Domain\Entities;

use Src\Api\Courses\Domain\Entities\Course;
use Src\Api\Enrollments\Domain\ValueObjects\EnrollmentDate;
use Src\Api\Students\Domain\Entities\Student;

class Enrollment
{
	public function __construct(
		public int|null $id,
		public Student $student,
		public Course $course,
		public EnrollmentDate $enrolledAt,
	) {}

	public function id(): int|null
	{
		return $this->id;
	}

	public function student(): Student
	{
		return $this->student;
	}

	public function course(): Course
	{
		return $this->course;
	}

	public function enrolledAt(): EnrollmentDate
	{
		return $this->enrolledAt;
	}
}