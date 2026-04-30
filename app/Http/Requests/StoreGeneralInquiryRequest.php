<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGeneralInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name'      => ['required', 'string', 'max:120'],
            'customer_phone'     => ['required', 'string', 'max:32'],
            'customer_email'     => ['nullable', 'email', 'max:160'],
            'customer_whatsapp'  => ['nullable', 'string', 'max:32'],
            'preferred_contact'  => ['required', 'in:phone,whatsapp,email'],
            'message'            => ['required', 'string', 'max:2000'],
            'subject'            => ['nullable', 'string', 'max:160'],
        ];
    }
}
