<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Courses\Application\SaveCourseUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Courses\Infrastructure\Request\CourseRequest;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;

class CreateCoursePOSTController extends Controller
{
	public function __invoke(CourseRequest $request): JsonResponse
	{
		$requestData = $request->validated();

		$saveCourseUseCase = new SaveCourseUseCase(new EloquentCourseRepository());

		$course = $saveCourseUseCase->execute(
			id: null,
			title: $requestData['title'],
			description: $requestData['description'],
			startDate: $requestData['start_date'],
			endDate: $requestData['end_date']
		);

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::CREATED->value,
				'course' => (new CourseResource())->toArrayByCourse($course),
			],
			status: ControllerStatusDescription::CREATED->httpCode()
		);
	}
}