<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Courses\Application\DeleteCourseByIdUseCase;
use Src\Api\Courses\Application\FindCourseByIdUseCase;
use Src\Api\Courses\Application\SaveCourseUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Courses\Infrastructure\Request\CourseRequest;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;

class DeleteCourseByIdDELETEController extends Controller
{
	public function __invoke(string $id): JsonResponse
	{
		$deleteCourseByIdUseCase = new DeleteCourseByIdUseCase(new EloquentCourseRepository());
		$deleteCourseByIdUseCase->execute($id);

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::DELETED->value,
			],
			status: ControllerStatusDescription::DELETED->httpCode()
		);
	}
}