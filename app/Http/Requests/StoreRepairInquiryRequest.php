<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRepairInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'repair_brand_id' => ['required', 'integer', 'exists:repair_brands,id'],
            'repair_model_id' => [
                'required', 'integer',
                Rule::exists('repair_models', 'id')->where('repair_brand_id', $this->input('repair_brand_id')),
            ],
            'repair_issue_id' => ['required', 'integer', 'exists:repair_issues,id'],

            'customer_name'      => ['required', 'string', 'max:120'],
            'customer_phone'     => ['required', 'string', 'max:32'],
            'customer_email'     => ['nullable', 'email', 'max:160'],
            'customer_whatsapp'  => ['nullable', 'string', 'max:32'],
            'preferred_contact'  => ['required', 'in:phone,whatsapp,email'],
            'message'            => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'repair_model_id.exists' => 'The selected model does not belong to the chosen brand.',
        ];
    }
}
