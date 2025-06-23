<?php

namespace Src\Api\Enrollments\Infrastucture\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;
use Src\Api\Enrollments\Domain\Entities\Enrollment;
use Src\Api\Students\Infrastructure\Resources\StudentResource;

class EnrollmentResource extends JsonResource
{
	/**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     */
    public function __construct(Enrollment $resource)
    {
        parent::__construct($resource);
    }
	
	/**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
	public function toArray(Request $request): array
	{
		return [
			'id' => $this->id(),
			'student' => new StudentResource($this->student()),
			'course' => new CourseResource($this->course()),
			'enrolled_at' => $this->enrolledAt()->toFormat(),
		];
	}
}