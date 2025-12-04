<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\User;
use App\Models\Course;
use App\Models\Group;           // ← import Group
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    /**
     * Display a listing of registrations.
     */
    public function index()
    {
        $title = "All Registrations";
        $regs  = Registration::with(['user', 'course', 'group'])
                            ->where('is_delete', 0)
                            ->orderBy('id', 'desc')
                            ->get();

        return view('admin.registration.list', compact('title', 'regs'));
    }

    /**
     * Show the form for creating a new registration.
     */
    public function create()
    {
        $title   = "Create New Registration";
        $users   = User::where('role_id', 3)
                       ->where('is_delete', 0)
                       ->orderBy('first_name')
                       ->get();
        $courses = Course::where('is_delete', 0)
                         ->orderBy('course_name')
                         ->get();
        $groups  = Group::where('is_delete', 0)
                        ->orderBy('name')
                        ->get();

        return view('admin.registration.new', compact(
            'title', 'users', 'courses', 'groups'
        ));
    }

    /**
     * Store a newly created registration in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'regdate'    => 'required|date',
            'regvalue'   => 'nullable|numeric|min:1',
            'stdname'    => 'required|exists:users,id',
            'coursename' => [
                'required',
                'exists:courses,id',
                function ($attribute, $value, $fail) use ($request) {
                    if (Registration::where('user_id', $request->stdname)
                                    ->where('course_id', $value)
                                    ->exists()) {
                        $fail('The student is already enrolled in this course.');
                    }
                },
            ],
            'group_id'   => 'required|exists:groups,id',
        ], [
            'regdate.required'    => 'Registration date is required.',
            'stdname.required'    => 'Student must be selected.',
            'coursename.required' => 'Course must be selected.',
            'group_id.required'   => 'Class must be selected.',
        ]);

        Registration::create([
            'uuid'      => Str::uuid(),
            'reg_date'  => $request->regdate,
            'reg_value' => $request->regvalue,
            'user_id'   => $request->stdname,
            'course_id' => $request->coursename,
            'group_id'  => $request->group_id,
        ]);

        return redirect()->route('admin.registration.list')
                         ->with('success', 'Registration added successfully.');
    }

    public function edit($uuid)
    {
        $registration = Registration::where('uuid', $uuid)->firstOrFail();
        $students = User::where('role_id', 3)->get(); // Get only students
        $groups = Group::all();
        $courses = Course::all();
        $title = "Edit Registration";

        return view('admin.registration.edit', compact('registration', 'students', 'groups', 'courses', 'title'));
    }

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'group_id' => 'required|exists:groups,id',
            'course_id' => 'required|exists:courses,id',
            'reg_date' => 'required|date',
            'reg_value' => 'nullable|numeric|min:0',
        ]);

        $registration = Registration::where('uuid', $uuid)->firstOrFail();
        $registration->update($request->only(['user_id', 'group_id', 'course_id', 'reg_date', 'reg_value']));

        return redirect()->route('admin.registration.list')->with('success', 'Registration updated successfully!');
    }


    /**
     * Permanently remove the specified registration.
     */
    public function destroy($uuid)
    {
        $reg = Registration::where('uuid', $uuid)->firstOrFail();
        $reg->delete();

        return redirect()->route('admin.registration.list')
                         ->with('error', 'Registration deleted successfully.');
    }
}
