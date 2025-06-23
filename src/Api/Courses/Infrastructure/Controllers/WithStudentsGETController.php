<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Src\Api\Courses\Application\WithStudentsUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;
use App\Models\Courses\Course as EloquentCourse;

class WithStudentsGETController extends Controller
{
    public function handle(): JsonResponse
    {
        $authorized = Gate::inspect('viewAny', EloquentCourse::class);

        if (!$authorized->allowed()) {
            return response()->json(
                data: [
                    'status' => ControllerStatusDescription::FORBIDDEN->value,
                ],
                status: ControllerStatusDescription::FORBIDDEN->httpCode()
            );
        }

        $withStudentsUseCase = new WithStudentsUseCase(new EloquentCourseRepository());

        $coursesWithStudents = $withStudentsUseCase->execute();

        return response()->json(
            data: [
                'status' => ControllerStatusDescription::OK->value,
                'courses_with_students' => (new CourseResource())->toArrayByCourses($coursesWithStudents),
            ],
            status: ControllerStatusDescription::OK->httpCode()
        );
    }
}
