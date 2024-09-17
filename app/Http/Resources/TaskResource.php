<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];
        if (Auth::user()->id == $this->manager_id) {
            $employee = User::find($this->employee_id);
            if ($employee != null) {
                $data['employeeName'] = $employee->name;
            }
        }
        return [
            'taskId' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'due_date' => $this->due_date,
            'status' => $this->status,
            ...$data,
        ];
    }
}