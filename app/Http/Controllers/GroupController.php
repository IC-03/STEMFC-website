<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with('teachers')
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->paginate(10);

        $title = 'All Classes';
        return view('admin.class.list', compact('title', 'groups'));
    }

    public function create()
    {
        $title = "New Class";
        return view('admin.class.new', compact('title'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|max:255|unique:groups,name',
            'ability'       => 'required|integer|min:0',
            'status'        => 'required|in:0,1',
            'startdate'     => 'required|date',
            'enddate'       => 'required|date|after_or_equal:startdate',
        ],[
            'name.required'        => 'Class Name cannot be left blank',
            'name.unique'          => 'Already exists in system, please try another',
            'ability.required'     => 'Ability cannot be left blank',
            'status.required'      => 'Status cannot be left blank',
            'startdate.required'   => 'Start Date cannot be left blank',
            'enddate.required'     => 'End Date cannot be left blank',
        ]);

        $grp = Group::create([
            'uuid'        => Str::uuid(),
            'name'        => trim(ucwords($data['name'])),
            'ability'     => $data['ability'],
            'start_date'  => $data['startdate'],
            'end_date'    => $data['enddate'],
            'status'      => $data['status'],
            'created_by'  => Auth::id(),
            'is_delete'   => 0,
        ]);

        // No syncing teachers here!

        return redirect()
            ->route('admin.class.list')
            ->with('success', "{$grp->name} added successfully");
    }


    public function edit($uuid)
    {
        $class = Group::with('teachers')
                      ->where('uuid', $uuid)
                      ->where('is_delete', 0)
                      ->firstOrFail();

        $teachers = User::where('role_id', 2)
                        ->where('is_delete', 0)
                        ->orderBy('first_name')
                        ->get();

        $title = "Edit Class";
        return view('admin.class.edit', compact('class','teachers','title'));
    }

    public function update(Request $request, $uuid)
    {
        $class = Group::where('uuid', $uuid)
                      ->where('is_delete', 0)
                      ->firstOrFail();

        $data = $request->validate([
            'name'          => 'required|max:255|unique:groups,name,' . $class->id,
            'teacher_ids'   => 'required|array|min:1',
            'teacher_ids.*' => 'exists:users,id',
            'ability'       => 'required|integer|min:0',
            'status'        => 'required|in:0,1',
            'startdate'     => 'required|date',
            'enddate'       => 'required|date|after_or_equal:startdate',
        ],[
            'name.required'        => 'Class Name cannot be left blank',
            'name.unique'          => 'Already exists in system, please try another',
            'teacher_ids.required' => 'At least one teacher must be assigned',
            'teacher_ids.*.exists' => 'Invalid teacher selected',
            'ability.required'     => 'Ability cannot be left blank',
            'status.required'      => 'Status cannot be left blank',
            'startdate.required'   => 'Start Date cannot be left blank',
            'enddate.required'     => 'End Date cannot be left blank',
        ]);

        $class->update([
            'name'       => trim(ucwords($data['name'])),
            'ability'    => $data['ability'],
            'start_date' => $data['startdate'],
            'end_date'   => $data['enddate'],
            'status'     => $data['status'],
        ]);

        // Sync all selected teachers
        $class->teachers()->sync($data['teacher_ids']);

        return redirect()
            ->route('admin.class.list')
            ->with('success', "{$class->name} updated successfully!");
    }

    public function destroy($uuid)
    {
        $group = Group::where('uuid', $uuid)->firstOrFail();
        $group->teachers()->detach();
        $group->delete();

        return redirect()
            ->route('admin.class.list')
            ->with('success','Class deleted successfully!');
    }

    public function show($uuid)
    {
        $group = Group::with('teachers')
                      ->where('uuid', $uuid)
                      ->where('is_delete', 0)
                      ->firstOrFail();
        $title = 'Class Profile';
        return view('admin.class.profile', compact('group','title'));
    }
}

