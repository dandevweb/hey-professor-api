<?php

namespace App\Http\Resources;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Question $this */
        return [
            'id'       => $this->id,
            'question' => $this->question,
            'status'   => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'created_by' => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
        ];
    }
}
