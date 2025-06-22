<?php

namespace Src\Api\Enrollments\Infrastucture\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Enrollments\Application\CreateEnrollmentUseCase;
use Src\Api\Enrollments\Domain\Entities\Enrollment;
use Src\Api\Enrollments\Domain\ValueObjects\EnrollmentDate;
use Src\Api\Enrollments\Infrastucture\Repositories\EloquentEnrollmentRepository;
use Src\Api\Enrollments\Infrastucture\Requests\EnrollmentRequest;
use Src\Api\Enrollments\Infrastucture\Resources\EnrollmentResource;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;

class CreateEnrollmentPOSTController extends Controller
{
	public function handle(EnrollmentRequest $request): JsonResponse
	{
		$requestData = $request->validated();

		$student = (new EloquentStudentRepository())->findById($requestData['student_id']);
		$course = (new EloquentCourseRepository())->findById($requestData['course_id']);

		$createEnrollmentUseCase = new CreateEnrollmentUseCase(new EloquentEnrollmentRepository());

		$enrollment = $createEnrollmentUseCase->execute(
			enrollment: new Enrollment(
				id: null,
				student: $student,
				course: $course,
				enrolledAt: new EnrollmentDate(now()),
			)
		);

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::CREATED->value,
				'enrollment' => (new EnrollmentResource())->toArray($enrollment),
			],
			status: ControllerStatusDescription::CREATED->httpCode()
		);
	}
}