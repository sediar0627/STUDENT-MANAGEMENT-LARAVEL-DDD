<?php

namespace Src\Api\Courses\Domain\Entities;

use Src\Api\Courses\Domain\ValueObjects\CourseDate;

class Course
{
    public function __construct(
        private int|null $id,
        private string $title,
        private string|null $description,
        private CourseDate $startDate,
        private CourseDate $endDate,
		private array $students = [],
		private int|null $students_count = null,
    ) {

	}

	public function id(): int|null
	{
		return $this->id;
	}

	public function title(): string
	{
		return $this->title;
	}

	public function description(): string|null
	{
		return $this->description;
	}

	public function startDate(): CourseDate
	{
		return $this->startDate;
	}

	public function endDate(): CourseDate
	{
		return $this->endDate;
	}

	public function students(): array
	{
		return $this->students;
	}

	public function studentsCount(bool $count = false): int|null
	{
		if($count){
			return count($this->students);
		}

		return $this->students_count;
	}
}