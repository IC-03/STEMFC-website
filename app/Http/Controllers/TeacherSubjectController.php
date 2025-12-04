<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class TeacherSubjectController extends Controller
{
    /**
     * Show all subjects (courses) that the current teacher actually teaches.
     */
    public function index()
    {
        $teacherId = Auth::id();

        // Step 1: get group_ids where this teacher is assigned
        $groupIds = DB::table('group_user')
            ->where('user_id', $teacherId)
            ->pluck('group_id');

        // Step 2: get course_ids linked to those groups
        $courseIdsFromGroups = DB::table('course_group')
            ->whereIn('group_id', $groupIds)
            ->pluck('course_id');

        // Step 3: get course_ids that are also assigned to the teacher
        $teacherCourseIds = DB::table('course_user')
            ->where('user_id', $teacherId)
            ->pluck('course_id');

        // Step 4: intersection of both to find actual taught courses
        $taughtCourseIds = $courseIdsFromGroups->intersect($teacherCourseIds);

        // Step 5: fetch courses + only relevant groups
        $subjects = Course::whereIn('id', $taughtCourseIds)
            ->with(['groups' => function ($query) use ($groupIds) {
                $query->whereIn('groups.id', $groupIds);
            }])
            ->orderBy('course_name')
            ->get();

        $title = 'Subject(s) You Teach';

        return view('teacher.subjects.list', compact('subjects', 'title'));
    }

}
