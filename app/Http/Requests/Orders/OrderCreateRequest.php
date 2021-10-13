<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class OrderCreateRequest extends FormRequest
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
            'name'        => ['required','string','max:90',
                Rule::unique('orders', 'name')
                    ->where(function ($query){
                        return $query->whereNotIn('statusExecution_id', [1, 4])->where('client_user_id', '=', $this->user()->id);
                    })
            ],
            'description' => 'required|string|max:1000',
            'file'        => 'nullable|max:15360|image: jpg, jpeg, png or mimes:pdf, doc, docx, png, jpg, jpeg, xlsx, ppt, pptx',
            'topics'    => 'required|array',
            'topics.*'  => 'integer|distinct:strict|exists:topics,id',
            'priority'    => 'required|string',
            'access'      => 'required|nullable|boolean',
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
            'name.required' => 'Поле name должно быть заполнено',
            'name.string'   => 'Поле name должно быть строкой',
            'name.max'      => 'Тема заявки не должна превышать 90 символов',
            'name.unique'   => 'Это название уже используется',

            'description.required' => 'Поле description должно быть заполнено',
            'description.string'   => 'Поле description должно быть строкой',
            'description.max'     => 'Поле description не должно превышать 1000 символов',

            'file.size'  => 'Вы можете отправить файлы общим размером не более 15Mb',
            'file.mimes' => 'Вы можете отправить только файлы в формате: pdf, doc, docx, png, jpg, jpeg, xlsx, ppt, pptx',

            'topics.required' => 'Поле topics должно быть заполнено',
            'topics.array'    => 'Поле topics должно быть массивом',
            'topics.exists'   => 'Поле topics должно существовать в данной таблице базы данных',

            'topic_id.*.numeric'  => 'Должно быть число или числа',
            'topic_id.*.distinct' => 'Значения должны быть уникальными',
            'topic_id.*.exists'   => 'Данной темы нет в базе данных',

            'priority.required' => 'Поле priority должно быть заполнено',
            'priority.string'   => 'Поле priority должно быть строкой',

            'access.bool' => 'Поле access должно быть булевым значением',
            'access.required' => 'Поле access обязательно для заполнения',
        ];
    }
}
