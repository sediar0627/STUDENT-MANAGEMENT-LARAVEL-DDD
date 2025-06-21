<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Courses\Application\FindCourseByIdUseCase;
use Src\Api\Courses\Application\SaveCourseUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Courses\Infrastructure\Request\CourseRequest;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;

class FindCourseByIdGETController extends Controller
{
	public function __invoke(string $id): JsonResponse
	{
		$findCourseByIdUseCase = new FindCourseByIdUseCase(new EloquentCourseRepository());
		$course = $findCourseByIdUseCase->execute($id);

		if (!$course) {
			return response()->json(
				data: [
					'status' => ControllerStatusDescription::NOT_FOUND->value,
				],
				status: ControllerStatusDescription::NOT_FOUND->httpCode()
			);
		}

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::OK->value,
				'course' => (new CourseResource())->toArrayByCourse($course),
			],
			status: ControllerStatusDescription::OK->httpCode()
		);
	}
}