<?php

namespace Src\Api\Students\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Students\Application\FindStudentByIdUseCase;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;
use Src\Api\Students\Infrastructure\Resources\StudentResource;

class FindStudentByIdGETController extends Controller
{
	public function handle(string $id): JsonResponse
	{
		$findStudentByIdUseCase = new FindStudentByIdUseCase(new EloquentStudentRepository());

		$student = $findStudentByIdUseCase->execute($id);

		if (!$student) {
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
				'student' => (new StudentResource())->toArrayByStudent($student),
			],
			status: ControllerStatusDescription::OK->httpCode()
		);
	}
}