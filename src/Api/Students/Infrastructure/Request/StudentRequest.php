<?php

namespace Src\Api\Students\Infrastructure\Request;

use App\Http\Request\CustomFormRequest;

class StudentRequest extends CustomFormRequest
{
	public function rules(): array
	{
		return [
			'email' => ['required', 'email', 'unique:students,email'],
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
		];
	}
}