<?php

namespace Src\Api\Students\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\Api\Students\Application\DeleteStudentByIdUseCase;
use Src\Api\Students\Application\FindStudentByIdUseCase;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;

class DeleteStudentByIdDELETEController extends Controller
{
	public function __construct(
		private EloquentStudentRepository $studentRepository
	){}

	public function handle(string $id): JsonResponse
	{
		$findStudentByIdUseCase = new FindStudentByIdUseCase($this->studentRepository);

		$student = $findStudentByIdUseCase->execute($id);

		if (!$student) {
			return response()->json(
				data: [
					'status' => ControllerStatusDescription::NOT_FOUND->value,
				],
				status: ControllerStatusDescription::NOT_FOUND->httpCode()
			);
		}

		$deleteStudentByIdUseCase = new DeleteStudentByIdUseCase($this->studentRepository);

		$deleteStudentByIdUseCase->execute($id);

		return response()->json(
			data: [
				'status' => ControllerStatusDescription::DELETED->value,
			],
			status: ControllerStatusDescription::DELETED->httpCode()
		);
	}
}