<?php

namespace App\Models\Students;

use App\Models\Courses\Course;
use App\Models\Enrollments\Enrollment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'students';

    protected $fillable = [
        'email',
        'first_name',
        'last_name'
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
            ->withPivot('enrolled_at')
            ->withTimestamps();
    }

    public function scopeWithCourses(Builder $query): void
    {
        $query->has('courses');
    }
}
