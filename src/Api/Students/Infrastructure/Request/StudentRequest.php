<?php

namespace Src\Api\Students\Infrastructure\Request;

use App\Http\Requests\CustomFormRequest;
use Src\Api\Students\Infrastructure\Rules\StudentEmailRule;

class StudentRequest extends CustomFormRequest
{
	public function rules(): array
	{
		return [
			'email' => ['required', 'email', new StudentEmailRule($this->route('id'))],
			'first_name' => ['required', 'string', 'max:255'],
			'last_name' => ['required', 'string', 'max:255'],
		];
	}
}