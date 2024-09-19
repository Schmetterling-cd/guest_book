<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dateFrom' => ['required_if_accepted:dateTo', 'date_format:d.m.Y'],
            'dateTo' => ['required_if_accepted:dateFrom', 'date_format:d.m.Y'],
            'filters' => ['array:name,value'],
        ];
    }

}
