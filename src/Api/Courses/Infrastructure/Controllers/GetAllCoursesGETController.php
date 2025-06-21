<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Courses\Application\GetAllCoursesUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;

class GetAllCoursesGETController extends Controller
{
	public function __invoke(): JsonResponse
	{
		$getAllCoursesUseCase = new GetAllCoursesUseCase(new EloquentCourseRepository());

		$courses = $getAllCoursesUseCase->execute();

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::OK->value,
				'courses' => (new CourseResource())->toArrayByCourses($courses),
			],
			status: ControllerStatusDescription::OK->httpCode()
		);
	}
}