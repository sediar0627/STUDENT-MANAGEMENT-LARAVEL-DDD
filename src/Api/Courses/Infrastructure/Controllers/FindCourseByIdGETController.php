<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Src\Api\Courses\Application\FindCourseByIdUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;
use App\Models\Courses\Course as EloquentCourse;

class FindCourseByIdGETController extends Controller
{
    public function handle(string $id): JsonResponse
    {
        $authorized = Gate::inspect('view', [EloquentCourse::class, $id]);

        if (!$authorized->allowed()) {

            return response()->json(
                data: [
                    'status' => ControllerStatusDescription::FORBIDDEN->value,
                ],
                status: ControllerStatusDescription::FORBIDDEN->httpCode()
            );
        }

        $findCourseByIdUseCase = new FindCourseByIdUseCase(new EloquentCourseRepository());
        $course = $findCourseByIdUseCase->execute($id);

        if (!$course) {
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
                'course' => (new CourseResource())->toArrayByCourse($course),
            ],
            status: ControllerStatusDescription::OK->httpCode()
        );
    }
}
