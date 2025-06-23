<?php

namespace Src\Api\Students\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Students\Application\SaveStudentUseCase;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;
use Src\Api\Students\Infrastructure\Request\StudentRequest;
use Src\Api\Students\Infrastructure\Resources\StudentResource;
use App\Models\Students\Student as EloquentStudent;
use Illuminate\Support\Facades\Gate;

class CreateStudentPOSTController extends Controller
{
	private EloquentStudentRepository $studentRepository;

	public function __construct()
	{
		$this->studentRepository = new EloquentStudentRepository();
	}

	public function handle(StudentRequest $request): JsonResponse
	{
		$authorize = Gate::inspect('create', EloquentStudent::class);

		if(!$authorize->allowed()){
			return response()->json(
				data: [
					'status' => ControllerStatusDescription::FORBIDDEN->value,
				],
				status: ControllerStatusDescription::FORBIDDEN->httpCode()
			);
		}

		$requestData = $request->validated();

		$saveStudentUseCase = new SaveStudentUseCase($this->studentRepository);

		$student = $saveStudentUseCase->execute(
			id: null,
			email: $requestData['email'],
			firstName: $requestData['first_name'],
			lastName: $requestData['last_name']
		);

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::CREATED->value,
				'student' => (new StudentResource())->toArrayByStudent($student),
			],
			status: ControllerStatusDescription::CREATED->httpCode()
		);
	}
}