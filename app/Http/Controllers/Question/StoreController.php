<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Http\Requests\Quesstion\StoreRequest;
use App\Models\Question;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        Question::create([
            'question' => $request->question,
            'user_id'  => auth()->user()->id,
        ]);
    }
}
