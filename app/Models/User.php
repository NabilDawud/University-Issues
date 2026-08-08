<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'user_name', 'city', 'phone_number', 'gender', 'is_active', 'profile_image', 'user_type_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
    public function student()
    {
        return $this->hasOne(Student::class);
    }
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    public function userType()
    {
        return $this->belongsTo(UserType::class)->withDefault();
    }
    public function isAdmin()
    {
        return $this->userType->name === 'Admin';
    }
    public function isStudent()
    {
        return $this->userType->name === 'Student';
    }
    public function isEmployee()
    {
        return $this->userType->name === 'Employee';
    }
}
