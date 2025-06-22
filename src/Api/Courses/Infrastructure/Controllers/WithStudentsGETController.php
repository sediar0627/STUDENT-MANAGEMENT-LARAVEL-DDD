<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Courses\Application\WithStudentsUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;

class WithStudentsGETController extends Controller
{
	public function handle(): JsonResponse
	{
		$withStudentsUseCase = new WithStudentsUseCase(new EloquentCourseRepository());

		$coursesWithStudents = $withStudentsUseCase->execute();

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::OK->value,
				'courses_with_students' => (new CourseResource())->toArrayByCourses($coursesWithStudents),
			],
			status: ControllerStatusDescription::OK->httpCode()
		);
	}
}