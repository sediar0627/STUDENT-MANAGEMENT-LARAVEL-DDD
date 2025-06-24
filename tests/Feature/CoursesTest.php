<?php

use App\Models\Courses\Course;
use App\Models\Enrollments\Enrollment;
use App\Models\Students\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

$routes = [
	'create_course' => [
		'url' => '/api/v1/courses', 
		'method' => 'post',
		'success_status' => 201
	],
	'update_course' => [
		'url' => '/api/v1/courses/{id}',
		'method' => 'put',
		'success_status' => 200
	],
	'get_courses' => [
		'url' => '/api/v1/courses',
		'method' => 'get',
		'success_status' => 200
	],
	'find_course' => [
		'url' => '/api/v1/courses/{id}',
		'method' => 'get',
		'success_status' => 200
	],
	'delete_course' => [
		'url' => '/api/v1/courses/{id}',
		'method' => 'delete',
		'success_status' => 200
	],
	'get_courses_with_students' => [
		'url' => '/api/v1/courses/with-students',
		'method' => 'get',
		'success_status' => 200
	],
	'get_courses_enrollment_count' => [
		'url' => '/api/v1/courses/enrollment-count',
		'method' => 'get',
		'success_status' => 200
	]
];

test('Course routes without token', function () use ($routes){
	Course::factory()->create(); // Creamos un curso para que exista

	foreach ($routes as $routeData) {
		$method = $routeData['method'];
		$url = str_replace('{id}', 1, $routeData['url']); // Ej: /api/v1/courses/1

		$response = $this->withHeaders([
			'Accept' => 'application/json',
		])
		->{$method}($url);

		$response->assertStatus(401);
	}
});

test('Course routes with token and not permissions', function () use ($routes){
	Course::factory()->create(); // Creamos un curso para que exista

	$token = tokenWithStudentsPermissions();

	foreach ($routes as $routeData) {
		$method = $routeData['method'];
		$url = str_replace('{id}', 1, $routeData['url']); // Ej: /api/v1/courses/1

		$response = $this->withHeaders([
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $token,
		])
		->{$method}($url, [
			'title' => 'Test Course',
			'description' => 'Test Description',
			'start_date' => now()->format('Y-m-d'),
			'end_date' => now()->addDays(10)->format('Y-m-d')
		]);

		$response->assertStatus(403);
	}
});

test('Course routes with token and permissions', function () use ($routes){
	Course::factory()->create(); // Curso para ser elminado por usuario normal
	Course::factory()->create(); // Curso para ser eliminado por usuario super admin

	$token = tokenWithCoursesPermissions();

	foreach ($routes as $routeData) {
		$method = $routeData['method'];
		$url = str_replace('{id}', 1, $routeData['url']); // Ej: /api/v1/courses/1

		// Usuario normal
		$response = $this->withHeaders([
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $token,
		])
		->{$method}($url, [
			'title' => 'Test Course',
			'description' => 'Test Description',
			'start_date' => now()->format('Y-m-d'),
			'end_date' => now()->addDays(10)->format('Y-m-d')
		]);

		$response->assertStatus($routeData['success_status']);

		// Usuario super admin
		$token = tokenWithSuperAdminRole();

		$url = str_replace('{id}', 2, $routeData['url']); // Ej: /api/v1/courses/2

		$response = $this->withHeaders([
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $token,
		])
		->{$method}($url, [
			'title' => 'Test Course',
			'description' => 'Test Description',
			'start_date' => now()->format('Y-m-d'),
			'end_date' => now()->addDays(10)->format('Y-m-d')
		]);

		$response->assertStatus($routeData['success_status']);
	}
});

test('Create course', function () use ($routes){
	$token = tokenWithCoursesPermissions();

	$method = $routes['create_course']['method'];
	$url = $routes['create_course']['url'];

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->{$method}($url, [
		'title' => 'Test Course',
		'description' => 'Test Description',
		'start_date' => now()->format('Y-m-d'),
		'end_date' => now()->addDays(10)->format('Y-m-d')
	]);

	$response->assertStatus($routes['create_course']['success_status']);

	$this->assertDatabaseHas('courses', [
		'title' => 'Test Course',
		'description' => 'Test Description',
	]);
});

test('Create course with invalid data', function () use ($routes){
	$token = tokenWithCoursesPermissions();

	$method = $routes['create_course']['method'];
	$url = $routes['create_course']['url'];

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->{$method}($url, [
		'title' => '',
		'description' => '',
		'start_date' => now()->format('Y-m-d'),
		'end_date' => now()->format('Y-m-d')
	]);

	$response->assertStatus(422);

	$response->assertJson([
		'errors' => [
			'title' => [
				'The title field is required.'
			],
			'end_date' => [
				'The end date field must be a date after start date.'
			]
		]
	]);
});

test('Update course', function () use ($routes){
	$course = Course::factory()->create();

	$method = $routes['update_course']['method'];
	$url = str_replace('{id}', $course->id, $routes['update_course']['url']);

	$token = tokenWithCoursesPermissions();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->{$method}($url, [
		'title' => 'Test Course Updated',
		'description' => 'Test Description Updated',
		'start_date' => now()->format('Y-m-d'),
		'end_date' => now()->addDays(10)->format('Y-m-d')
	]);

	$response->assertStatus($routes['update_course']['success_status']);

	$this->assertDatabaseHas('courses', [
		'id' => $course->id,
		'title' => 'Test Course Updated',
		'description' => 'Test Description Updated',
	]);
});

test('Update course with invalid data', function () use ($routes){
	$course = Course::factory()->create();

	$url = str_replace('{id}', $course->id, $routes['update_course']['url']);

	$token = tokenWithCoursesPermissions();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->put($url, [
		'title' => '',
		'description' => '',
		'start_date' => now()->format('Y-m-d'),
		'end_date' => now()->format('Y-m-d')
	]);

	$response->assertStatus(422);

	$response->assertJson([
		'errors' => [
			'title' => [
				'The title field is required.'
			],
			'end_date' => [
				'The end date field must be a date after start date.'
			]
		]
	]);
});

test('Find course', function () use ($routes){
	$course = Course::factory()->create();

	$url = str_replace('{id}', $course->id, $routes['find_course']['url']);

	$token = tokenWithCoursesPermissions();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->get($url);

	$response->assertStatus($routes['find_course']['success_status']);

	$response->assertJson([
		'course' => [
			'id' => $course->id,
			'title' => $course->title,
			'description' => $course->description,
			'start_date' => $course->start_date->format('Y-m-d'),
			'end_date' => $course->end_date->format('Y-m-d'),
		]
	]);
});

test('Find course not found', function () use ($routes){
	$url = str_replace('{id}', 999999, $routes['find_course']['url']);

	$token = tokenWithCoursesPermissions();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->get($url);

	$response->assertStatus(404);

	$response->assertJson([
		'status' => 'not_found'
	]);
});

test('Delete course', function () use ($routes){
	$course = Course::factory()->create();

	$url = str_replace('{id}', $course->id, $routes['delete_course']['url']);

	$token = tokenWithCoursesPermissions();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->delete($url);

	$response->assertStatus($routes['delete_course']['success_status']);

	$this->assertSoftDeleted('courses', [
		'id' => $course->id,
	]);
});

test('Delete course not found', function () use ($routes){
	$url = str_replace('{id}', 999999, $routes['delete_course']['url']);

	$token = tokenWithCoursesPermissions();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->delete($url);

	$response->assertStatus(404);

	$response->assertJson([
		'status' => 'not_found'
	]);
});

test('Get courses', function () use ($routes){
	$token = tokenWithCoursesPermissions();

	$url = $routes['get_courses']['url'];

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->get($url);

	$response->assertStatus($routes['get_courses']['success_status']);

	$expectedCourses = Course::all()->map(function ($course) {
		return [
			'id' => $course->id,
			'title' => $course->title,
			'description' => $course->description,
			'start_date' => $course->start_date->format('Y-m-d'),
			'end_date' => $course->end_date->format('Y-m-d'),
		];
	})->toArray();

	$response->assertJson([
		'courses' => $expectedCourses
	]);
});

test('Get courses with students', function () use ($routes){
	$courseWithStudent = Course::factory()->create();
	$student = Student::factory()->create();
	Enrollment::factory()->create([
		'course_id' => $courseWithStudent->id,
		'student_id' => $student->id
	]);

	$courseWithoutStudent = Course::factory()->create();

	$token = tokenWithCoursesPermissions();

	$url = $routes['get_courses_with_students']['url'];

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->get($url);

	$response->assertStatus($routes['get_courses_with_students']['success_status']);

	$responseJson = $response->json();

	$this->assertCount(1, $responseJson['courses_with_students']); // Solo hay un curso con estudiante

	$existCourseWithoutStudentInResponse = collect($responseJson['courses_with_students'])
		->contains(function ($course) use ($courseWithoutStudent) {
			return $course['id'] === $courseWithoutStudent->id;
		});

	$this->assertFalse($existCourseWithoutStudentInResponse);
});

test('Get courses enrollment count', function () use ($routes){
	$courseWithStudent = Course::factory()->create();

	$student1 = Student::factory()->create();
	$student2 = Student::factory()->create();

	Enrollment::factory()->create([
		'course_id' => $courseWithStudent->id,
		'student_id' => $student1->id
	]);
	Enrollment::factory()->create([
		'course_id' => $courseWithStudent->id,
		'student_id' => $student2->id
	]);

	$courseWithoutStudent = Course::factory()->create();
	
	$token = tokenWithCoursesPermissions();

	$url = $routes['get_courses_enrollment_count']['url'];

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->get($url);

	$response->assertStatus($routes['get_courses_enrollment_count']['success_status']);

	$responseJson = $response->json();

	// Se deben mostrar todos los cursos, con la cantidad de estudiantes inscritos en cada uno
	$this->assertCount(2, $responseJson['courses_with_students_count']);

	$responseCourseWithStudent = $responseJson['courses_with_students_count'][0];
	$this->assertEquals($courseWithStudent->id, $responseCourseWithStudent['id']); // Validamos que sea el curso con estudiantes
	$this->assertEquals(2, $responseCourseWithStudent['students_count']); // Validamos que tenga 2 estudiantes inscritos

	$responseCourseWithoutStudent = $responseJson['courses_with_students_count'][1];
	$this->assertEquals($courseWithoutStudent->id, $responseCourseWithoutStudent['id']); // Validamos que sea el curso sin estudiantes
	$this->assertEquals(0, $responseCourseWithoutStudent['students_count']); // Validamos que no tenga estudiantes inscritos
});