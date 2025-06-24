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