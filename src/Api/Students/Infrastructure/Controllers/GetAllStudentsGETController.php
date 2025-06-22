<?php

namespace Src\Api\Students\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Students\Application\GetAllStudentsUseCase;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;
use Src\Api\Students\Infrastructure\Resources\StudentResource;

class GetAllStudentsGETController extends Controller
{
	public function __invoke(): JsonResponse
	{
		$getAllStudentsUseCase = new GetAllStudentsUseCase(new EloquentStudentRepository());

		$students = $getAllStudentsUseCase->execute();

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::OK->value,
				'courses' => (new StudentResource())->toArrayByStudents($students),
			],
			status: ControllerStatusDescription::OK->httpCode()
		);
	}
}