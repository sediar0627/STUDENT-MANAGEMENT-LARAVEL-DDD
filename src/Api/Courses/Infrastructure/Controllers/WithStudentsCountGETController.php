<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Courses\Application\WithStudentsCountUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;

class WithStudentsCountGETController extends Controller
{
	public function handle(): JsonResponse
	{
		$withStudentsCountUseCase = new WithStudentsCountUseCase(new EloquentCourseRepository());

		$coursesWithStudentsCount = $withStudentsCountUseCase->execute();

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::OK->value,
				'courses_with_students_count' => (new CourseResource())->toArrayByCourses($coursesWithStudentsCount),
			],
			status: ControllerStatusDescription::OK->httpCode()
		);
	}
}