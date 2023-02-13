<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class PartnerIDRule implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (substr_count($value, '-') !== 3) {
            $fail('malformed.entry');
        }
    }
}
