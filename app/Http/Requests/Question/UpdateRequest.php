<?php

namespace App\Http\Requests\Question;

use App\Rules\WithQuestionMark;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $question = $this->route('question');
        return $question->user->is(user()) && $question->status === 'draft'; //@phpstan-ignore-line,
    }

    public function rules(): array
    {

        return [
            'question' => [
                'required',
                new WithQuestionMark(),
                'min:10',
                Rule::unique('questions')->ignore($this->route('question')->id) //@phpstan-ignore-line,
            ],
        ];
    }
}
