<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'userId' => $this->id,
            'userName' => $this->name,
            'email' => $this->email,
            'role' => $this->pivot->role,
            'contribution_hours' => $this->pivot->role,
            'last_activity' => $this->pivot->role
        ];
    }
}
