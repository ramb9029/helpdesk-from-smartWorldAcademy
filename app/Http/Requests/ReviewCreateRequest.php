<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class ReviewCreateRequest extends FormRequest
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
            'description'    => 'sometimes|required|string|max:1000',
            'valueClient'    => 'sometimes|required|integer|numeric|max:2',
            'valueOther'     => 'sometimes|required|integer|numeric|max:2',
            'order_id'       => 'required|integer|numeric|exists:orders,id',
            'criticUser' => 'required|integer|numeric|exists:users,id',
        ];
    }

    /**
     * Сообщения об ошибках
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'description.required' => 'Поле description должно быть заполнено',
            'description.string'   => 'Поле description должно быть строкой',
            'description.max'      => 'Поле description не должно превышать 1000 символов',

            'valueClient.required'  => 'Поле valueClient должно быть заполнено',
            'valueClient.integer'   => 'Поле valueClient должно быть целым числом',
            'valueClient.numeric'   => 'Поле valueClient должно быть числовым значением',
            'valueClient.max'       => 'Поле valueClient не должно превышать 2',

            'valueOther.required'  => 'Поле valueOther должно быть заполнено',
            'valueOther.integer'   => 'Поле valueOther должно быть целым числом',
            'valueOther.numeric'   => 'Поле valueOther должно быть числовым значением',
            'valueOther.max'       => 'Поле valueOther не должно превышать 2',

            'order_id.required' => 'Поле order_id должно быть заполнено',
            'order_id.integer'  => 'Поле order_id должно быть целым числом',
            'order_id.numeric'  => 'Поле order_id должно быть числовым значением',
            'order_id.exists'   => 'Данная заявка не найдена. Действие недоступно',

            'criticUser.required' => 'Поле criticUser должно быть заполнено',
            'criticUser.integer'  => 'Поле criticUser должно быть целым числом',
            'criticUser.numeric'  => 'Поле criticUser должно быть числовым значением',
            'criticUser.exists'   => 'Данного юзера нет в базе данных',
        ];
    }
}
