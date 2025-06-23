<?php

namespace Src\Api\Courses\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Src\Api\Courses\Domain\Entities\Course;

class CourseCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function (Course $course) {
            return new CourseResource($course);
        })
        ->toArray();
    }
}
