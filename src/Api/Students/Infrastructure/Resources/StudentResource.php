<?php

namespace Src\Api\Students\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Api\Courses\Domain\Entities\Course;
use Src\Api\Courses\Infrastructure\Resource\CourseResource;
use Src\Api\Students\Domain\Entities\Student;

class StudentResource extends JsonResource
{
	/**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     */
    public function __construct(Student $resource)
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
        $data = [
			'id' => $this->id(),
			'email' => $this->email()->value(),
			'first_name' => $this->firstName(),
			'last_name' => $this->lastName(),
		];

		if(count($this->courses()) > 0){
			$data['courses'] = array_map(function (Course $course) {
				return new CourseResource($course);
			}, $this->courses());
		}

        return $data;
    }
}