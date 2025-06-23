<?php

namespace Src\Api\Students\Infrastructure\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Src\Api\Students\Domain\Entities\Student;

class StudentCollectionResource extends ResourceCollection
{
	/**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function (Student $student) {
            return new StudentResource($student);
        })
        ->toArray();
    }
}