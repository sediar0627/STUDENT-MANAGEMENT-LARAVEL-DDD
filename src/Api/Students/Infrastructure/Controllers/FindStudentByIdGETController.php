<?php

namespace Src\Api\Students\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Src\Api\Students\Application\FindStudentByIdUseCase;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;
use Src\Api\Students\Infrastructure\Resources\StudentResource;
use App\Models\Students\Student as EloquentStudent;

class FindStudentByIdGETController extends Controller
{
	public function handle(string $id): JsonResponse
	{
		$authorize = Gate::inspect('view', [EloquentStudent::class, $id]);

		if(!$authorize->allowed()){
			return response()->json(
				data: [
					'status' => ControllerStatusDescription::FORBIDDEN->value,
				],
				status: ControllerStatusDescription::FORBIDDEN->httpCode()
			);
		}

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
				'student' => new StudentResource($student),
			],
			status: ControllerStatusDescription::OK->httpCode()
		);
	}
}