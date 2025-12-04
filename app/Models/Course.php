<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Group;
use App\Models\User;


class Course extends Model
{
    protected $fillable = [
        'course_name', 'uuid',
    ];

    // Groups under this course
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'course_group', 'course_id', 'group_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(
            User::class,
            'course_user',   // pivot table name
            'course_id',     // this model’s foreign key in pivot
            'user_id'        // related model’s foreign key in pivot
        )->withTimestamps();
    }


    /**
     * The students registered in this course (via registrations pivot).
     */
    public function students()
    {
        return $this->belongsToMany(
                User::class,
                'registrations',
                'course_id',
                'user_id'
            )
            ->withPivot(['uuid','reg_date','reg_value', 'group_id'])
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Registration::class,
            'course_id',    // registration.course_id
            'registration_id', // payment.registration_id
            'id',           // this.id
            'id'            // registration.id
        );
    }

    public function teacherPeriods()
    {
        return $this->belongsToMany(
            User::class,
            'course_teacher_period',    // pivot table
            'course_id',                // this model’s FK
            'user_id'                   // related model’s FK
        )
        ->withPivot(['group_id','period'])
        ->withTimestamps();
    }
}
