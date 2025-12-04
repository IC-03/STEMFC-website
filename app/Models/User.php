<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'id_no',
        'password',
        'guard_id',
        'role_id',
        'picture',
        'telephone',
        'gender',
        'notes',
        'uuid',
    ];

    /**
     * The attributes that should be appended to model arrays.
     */
    protected $appends = ['full_name'];

    /**
     * The attributes that should be hidden.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Teaching groups (if you use group_user pivot)
    public function teachingGroups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }

    // Groups assigned as professor
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id')->withTimestamps();
    }

    // Groups for which this user is the assigned professor (one-to-many)
    public function professorGroups()
    {
        return $this->hasMany(Group::class, 'professor_id');
    }

    // Courses taught via assigned groups
   /***  public function taughtCourses()
    {
        return $this->belongsToMany(Course::class, 'course_group', 'course_id', 'group_id')
            ->join('groups', 'groups.id', '=', 'course_group.group_id')
            ->where('groups.professor_id', $this->id)
            ->select('courses.*')
            ->distinct();
    }*/





    /**
     * Return a query builder for all Course models taught by this professor.
     *
     * A professor “teaches” a course IF there exists at least one Group
     * (in `groups`) whose `professor_id = $this->id`, and that Group is
     * linked to the Course via the `course_group` pivot table.
     *
     * This method returns a Builder for `Course`; you can chain
     * ->pluck('id') or ->get() on it.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function assignedCourses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_user',   // pivot table name
            'user_id',       // this model’s foreign key in pivot
            'course_id'      // related model’s foreign key in pivot
        )->withTimestamps();
    }

    // Course periods (advanced tracking)
    public function coursePeriods()
    {
        return $this->belongsToMany(Course::class, 'course_teacher_period', 'user_id', 'course_id')
                    ->withPivot(['group_id', 'period'])
                    ->withTimestamps();
    }

    // Course registrations (for students)
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'registrations', 'user_id', 'course_id')
                    ->withPivot(['uuid', 'reg_date', 'reg_value', 'group_id'])
                    ->withTimestamps();
    }
	
	
	public function coursesName()
{
    return $this->belongsToMany(
        Course::class,
        'registrations',
        'user_id',
        'course_id'
    );
}

    // In User.php
    public function taughtCourses()
    {
        return Course::whereHas('groups.teachers', function ($q) {
            $q->where('users.id', $this->id);
        });
    }


    // Attendance records
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // Payments made
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Role Checks
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role_id === 1;
    }

    public function isTeacher(): bool
    {
        return $this->role_id === 2;
    }

    public function isStudent(): bool
    {
        return $this->role_id === 3;
    }

    public function isParent(): bool
    {
        return $this->role_id === 4;
    }
}
