<?php

namespace Src\Api\Enrollments\Domain\ValueObjects;

use Carbon\Carbon;

class EnrollmentDate
{
    public function __construct(
		private string|Carbon $date
	) {	
		if(is_string($date) && !Carbon::hasFormat($date, 'Y-m-d H:i:s')){
			throw new \InvalidArgumentException('Invalid date format');
		}

		if(is_string($date)){
			$date = Carbon::parse($date);
		}

		$this->date = $date;
	}

	public function value(): Carbon
	{
		return $this->date;
	}

	public function toFormat(string $format = 'Y-m-d H:i:s'): string
	{
		return $this->date->format($format);
	}
}