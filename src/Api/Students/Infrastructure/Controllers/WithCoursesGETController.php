<?php

namespace Src\Api\Students\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Students\Application\WithCoursesUseCase;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;
use Src\Api\Students\Infrastructure\Resources\StudentResource;
use Illuminate\Support\Facades\Gate;
use App\Models\Students\Student as EloquentStudent;
use Src\Api\Students\Infrastructure\Resources\StudentCollectionResource;

class WithCoursesGETController extends Controller
{
	public function handle(): JsonResponse
	{
		$authorize = Gate::inspect('viewAny', EloquentStudent::class);

		if(!$authorize->allowed()){
			return response()->json(
				data: [
					'status' => ControllerStatusDescription::FORBIDDEN->value,
				],
				status: ControllerStatusDescription::FORBIDDEN->httpCode()
			);
		}

		$withCoursesUseCase = new WithCoursesUseCase(new EloquentStudentRepository());

		$studentsWithCourses = $withCoursesUseCase->execute();

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::OK->value,
				'student_with_courses' => new StudentCollectionResource($studentsWithCourses),
			],
			status: ControllerStatusDescription::OK->httpCode()
		);
	}
}