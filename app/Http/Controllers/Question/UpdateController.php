<?php

namespace App\Http\Controllers\Question;

use App\Models\Question;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Http\Requests\Question\UpdateRequest;

class UpdateController extends Controller
{
    public function __invoke(Question $question, UpdateRequest $request)
    {
        $question->update($request->validated());

        return QuestionResource::make($question);
    }
}
