<?php

namespace App\Http\Requests\Quesstion;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property-read string $question
 */
class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }
}