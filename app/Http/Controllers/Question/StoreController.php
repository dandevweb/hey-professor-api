<?php

namespace App\Http\Controllers\Question;

use App\Http\Resources\QuestionResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreRequest;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $question = user()->questions()
            ->create([
                'question' => $request->question,
                'status'   => 'draft',
            ]);

        return QuestionResource::make($question);
    }
}
