<?php

namespace Src\Api\Students\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Students\Application\WithCoursesUseCase;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;
use Src\Api\Students\Infrastructure\Resources\StudentResource;

class WithCoursesGETController extends Controller
{
	public function handle(): JsonResponse
	{
		$withCoursesUseCase = new WithCoursesUseCase(new EloquentStudentRepository());

		$studentsWithCourses = $withCoursesUseCase->execute();

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::OK->value,
				'student_with_courses' => (new StudentResource())->toArrayByStudents($studentsWithCourses),
			],
			status: ControllerStatusDescription::OK->httpCode()
		);
	}
}