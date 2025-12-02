<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /** @use HasFactory<\Database\Factories\EmployeeFactory> */
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'employee_no',
        'phone',
        'email',
        'designation',
        'department',
        'is_active'
    ];

    protected $casts = [
        'first_name'    => 'string',
        'last_name'     => 'string',
        'employee_no'   => 'string',
        'phone'         => 'string',
        'email'         => 'string',
        'designation'   => 'string',
        'department'     => 'string',
        'is_active'     => 'boolean',
    ];

    public function Attendance(){
        return $this->hasMany(Attendance::class);
    }
}
