<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $casts = [
        'date' => 'date',
    ];

    protected $table = 'attendances'; // Explicitly set table name
    protected $fillable = ['group_id', 'date', 'user_id', 'user_uuid', 'attendance_status', 'period','course_id','teacher_id','attendance_comments',];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

}
