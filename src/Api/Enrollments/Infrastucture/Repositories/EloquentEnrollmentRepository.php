<?php

namespace Src\Api\Enrollments\Infrastucture\Repositories;

use App\Models\Enrollments\Enrollment as EloquentEnrollment;
use Src\Api\Courses\Domain\Entities\Course;
use Src\Api\Courses\Domain\ValueObjects\CourseDate;
use Src\Api\Enrollments\Domain\Entities\Enrollment;
use Src\Api\Enrollments\Domain\Interfaces\EnrollmentRepositoryInterface;
use Src\Api\Enrollments\Domain\ValueObjects\EnrollmentDate;
use Src\Api\Students\Domain\Entities\Student;
use Src\Api\Students\Domain\ValueObjects\StudentEmail;

class EloquentEnrollmentRepository implements EnrollmentRepositoryInterface
{
	public function create(Enrollment $enrollment): Enrollment
	{
		$eloquentEnrollment = EloquentEnrollment::create([
			'course_id' => $enrollment->course()->id(),
			'student_id' => $enrollment->student()->id(),
			'enrolled_at' => $enrollment->enrolledAt()->value(),
		]);

		return new Enrollment(
			id: $eloquentEnrollment->id,
			student: $enrollment->student(),
			course: $enrollment->course(),
			enrolledAt: new EnrollmentDate($eloquentEnrollment->enrolled_at),
		);
	}

	public function findByStudentIdAndCourseId(int $studentId, int $courseId): ?Enrollment
	{
		$eloquentEnrollment = EloquentEnrollment::with(['student', 'course'])
			->where('student_id', $studentId)
			->where('course_id', $courseId)
			->first();

		if (!$eloquentEnrollment) {
			return null;
		}

		$eloquentStudent = $eloquentEnrollment->student;

		$student = new Student(
			id: $eloquentStudent->id,
			email: new StudentEmail($eloquentStudent->email),
			first_name: $eloquentStudent->first_name,
			last_name: $eloquentStudent->last_name,
		);

		$eloquentCourse = $eloquentEnrollment->course;

		$course = new Course(
			id: $eloquentCourse->id,
			title: $eloquentCourse->title,
			description: $eloquentCourse->description,
			startDate: new CourseDate($eloquentCourse->start_date),
			endDate: new CourseDate($eloquentCourse->end_date),
		);

		return new Enrollment(
			id: $eloquentEnrollment->id,
			student: $student,	
			course: $course,
			enrolledAt: new EnrollmentDate($eloquentEnrollment->enrolled_at),
		);
	}
}