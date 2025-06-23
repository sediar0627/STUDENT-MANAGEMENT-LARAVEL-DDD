<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Src\Api\Courses\Application\DeleteCourseByIdUseCase;
use Src\Api\Courses\Application\FindCourseByIdUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use App\Models\Courses\Course as EloquentCourse;

class DeleteCourseByIdDELETEController extends Controller
{
    public function __construct(
        private EloquentCourseRepository $courseRepository
    ) {
    }

    public function handle(string $id): JsonResponse
    {
        $authorized = Gate::inspect('delete', [EloquentCourse::class, $id]);

        if (!$authorized->allowed()) {
            return response()->json(
                data: [
                    'status' => ControllerStatusDescription::FORBIDDEN->value,
                ],
                status: ControllerStatusDescription::FORBIDDEN->httpCode()
            );
        }

        $findCourseByIdUseCase = new FindCourseByIdUseCase($this->courseRepository);

        $course = $findCourseByIdUseCase->execute($id);

        if (!$course) {
            return response()->json(
                data: [
                    'status' => ControllerStatusDescription::NOT_FOUND->value,
                ],
                status: ControllerStatusDescription::NOT_FOUND->httpCode()
            );
        }

        $deleteCourseByIdUseCase = new DeleteCourseByIdUseCase($this->courseRepository);
        $deleteCourseByIdUseCase->execute($id);

        return response()->json(
            data: [
                'status' => ControllerStatusDescription::DELETED->value,
            ],
            status: ControllerStatusDescription::DELETED->httpCode()
        );
    }
}
