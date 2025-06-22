<?php

namespace Src\Api\Students\Infrastructure\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Cache;
use App\Models\Students\Student as EloquentStudent;
use Src\Api\Students\Infrastructure\Repositories\EloquentStudentRepository;

class StudentEmailRule implements ValidationRule
{
	public function __construct(
        private string|null $id = null
    ){
		
	}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Usamos cache debido a que pueden pueden entrar varias peticiones al mismo tiempo
        // y no queremos errores de concurrencia
        $lock = Cache::lock('student_email_lock', 2);

		if ($lock->get()) {
			$query = EloquentStudent::where('email', $value);

			if ($this->id) {
				$query->where('id', '!=', $this->id);
			}
			
			if ($query->exists()) {
				$fail('The email has already been taken.');
			}
		 
			$lock->release();
		}
    }
}
