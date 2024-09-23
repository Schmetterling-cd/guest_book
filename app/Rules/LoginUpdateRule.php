<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class LoginUpdateRule implements ValidationRule
{

	protected $data = [];

	/**
	 * Set the data under validation.
	 *
	 * @param array<string, mixed> $data
	 */
	public function setData(array $data): static
	{
		$this->data = $data;

		return $this;
	}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

		$user = User::where('login', '=', $value)->first();

		if (!empty($user) && $user->id != $this->data['userId']) {
			$fail('Логин пользователя должен быть уникальным.');
		}

    }
}
