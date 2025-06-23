<?php

namespace Src\Api\Students\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Students\Application\GetAllStudentsUseCase;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;
use Src\Api\Students\Infrastructure\Resources\StudentResource;
use Illuminate\Support\Facades\Gate;
use App\Models\Students\Student as EloquentStudent;

class GetAllStudentsGETController extends Controller
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