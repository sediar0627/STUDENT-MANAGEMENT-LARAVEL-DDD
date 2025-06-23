<?php

namespace App\Http\Requests;

use App\Enum\ControllerStatusDescription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomFormRequest extends FormRequest
{
	public function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(
			response: response()->json([
				'status' => ControllerStatusDescription::BAD_REQUEST->value,
				'errors'  => $validator->errors()
			],
			status: ControllerStatusDescription::BAD_REQUEST->httpCode()
		));
	}
}