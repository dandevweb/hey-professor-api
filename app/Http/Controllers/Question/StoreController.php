<?php

namespace App\Http\Controllers\Question;

use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Http\Controllers\Controller;
use App\Http\Requests\Quesstion\StoreRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $question = Question::create([
            'question' => $request->question,
            'status'   => 'draft',
            'user_id'  => auth()->user()->id,
        ]);

        return QuestionResource::make($question);
    }
}
