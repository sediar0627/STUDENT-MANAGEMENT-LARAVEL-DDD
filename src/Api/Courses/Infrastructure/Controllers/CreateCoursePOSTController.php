<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Src\Api\Courses\Application\SaveCourseUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Courses\Infrastructure\Request\CourseRequest;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;
use App\Models\Courses\Course as EloquentCourse;

class CreateCoursePOSTController extends Controller
{
	public function handle(CourseRequest $request): JsonResponse
	{
		$authorized = Gate::inspect('create', EloquentCourse::class);

		if(!$authorized->allowed()) {
			return response()->json(
				data: [
					'status' => ControllerStatusDescription::FORBIDDEN->value,
				],
				status: ControllerStatusDescription::FORBIDDEN->httpCode()
			);
		}

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