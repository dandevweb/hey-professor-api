<?php

namespace App\Http\Controllers\Question;

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

        return response([
            'data' => [
                'id'     => $question->id,
                'question' => $question->question,
                'status' => $question->status,
                'user_id' => $question->user_id,
                'created_at' => $question->created_at->format('Y-m-d'),
                'updated_at' => $question->updated_at->format('Y-m-d'),
            ]
        ], Response::HTTP_CREATED);
    }
}
