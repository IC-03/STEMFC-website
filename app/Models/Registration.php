<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    // If your table is named something other than the default "registrations", uncomment and adjust:
    // protected $table = 'registrations';

    // Allow mass assignment for these fields
    protected $fillable = [
        'uuid',
        'reg_date',
        'reg_value',
        'user_id',
        'group_id',
        'course_id',
        'is_delete',  // if you still track soft‑deletes this way
    ];

    // Cast reg_date to a Carbon instance automatically
    protected $casts = [
        'reg_date' => 'date',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * The user (student) who owns this registration.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The course for this registration.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
