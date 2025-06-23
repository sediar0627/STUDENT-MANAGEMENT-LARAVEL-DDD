<?php

namespace Src\Api\Courses\Infrastructure\Request;

use App\Http\Requests\CustomFormRequest;

class CourseRequest extends CustomFormRequest
{
	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string', 'max:255'],
			'start_date' => ['required', 'date', 'date_format:Y-m-d'],
			'end_date' => ['required', 'date', 'date_format:Y-m-d', 'after:start_date'],
		];
	}
}