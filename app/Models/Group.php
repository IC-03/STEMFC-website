<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected $fillable = [
        'uuid',
        'name',
        'ability',
        'start_date',
        'end_date',
        'status',
        'created_by',
    ];

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }


    public function teachers()
    {
        return $this->belongsToMany(
            User::class,
            'group_user',
            'group_id',
            'user_id'
        )
        ->where('role_id', 2)
        ->withTimestamps();
    }


   // Course-group pivot
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_group',   // pivot table name
            'group_id',       // pivot FK that points to *this* group
            'course_id'       // pivot FK that points to Course
        )->withTimestamps();
    }


    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'group_user',   // pivot table name
            'group_id',
            'user_id'
        );
    }


    public function students()
    {
        return User::whereHas('courses', function ($query) {
            $query->whereIn('courses.id', $this->courses()->pluck('id'));
        });
    }
}
