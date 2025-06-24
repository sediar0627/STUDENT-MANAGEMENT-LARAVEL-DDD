<?php

use App\Models\Courses\Course;
use App\Models\Enrollments\Enrollment;
use App\Models\Students\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

$routes = [
	'create_student' => [
		'url' => '/api/v1/students', 
		'method' => 'post',
		'success_status' => 201
	],
	'update_student' => [
		'url' => '/api/v1/students/{id}',
		'method' => 'put',
		'success_status' => 200
	],
	'get_students' => [
		'url' => '/api/v1/students',
		'method' => 'get',
		'success_status' => 200
	],
	'find_student' => [
		'url' => '/api/v1/students/{id}',
		'method' => 'get',
		'success_status' => 200
	],
	'delete_student' => [
		'url' => '/api/v1/students/{id}',
		'method' => 'delete',
		'success_status' => 200
	],
	'get_students_with_courses' => [
		'url' => '/api/v1/students/with-courses',
		'method' => 'get',
		'success_status' => 200
	]
];

test('Students routes without token', function () use ($routes){
	Student::factory()->create(); // Creamos un estudiante para que exista

	foreach ($routes as $routeData) {
		$method = $routeData['method'];
		$url = str_replace('{id}', 1, $routeData['url']); // Ej: /api/v1/students/1

		$response = $this->withHeaders([
			'Accept' => 'application/json',
		])
		->{$method}($url);

		$response->assertStatus(401);
	}
});

test('Students routes with token and not permissions', function () use ($routes){
	Student::factory()->create(); // Creamos un estudiante para que exista

	$token = tokenWithCoursesPermissions();

	foreach ($routes as $routeData) {
		$method = $routeData['method'];
		$url = str_replace('{id}', 1, $routeData['url']); // Ej: /api/v1/students/1

		$response = $this->withHeaders([
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $token,
		])
		->{$method}($url, [
			'email' => 'usuario@prueba.com',
			'first_name' => 'Usuario',
			'last_name' => 'Prueba'
		]);

		$response->assertStatus(403);
	}
});

test('Students routes with token and permissions', function () use ($routes){
	Student::factory()->create(); // Estudiante para ser elminado por usuario normal
	Student::factory()->create(); // Estudiante para ser eliminado por usuario super admin

	$successToken = tokenWithStudentsPermissions();
	$superAdminToken = tokenWithSuperAdminRole();

	foreach ($routes as $routeData) {
		$method = $routeData['method'];
		$url = str_replace('{id}', 1, $routeData['url']); // Ej: /api/v1/students/1

		// Usuario normal
		$response = $this->withHeaders([
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $successToken,
		])
		->{$method}($url, [
			'email' => fake()->email(),
			'first_name' => 'Usuario 1',
			'last_name' => 'Prueba 1'
		]);

		$response->assertStatus($routeData['success_status']);

		// Usuario super admin
		$url = str_replace('{id}', 2, $routeData['url']); // Ej: /api/v1/students/2

		$response = $this->withHeaders([
			'Accept' => 'application/json',
			'Authorization' => 'Bearer ' . $superAdminToken,
		])
		->{$method}($url, [
			'email' => fake()->email(),
			'first_name' => 'Usuario 2',
			'last_name' => 'Prueba 2'
		]);

		$response->assertStatus($routeData['success_status']);
	}
});

test('Create student', function () use ($routes){
	$token = tokenWithStudentsPermissions();

	$method = $routes['create_student']['method'];
	$url = $routes['create_student']['url'];

	$email = fake()->email();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->{$method}($url, [
		'email' => $email,
		'first_name' => 'Estudiante',
		'last_name' => 'Test'
	]);

	$response->assertStatus($routes['create_student']['success_status']);

	$this->assertDatabaseHas('students', [
		'email' => $email,
	]);
});

test('Create student with invalid data', function () use ($routes){
	$prevStudent = Student::factory()->create();

	$token = tokenWithStudentsPermissions();

	$method = $routes['create_student']['method'];
	$url = $routes['create_student']['url'];

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->{$method}($url, [
		'email' => $prevStudent->email,
		'first_name' => '',
		'last_name' => ''
	]);

	$response->assertStatus(422);

	$response->assertJson([
		'errors' => [
			'email' => [
				'The email has already been taken.'
			],
			'first_name' => [
				'The first name field is required.'
			],
			'last_name' => [
				'The last name field is required.'
			]
		]
	]);
});

test('Update student', function () use ($routes){
	$student = Student::factory()->create();

	$method = $routes['update_student']['method'];
	$url = str_replace('{id}', $student->id, $routes['update_student']['url']);

	$token = tokenWithStudentsPermissions();

	$newEmail = fake()->email();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->{$method}($url, [
		'email' => $newEmail,
		'first_name' => 'Estudiante Updated',
		'last_name' => 'Test Updated'
	]);

	$response->assertStatus($routes['update_student']['success_status']);

	$this->assertDatabaseHas('students', [
		'id' => $student->id,
		'email' => $newEmail,
		'first_name' => 'Estudiante Updated',
		'last_name' => 'Test Updated'
	]);
});

test('Update student with invalid data', function () use ($routes){
	$prevStudent = Student::factory()->create();
	$student = Student::factory()->create();

	$url = str_replace('{id}', $student->id, $routes['update_student']['url']);

	$token = tokenWithStudentsPermissions();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->put($url, [
		'email' => $prevStudent->email,
		'first_name' => '',
		'last_name' => ''
	]);

	$response->assertStatus(422);

	$response->assertJson([
		'errors' => [
			'email' => [
				'The email has already been taken.'
			],
			'first_name' => [
				'The first name field is required.'
			],
			'last_name' => [
				'The last name field is required.'
			]
		]
	]);
});

test('Find student', function () use ($routes){
	$student = Student::factory()->create();

	$url = str_replace('{id}', $student->id, $routes['find_student']['url']);

	$token = tokenWithStudentsPermissions();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->get($url);

	$response->assertStatus($routes['find_student']['success_status']);

	$response->assertJson([
		'student' => [
			'id' => $student->id,
			'email' => $student->email,
			'first_name' => $student->first_name,
			'last_name' => $student->last_name,
		]
	]);
});

test('Find student not found', function () use ($routes){
	$url = str_replace('{id}', 999999, $routes['find_student']['url']);

	$token = tokenWithStudentsPermissions();

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

test('Delete student', function () use ($routes){
	$student = Student::factory()->create();

	$url = str_replace('{id}', $student->id, $routes['delete_student']['url']);

	$token = tokenWithStudentsPermissions();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->delete($url);

	$response->assertStatus($routes['delete_student']['success_status']);

	$this->assertSoftDeleted('students', [
		'id' => $student->id,
	]);
});

test('Delete student not found', function () use ($routes){
	$url = str_replace('{id}', 999999, $routes['delete_student']['url']);

	$token = tokenWithStudentsPermissions();

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

test('Get students', function () use ($routes){
	$token = tokenWithStudentsPermissions();

	$url = $routes['get_students']['url'];

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->get($url);

	$response->assertStatus($routes['get_students']['success_status']);

	$expectedStudents = Student::all()->map(function ($student) {
		return [
			'id' => $student->id,
			'email' => $student->email,
			'first_name' => $student->first_name,
			'last_name' => $student->last_name,
		];
	})->toArray();

	$response->assertJson([
		'students' => $expectedStudents
	]);
});

test('Get students with courses', function () use ($routes){
	$studentWithCourse = Student::factory()->create();
	
	$course_1 = Course::factory()->create();
	$course_2 = Course::factory()->create();

	Enrollment::factory()->create([
		'course_id' => $course_1->id,
		'student_id' => $studentWithCourse->id
	]);

	Enrollment::factory()->create([	
		'course_id' => $course_2->id,
		'student_id' => $studentWithCourse->id
	]);

	$studentWithoutCourse = Student::factory()->create();

	$token = tokenWithStudentsPermissions();

	$url = $routes['get_students_with_courses']['url'];

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->get($url);

	$response->assertStatus($routes['get_students_with_courses']['success_status']);

	$responseJson = $response->json();

	$this->assertCount(1, $responseJson['student_with_courses']); // Solo hay un estudiante con cursos

	$responseStudentWithCourse = $responseJson['student_with_courses'][0];

	$this->assertEquals($studentWithCourse->id, $responseStudentWithCourse['id']); // Validamos que sea el estudiante con cursos
	$this->assertEquals(2, count($responseStudentWithCourse['courses'])); // Validamos que tenga 2 cursos

	$existStudentWithoutCourseInResponse = collect($responseJson['student_with_courses'])
		->contains(function ($student) use ($studentWithoutCourse) {
			return $student['id'] === $studentWithoutCourse->id;
		});

	$this->assertFalse($existStudentWithoutCourseInResponse);
});