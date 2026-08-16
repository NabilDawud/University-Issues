<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'user_name', 'city', 'phone_number', 'gender', 'is_active', 'profile_image', 'user_type_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

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
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function isAdmin()
    {
        return $this->userType->id === 1;
    }
    public function isStudent()
    {
        return $this->userType->id === 2;
    }
    public function isEmployee()
    {
        return $this->userType->id === 3;
    }
    public function scopeAccessibleEmployees($query)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return $query->whereNull('id');
        }
        if ($user->isAdmin()) {
            return $query;
        }
        if ($user->hasRole('Dean')) {
            return $query->whereHas('employee.department', function ($q) use ($user) {
                $q->where('deanship_id', $user->employee?->department?->deanship_id);
            });
        }
        if ($user->hasRole('Department Head')) {
            return $query->whereHas('employee', function ($q) use ($user) {
                $q->where('department_id', $user->employee?->department_id);
            });
        }
        return $query->where('id', $user->id);
    }
    public function scopeAccessibleStudents($query)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return $query->whereNull('id');
        }
        if ($user->isAdmin()) {
            return $query;
        }
        if ($user->hasRole('Dean')) {
            return $query->whereHas('student.major.department', function ($q) use ($user) {
                $q->where('deanship_id', $user->employee?->department?->deanship_id);
            });
        }
        // return $query->where('id', $user->id);

        if ($user->hasRole('Department Head') || $user->hasRole('Instructor') || $user->hasRole('Assistant')) {
            return $query->whereHas('student.major', function ($q) use ($user) {
                $q->where('department_id', $user->employee?->department_id);
            });
        }

        return $query->where('id', $user->id);
    }
}
