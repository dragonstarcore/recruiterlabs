<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Employee extends Model
{
    use HasFactory;
    

    /**
     * Get the user associated with the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employee_details()
    {
        return $this->hasOne(EmployeeDetail::class, 'employee_id');
    }

    
    /**
     * Get all of the comments for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employee_documents()
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id');
    }
}
