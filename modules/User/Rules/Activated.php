<?php

namespace Modules\User\Rules;

use Illuminate\Contracts\Validation\Rule;

class Activated implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return \DB::table('users')->where($attribute, $value)
            ->where('activated', '=', 1)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('user::validation.not_activated');
    }
}
