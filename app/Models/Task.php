<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'id',
        'task_id',
        'lead_id',
        'note_id',
    ];
}