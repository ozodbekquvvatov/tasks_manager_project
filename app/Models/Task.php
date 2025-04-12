<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';  // Task jadvali nomi
    protected $fillable = [
        'task_name', // Task nomi
        'priority',  // Prioritet
        'due_date',  // Tugash sanasi
        'status',    // Status (masalan, "In Progress", "Completed" va boshqalar)
        'description', // Tasvir (agar kerak bo'lsa)
        'image'
    ];
}
