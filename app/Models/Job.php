<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $table = 'jobs';
    
    protected $fillable = [
        'job_title',
        'industry', // Add this field
        'location',
        'salary',
        'start_date',
        'margin_agreed',
        'fee',
        'job_description',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 