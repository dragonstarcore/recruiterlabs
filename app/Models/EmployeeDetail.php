<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the EmployeeDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function direct_report_to()
    {
        return $this->belongsTo(Employee::class, 'id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
