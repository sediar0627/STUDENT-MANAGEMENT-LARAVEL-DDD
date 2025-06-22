<?php

namespace Src\Api\Courses\Infrastructure\Repositories;

use Src\Api\Courses\Domain\Entities\Course;
use Src\Api\Courses\Domain\Interfaces\CourseRepositoryInterface;
use App\Models\Courses\Course as EloquentCourse;
use Src\Api\Courses\Domain\ValueObjects\CourseDate;
use App\Models\Students\Student as EloquentStudent;
use Src\Api\Students\Domain\Entities\Student;
use Src\Api\Students\Domain\ValueObjects\StudentEmail;

class EloquentCourseRepository implements CourseRepositoryInterface
{
	public function all(): array
	{
		return EloquentCourse::all()
			->map(function (EloquentCourse $course) {
				return new Course(
					id: $course->id,
					title: $course->title,
					description: $course->description,
					startDate: new CourseDate($course->start_date),
					endDate: new CourseDate($course->end_date),
				);
			})
			->toArray();
	}

	public function findById(int $id): ?Course
	{
		$course = EloquentCourse::find($id);

		if(!$course){
			return null;
		}

		return new Course(
			id: $course->id,
			title: $course->title,
			description: $course->description,
			startDate: new CourseDate($course->start_date),
			endDate: new CourseDate($course->end_date),
		);
	}

	public function save(Course $course): Course
	{
		$eloquentCourse = EloquentCourse::updateOrCreate(
			[
				'id' => $course->id(),
			], 
			[
				'title' => $course->title(),
				'description' => $course->description(),
				'start_date' => $course->startDate()->value(),
				'end_date' => $course->endDate()->value(),
			]
		);

		return new Course(
			id: $eloquentCourse->id,
			title: $eloquentCourse->title,
			description: $eloquentCourse->description,
			startDate: new CourseDate($eloquentCourse->start_date),
			endDate: new CourseDate($eloquentCourse->end_date),
		);
	}

	public function delete(int $id): void
	{
		EloquentCourse::destroy($id);
	}

	public function allWithStudents(): array
	{
		return EloquentCourse::with(['students'])
			->withStudents()
			->get()
			->map(function (EloquentCourse $course) {
				$students = $course->students->map(function (EloquentStudent $student) {
					return new Student(
						id: $student->id,
						email: new StudentEmail($student->email),
						first_name: $student->first_name,
						last_name: $student->last_name,
					);
				})->toArray();

				return new Course(
					id: $course->id,
					title: $course->title,
					description: $course->description,
					startDate: new CourseDate($course->start_date),
					endDate: new CourseDate($course->end_date),
					students: $students,
				);
			})
			->toArray();
	}
}