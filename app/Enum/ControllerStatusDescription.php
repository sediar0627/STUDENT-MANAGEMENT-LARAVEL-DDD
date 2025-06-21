<?php

namespace App\Enum;

enum ControllerStatusDescription: string
{
	case OK = 'ok';
	case CREATED = 'created';
	case UPDATED = 'updated';
	case DELETED = 'deleted';
	case NOT_FOUND = 'not_found';
	case BAD_REQUEST = 'bad_request';
	case UNAUTHORIZED = 'unauthorized';
	case FORBIDDEN = 'forbidden';
	case INTERNAL_SERVER_ERROR = 'server_error';

	public function httpCode(): int
	{
		return match ($this) {
			self::OK => 200,
			self::CREATED => 201,
			self::UPDATED => 200,
			self::DELETED => 200,
			self::NOT_FOUND => 404,
			self::BAD_REQUEST => 422,
			self::UNAUTHORIZED => 401,
			self::FORBIDDEN => 403,
			self::INTERNAL_SERVER_ERROR => 500,
		};
	}
}