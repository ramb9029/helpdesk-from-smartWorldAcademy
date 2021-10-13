<?php

namespace App\Http\Requests\Orders;

use App\Http\Controllers\OrderController;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class OrderUpdateRequest extends FormRequest
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
            'name'        => ['string','max:90',
                Rule::unique('orders', 'name')
                    ->where(function ($query){
                        return $query->where('statusExecution_id', '!=', OrderController::STATUS_EXECUTION_ARCHIVE);
                    })->ignore($this->id)
            ],
            'executor_user_id' => 'array',
            'executor_user_id.*' =>['integer', 'distinct:strict',
                Rule::exists('users', 'id')
                    ->where(function ($query){
                        return $query->where('role', '!=', User::ARCHIVED_ROLE_ID);
                    })],
            'description' => 'string|max:1000',
            'file'        => 'nullable|size:30720|image:jpg, jpeg, png|mimes:pdf, doc, docx, xlsx, ppt, pptx',
            'topic_id'    => 'array',
            'topic_id.*'  => 'integer|distinct:strict|exists:topics,id',
            'priority'    => 'string|max:100',
            'access'      => 'nullable|boolean',
            'estimatedDueDate' => 'date|after:now',
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
            'name.size'     => 'Тема заявки не должна превышать 90 символов',

            'description.required' => 'Поле description должно быть заполнено',
            'description.string'   => 'Поле description должно быть строкой',
            'description.max'     => 'Поле description не должно превышать 1000 символов',

            'file.size'  => 'Вы можете отправить файлы общим размером не более 30Mb',
            'file.mimes' => 'Вы можете отправить файлы в формате: pdf, doc, docx, xlsx, ppt, pptx',
            'file.image' => 'Вы можете отправить файлы в формате: png, jpg, jpeg',

            'topic_id.required' => 'Поле topic_id должно быть заполнено',
            'topic_id.array'    => 'Поле topic_id должно быть массивом',
            'topic_id.exists'   => 'Поле topic_id должно существовать в данной таблице базы данных',

            'topic_id.*.integer'  => 'Должно быть число или числа',
            'topic_id.*.distinct' => 'Значения должны быть уникальными',
            'topic_id.*.exists'   => 'Данной темы нет в базе данных',

            'priority.required' => 'Поле priority должно быть заполнено',
            'priority.string'   => 'Поле priority должно быть строкой',
            'priority.max'      => 'Поле priority должно быть однозначным',

            'access.boolean' => 'Поле access должно быть булевым значением',

            'executor_user_id.required' => 'Исполнитель заявки должен быть назначен',
            'executor_user_id.distinct' => 'Исполнители заявки должены быть уникальными',
            'executor_user_id.integer' => 'В Поле Исполнитель должно быть указано числом',
            'executor_user_id.exists' => 'Поле исполлнитель должно существовать в данной таблице',

            'executor_user_id.*.distinct' => 'Исполнители заявки должены быть уникальными',
            'executor_user_id.*.integer' => 'В Поле Исполнитель должно быть указано числом',
            'executor_user_id.*.exists' => 'Нельзя назначить задачу архивированному пользователю',

            'estimatedDueDate.data' => 'Поле должно содержать дату',
            'estimatedDueDate.after' => 'Дата должна быть указана не ранее чем завтра',

        ];
    }
}
