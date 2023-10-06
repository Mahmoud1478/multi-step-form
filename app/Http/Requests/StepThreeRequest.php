<?php

namespace App\Http\Requests;

use App\Enums\User\RoleEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StepThreeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'teacher_id' => [
                Rule::when($this->get('role',1) == RoleEnum::STUDENT->value,['required'],['nullable']) ,
                'exists:users,id',
            ],
            'count' => [
                Rule::when($this->get('role',1) == RoleEnum::TEACHER->value ,['required'],['nullable']),
                'integer','min:1'
            ]
        ];
    }
}
