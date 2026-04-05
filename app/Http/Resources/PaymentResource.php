<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'full_name' => $this->student->full_name ?? 'Unknown Student',
            'amount' => $this->amount,
            'created_at' => $this->created_at->toIso8601String(),
            // Eager loaded relationship
            'collected_by_name' => $this->collector->name ?? 'System',
        ];
    }
}
