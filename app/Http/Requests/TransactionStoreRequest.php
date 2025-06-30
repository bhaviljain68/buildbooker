<?php

namespace App\Http\Requests;

use App\Rules\SumEquals;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (auth()) {
            return true;
        }

        return false;
    }

    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            // 'new_customer' => 'required|boolean',
            
            'payment_date' => 'required|date_format:Y-m-d',
            'receipt_date' => 'required|date_format:Y-m-d',
            'transaction_amount' => 'required|numeric|gt:1',
            'gst' => 'required|boolean',
            'payment_type' => 'required|string|in:bank_draft,rtgs,neft,cheque,cash',
            'payment_reference' => 'required_unless:payment_type,cash|nullable|string',
            'bank_name' => 'required|string',
            'bank_branch' => 'required|string',
        ];
 
        return $rules;
    }
}
