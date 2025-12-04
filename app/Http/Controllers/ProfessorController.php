<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Group;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfessorController extends Controller
{
    /**
     * Display a listing of professors.
     */
    public function index()
    {
         $users = User::where('role_id', 2)
            ->where('is_delete', 0)
            ->withCount('assignedCourses')      // show how many courses each professor has
            ->with([
                'assignedCourses',              // load the Course models from course_user pivot
                'teachingGroups.courses'        // for each group the prof teaches, load its courses
            ])
            ->orderByDesc('id')
            ->get();

        return view('admin.professor.list', [
            'title' => 'All Professors',
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new professor.
     */
    public function create()
    {
        return view('admin.professor.new', [
            'title'   => 'New Professor',
            'courses' => Course::with('groups')->orderBy('course_name')->get(),
            'groups'  => Group::where('is_delete', 0)->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created professor in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:3|max:50',
            'last_name'  => 'required|string|min:3|max:50',
            'telephone'  => 'nullable|string|max:20',
            'id_no'      => 'required|string|unique:users,id_no',
            'gender'     => 'required|in:male,female,other',
            'password'   => 'required|confirmed|min:8',
            'courses'    => 'nullable|array',
            'courses.*'  => 'exists:courses,id',
            'groups'     => 'required|array|min:1',
            'groups.*'   => 'exists:groups,id',
            'image'      => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        // 1) Store uploaded image, if any:
        $path = $request->file('image')?->store('images', 'public');

        // 2) Create the new User (professor):
        $user = User::create([
            'uuid'       => Str::uuid(),
            'first_name' => ucwords($request->first_name),
            'last_name'  => ucwords($request->last_name),
            'telephone'  => $request->telephone,
            'id_no'      => $request->id_no,
            'gender'     => $request->gender,
            'password'   => Hash::make($request->password),
            'role_id'    => 2,          // “2” = professor
            'picture'    => $path,
        ]);

        // 3) Attach this professor to as many groups as were selected.
        //    This writes to the `group_user (user_id, group_id)` pivot.
        $user->teachingGroups()->sync($request->groups);

        // 4) Attach this professor to as many courses as were selected.
        //    This writes to the `course_user (user_id, course_id)` pivot.
        $user->assignedCourses()->sync($request->courses ?? []);

        return redirect()
            ->route('admin.professor.list')
            ->with('success', "{$user->full_name} added successfully.");
    }

    /**
     * Show the form for editing the specified professor.
     */
    public function edit($uuid)
    {
        $professor = User::where('uuid', $uuid)
                        ->where('role_id', 2)
                        ->firstOrFail();

        return view('admin.professor.edit', [
            'title'      => 'Edit Professor',
            'professor'  => $professor,
            'allCourses' => Course::with('groups')->orderBy('course_name')->get(),
            'allGroups'  => Group::where('is_delete', 0)->orderBy('name')->get(),
            'teaches'    => $professor->assignedCourses()->pluck('courses.id')->toArray(),
            'classes'    => $professor->professorGroups()->pluck('id')->toArray(),
        ]);
    }


    /**
     * Update the specified professor in storage.
     */
    public function update(Request $request, $uuid)
    {
        $professor = User::where('uuid', $uuid)
                         ->where('role_id', 2)
                         ->firstOrFail();

        $request->validate([
            'first_name' => 'required|string|min:3|max:50',
            'last_name'  => 'required|string|min:3|max:50',
            'telephone'  => 'nullable|string|max:20',
            'id_no'      => 'required|string|unique:users,id_no,' . $professor->id,
            'gender'     => 'required|in:male,female,other',
            'password'   => 'nullable|confirmed|min:8',
            'courses'    => 'nullable|array',
            'courses.*'  => 'exists:courses,id',
            'groups'     => 'required|array|min:1',
            'groups.*'   => 'exists:groups,id',
            'image'      => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $professor->picture = $request->file('image')->store('images', 'public');
        }

        $professor->update([
            'first_name' => ucwords($request->first_name),
            'last_name'  => ucwords($request->last_name),
            'telephone'  => $request->telephone,
            'id_no'      => $request->id_no,
            'gender'     => $request->gender,
        ]);

        if ($request->filled('password')) {
            $professor->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Re-assign groups
        Group::where('professor_id', $professor->id)
             ->update(['professor_id' => null]);

        Group::whereIn('id', $request->groups)
             ->update(['professor_id' => $professor->id]);


        $professor->teachingGroups()->sync($request->groups);

        // Re-sync courses pivot
        $professor->assignedCourses()->sync($request->courses ?? []);

        return redirect()
            ->route('admin.professor.list')
            ->with('success', "{$professor->full_name} updated.");
    }

    /**
     * Remove the specified professor from storage.
     */
    public function destroy($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        Group::where('professor_id', $user->id)
             ->update(['professor_id' => null]);

        $user->delete();

        return redirect()
            ->route('admin.professor.list')
            ->with('error', "{$user->full_name} deleted.");
    }
}
