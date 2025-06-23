<?php

namespace Src\Api\Courses\Infrastructure\Controllers;

use App\Enum\ControllerStatusDescription;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Src\Api\Courses\Application\SaveCourseUseCase;
use Src\Api\Courses\Infrastructure\Repositories\EloquentCourseRepository;
use Src\Api\Courses\Infrastructure\Request\CourseRequest;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;
use App\Models\Courses\Course as EloquentCourse;

class UpdateCoursePUTController extends Controller
{
    private EloquentCourseRepository $repository;

    public function __construct()
    {
        $this->repository = new EloquentCourseRepository();
    }

    public function handle(CourseRequest $request, string $id): JsonResponse
    {
        $authorized = Gate::inspect('update', [EloquentCourse::class, $id]);

        if (!$authorized->allowed()) {
            return response()->json(
                data: [
                    'status' => ControllerStatusDescription::FORBIDDEN->value,
                ],
                status: ControllerStatusDescription::FORBIDDEN->httpCode()
            );
        }

        $dbCourse = $this->repository->findById($id);

        if (!$dbCourse) {
            return response()->json(
                data: [
                    'status' => ControllerStatusDescription::NOT_FOUND->value,
                ],
                status: ControllerStatusDescription::NOT_FOUND->httpCode()
            );
        }

        $requestData = $request->validated();

        $saveCourseUseCase = new SaveCourseUseCase($this->repository);

        $course = $saveCourseUseCase->execute(
            id: $dbCourse->id(),
            title: $requestData['title'],
            description: $requestData['description'],
            startDate: $requestData['start_date'],
            endDate: $requestData['end_date']
        );

        return response()->json(
            data: [
                'status' => ControllerStatusDescription::UPDATED->value,
                'course' => new CourseResource($course),
            ],
            status: ControllerStatusDescription::UPDATED->httpCode()
        );
    }
}
