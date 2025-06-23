<?php

namespace Src\Api\Students\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Src\Api\Students\Application\SaveStudentUseCase;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;
use Src\Api\Students\Infrastructure\Request\StudentRequest;
use Src\Api\Students\Infrastructure\Resources\StudentResource;
use App\Models\Students\Student as EloquentStudent;
class UpdateStudentPUTController extends Controller
{
	private EloquentStudentRepository $studentRepository;

	public function __construct()
	{
		$this->studentRepository = new EloquentStudentRepository();
	}

	public function handle(StudentRequest $request, string $id): JsonResponse
	{
		$authorize = Gate::inspect('update', [EloquentStudent::class, $id]);

		if(!$authorize->allowed()){
			return response()->json(
				data: [
					'status' => ControllerStatusDescription::FORBIDDEN->value,
				],
				status: ControllerStatusDescription::FORBIDDEN->httpCode()
			);
		}
		
		$dbStudent = $this->studentRepository->findById($id);

		if (!$dbStudent) {
			return response()->json(
				data: [
					'status' => ControllerStatusDescription::NOT_FOUND->value,
				],
				status: ControllerStatusDescription::NOT_FOUND->httpCode()
			);
		}

		$requestData = $request->validated();

		$saveStudentUseCase = new SaveStudentUseCase($this->studentRepository);

		$student = $saveStudentUseCase->execute(
			id: $dbStudent->id(),
			email: $requestData['email'],
			firstName: $requestData['first_name'],
			lastName: $requestData['last_name']
		);

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::UPDATED->value,
				'student' => new StudentResource($student),
			],
			status: ControllerStatusDescription::UPDATED->httpCode()
		);
	}
}