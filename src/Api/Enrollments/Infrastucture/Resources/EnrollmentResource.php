<?php

namespace Src\Api\Enrollments\Infrastucture\Resources;

use Src\Api\Enrollments\Domain\Entities\Enrollment;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;
use Src\Api\Students\Infrastructure\Resources\StudentResource;

class EnrollmentResource
{
	public function toArray(Enrollment $enrollment): array
	{
		return [
			'id' => $enrollment->id(),
			'student' => (new StudentResource())->toArrayByStudent($enrollment->student()),
			'course' => (new CourseResource())->toArrayByCourse($enrollment->course()),
			'enrolled_at' => $enrollment->enrolledAt()->toFormat(),
		];
	}
}