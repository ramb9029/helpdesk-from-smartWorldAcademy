<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'required|string|max:255',
            'role' => 'required|numeric',
            'email' => 'required|string|email|unique:users|min:5|max:50|',
            'password' => 'regex:/^[a-zA-Z0-9\S]+$/i|
                            required|string|min:6|max:30|confirmed',
            'department_id' => 'required|numeric',
            'position_id' => 'required|numeric',
            'room_id' => 'required|numeric',
        ];
        if($this->isMethod('patch')){
            foreach ($rules as &$rule){
                $rule = str_replace('required|', '', $rule);
            }
            unset($rule);
            $rules['email'] = 'string|email|'.Rule::unique('users')->ignore($this->route()->id, 'id');
            if (!$this->password_confirmation){
                $rules['password'] = 'string|min:6';
            }
        }
        return $rules;
    }

    public function messages()
    {
        return  [
            'firstName.required' => 'Имя должжно быть заполнено',
            'firstName.string' => 'Имя должжно быть стройкой',

            'lastName.required' => 'Фамилия должжна быть заполнено',
            'lastName.string' => 'Фамилия должжна быть стройкой',

            'middleName.required' => 'Отчество должжно быть заполнено',
            'middleName.string' => 'Отчество должжно быть стройкой',

            'role.required' => 'Роль должна быть указана',
            'role.numeric' => 'Роль должжна быть числом',

            'email.required' => 'Почта обязательна для заполнения',
            'email.email' => 'Почта должна быть правильно указана',
            'email.unique' => 'Данная почта уже занята',

            'password.required' =>'Поле пароль обязательно для заполнения',
            'password.regex' => 'Пароль должен состоять только из латинских букв, цифр, символов и не содержать пробелов',
            'password.min' => 'Пароль должен быть минимум 6 символов длиной',
            'password.confirmed' => 'Пароли не совпадают',

            'department_id' => 'Отдел должен быть указан',
            'position_id' => 'Должность должна быть указана',
            'room_id' => 'Номер команты должен быть указан',
        ];
    }

}
