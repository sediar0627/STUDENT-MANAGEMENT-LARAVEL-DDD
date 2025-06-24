<?php

use App\Models\Courses\Course;
use App\Models\Enrollments\Enrollment;
use App\Models\Students\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('Create enrollment without token', function (){
	Enrollment::factory()->create();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
	])
	->post('/api/v1/enrollments', [
		'course_id' => 10,
		'student_id' => 10
	]);

	$response->assertStatus(401);
});

test('Create enrollment with invalid data', function () {
	$token = tokenWithStudentsPermissions();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->post('/api/v1/enrollments', [
		'course_id' => 10,
		'student_id' => 10
	]);

	$response->assertStatus(422);

	$response->assertJson([
		'errors' => [
			'student_id' => [
				'The selected student id is invalid.'
			],
			'course_id' => [
				'The selected course id is invalid.'
			]
		]
	]);
});

test('Create enrollment', function () {
	$token = tokenWithStudentsPermissions();

	$student = Student::factory()->create();
	$course = Course::factory()->create();

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->post('/api/v1/enrollments', [
		'course_id' => $course->id,
		'student_id' => $student->id
	]);

	$response->assertStatus(201);

	$this->assertDatabaseHas('enrollments', [
		'course_id' => $course->id,
		'student_id' => $student->id
	]);
});

test('Create duplicate enrollment', function () {
	$token = tokenWithStudentsPermissions();

	$student = Student::factory()->create();
	$course = Course::factory()->create();

	Enrollment::factory()->create([
		'course_id' => $course->id,
		'student_id' => $student->id
	]);

	$response = $this->withHeaders([
		'Accept' => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	])
	->post('/api/v1/enrollments', [
		'course_id' => $course->id,
		'student_id' => $student->id
	]);

	$response->assertStatus(422);

	$response->assertJson([
		'errors' => [
			'student_id' => [
				'Student already enrolled in this course.'
			],
		]
	]);
});