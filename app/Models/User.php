<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use SoftDeletes;
    // protected $softDelete = true;

    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'users';

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $with = ['user_details'];
    protected $fillable = [
        'name',
        'email',
        'password',
        'deleted_at',
        'xero_oauth',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'xero_oauth' => 'array'
    ];

    /**
     * Get the user associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user_details()
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    /**
     * Get all of the comments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user_documents()
    {
        return $this->hasMany(UserDocument::class, 'user_id');
    }

    public function user_people()
    {
        return $this->hasMany(Employee::class, 'user_id');
    }
    public function user_jobs()
    {
        return $this->hasMany(Job::class, 'user_id');
    }
    public function user_hr_documents()
    {
        return $this->hasMany(HRDocument::class, 'user_id');
    }

    public function xero_details()
    {
        return $this->hasOne(XeroDetail::class, 'user_id');
    }

    public function jobadder_details()
    {
        return $this->hasOne(JobadderDetail::class, 'user_id');
    }
}
