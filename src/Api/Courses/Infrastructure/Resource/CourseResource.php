<?php

namespace Src\Api\Courses\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Api\Courses\Domain\Entities\Course;

class CourseResource extends JsonResource
{
	/**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     */
    public function __construct(Course $resource)
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
			'title' => $this->title(),
			'description' => $this->description(),
			'start_date' => $this->startDate()->toFormat(),
			'end_date' => $this->endDate()->toFormat(),
		];

		if (count($this->students()) > 0) {
			
		}

		if (!is_null($this->studentsCount())) {
			$data['students_count'] = $this->studentsCount();
		}

        return $data;
    }
}
