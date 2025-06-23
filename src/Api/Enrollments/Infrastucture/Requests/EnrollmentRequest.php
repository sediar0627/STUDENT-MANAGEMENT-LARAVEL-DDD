<?php

namespace Src\Api\Enrollments\Infrastucture\Requests;

use App\Http\Requests\CustomFormRequest;
use Src\Api\Enrollments\Infrastucture\Repositories\EloquentEnrollmentRepository;

class EnrollmentRequest extends CustomFormRequest
{

	public function __construct(
		private EloquentEnrollmentRepository $eloquentEnrollmentRepository,
	) {
		parent::__construct();

		$this->eloquentEnrollmentRepository = new EloquentEnrollmentRepository();
	}
	
	public function rules(): array
	{
		$enrollment = $this->eloquentEnrollmentRepository->findByStudentIdAndCourseId(
			studentId: $this->input('student_id', 0),
			courseId: $this->input('course_id', 0),
		);

		return [
			'student_id' => [
				'required', 
				'exists:students,id',
				function ($attribute, $value, $fail) use ($enrollment) {
					if ($enrollment) {
						$fail('Student already enrolled in this course');
					}
				}
			],
			'course_id' => ['required', 'exists:courses,id'],
		];
	}
}