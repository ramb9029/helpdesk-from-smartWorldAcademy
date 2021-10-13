<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class OrderGetAllRequestRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    /**
     * Проверка на авторизацию
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Правила валидации
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'filter' => 'array',
            'filter.1' => [ Rule::in('name', 'description','access', 'priority')],
            'filter.2' => [ Rule::in('!=', '=', '>','<')],
            'per_page' => 'numeric',
            'page' => 'numeric',
            'tab' => [ Rule::in('archive', 'my task', 'list task')]
        ];
    }

}
