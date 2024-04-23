<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Question;

class QuestionPolicy
{
    public function forceDelete(User $user, Question $question): bool
    {
        return $question->user->is($user);
    }
}
