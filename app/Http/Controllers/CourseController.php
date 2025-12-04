<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Group;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $title = 'All Courses';
        $courses = Course::with('groups')
                        ->where('is_delete', 0)
                        ->orderBy('id', 'desc')
                        ->get();

        return view('admin.course.list', compact('title', 'courses'));
    }



    public function create()
    {
        $groups = Group::where('is_delete', '=', 0)->orderBy('id', 'desc')->get();
        return view('admin.course.add', [
            'title' => 'Add New Course',
            'groups' => $groups
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'bail|required|max:255|unique:courses',
            'groupname'   => 'required|array|min:1',
            'groupname.*' => 'exists:groups,id',
        ], [
            'course_name.required' => 'Course Name cannot be left blank',
            'course_name.unique'   => 'Already exists in system, please try another',
            'groupname.required'   => 'At least one group must be selected',
        ]);

        $course = Course::create([
            'course_name' => trim(ucwords($request->course_name)),
            'uuid'        => Str::uuid(),
        ]);

        $course->groups()->sync($request->groupname);

        return redirect('./admin/course/list')->with('success', $course->course_name . ' added successfully');
    }

    public function destroy($uuid)
    {
        $course = Course::where('uuid', $uuid)->firstOrFail();
        $name = $course->course_name;
        $course->groups()->detach();
        $course->delete();

        return redirect('./admin/course/list')->with('success', $name . ' deleted successfully');
    }

    public function edit($uuid)
    {
        $course = Course::where('uuid', $uuid)->firstOrFail();
        $groups = Group::where('is_delete', 0)->orderBy('name')->get();
        return view('admin.course.edit', [
            'course' => $course,
            'groups' => $groups,
            'title' => 'Edit Record'
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $course = Course::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'course_name'  => 'required|string|max:255|unique:courses,course_name,' . $course->id,
            'groupname'    => 'required|array|min:1',
            'groupname.*'  => 'exists:groups,id',
        ]);

        $course->update([
            'course_name' => trim($request->course_name),
        ]);

        $course->groups()->sync($request->groupname);

        return redirect()->route('admin.course.list')->with('success', 'Course updated successfully.');
    }
}
