<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SumEquals implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Retrieve base_amount and gst_amount from the request
        $base = request()->input('base_amount');
        $gst = request()->input('gst_amount');

        // Check if both amounts are numeric
        if (is_numeric($base) && is_numeric($gst)) {
            $expectedTotal = $base + $gst;
            if ($value != $expectedTotal) {
                $fail('The total amount must be the sum of the base amount and GST amount.');
            }
        } else {
            $fail('The base amount and GST amount must be numeric.');
        }
    }
}
