<?php

namespace App\Http\Controllers\Question;

use App\Http\Controllers\Controller;
use App\Models\Question;

class PublishController extends Controller
{
    public function __invoke(Question $question)
    {
        $this->authorize('publish', $question);

        return $question->update(['status' => 'published'])
            ? response()->noContent()
            : response()->json(status: 500);
    }
}
