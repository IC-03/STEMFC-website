<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AssignmentController extends Controller
{
    // List all assignments uploaded by the teacher
    public function index()
    {
		$title = 'Assignments';
        $assignments = Assignment::where('teacher_id', Auth::id())->with('course')->get();
        return view('assignments.index', compact('assignments','title'));
    }

    // Show upload form
    public function create()
    {
				$title = 'Assignments';
        $courses = Course::all();
        return view('assignments.form', ['courses' => $courses, 'assignment' => null,'title'=>$title]);
    }

    // Store new assignment
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
            'course_id' => 'required|exists:courses,id',
        ]);

        //$path = $request->file('file')->store('assignments', 'public');
		
		
		
		
	
		if($request->hasFile('file')){
			$assignment_file = $request->file('file');
			$assignment_name = $assignment_file->getClientOriginalName();
			$assignment_file->move('public/assignments/', $assignment_name);
			
			
		}else{
			$assignment_name = '';
		}
		
		

        Assignment::create([
            'name' => $request->name,
            'file_path' => $assignment_name,
            'teacher_id' => Auth::id(),
            'course_id' => $request->course_id,
        ]);

        return redirect()->route('assignments.index')->with('success', 'Assignment uploaded successfully.');
    }

    // Show edit form
    public function edit(Assignment $assignment)
    {
		$title = 'Assignments';
        if ($assignment->teacher_id !== Auth::id()) abort(403);
        $courses = Course::all();
        return view('assignments.form', compact('assignment', 'courses','title'));
    }

    // Update assignment
    public function update(Request $request, Assignment $assignment)
    {
        if ($assignment->teacher_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
            'course_id' => 'required|exists:courses,id',
        ]);

        $data = [
            'name' => $request->name,
            'course_id' => $request->course_id,
        ];

        if ($request->hasFile('file')) {
            //Storage::disk('public')->delete($assignment->file_path);
			
			$filename = $assignment->file_path;
			$filePath = public_path('assignments/' . $filename);
			if (File::exists($filePath)) {
				File::delete($filePath);
			}
			
			$assignment_file = $request->file('file');
			$assignment_name = $assignment_file->getClientOriginalName();
			$assignment_file->move('public/assignments/', $assignment_name);
			
			
			
            $data['file_path'] = $assignment_name;
        }

        $assignment->update($data);

        return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully.');
    }

    // Delete assignment
    public function destroy(Assignment $assignment)
    {
        if ($assignment->teacher_id !== Auth::id()) abort(403);

        //Storage::disk('public')->delete($assignment->file_path);
		
		$filename = $assignment->file_path;
			$filePath = public_path('assignments/' . $filename);
			if (File::exists($filePath)) {
				File::delete($filePath);
			}
		
        $assignment->delete();

        return redirect()->route('assignments.index')->with('success', 'Assignment deleted successfully.');
    }

    // Student can view assignments by course
    public function studentAssignments()
    {
		$title = 'Your Assignments';
		
		 $user = Auth::user();
        $role = $user->role_id;
		   $courses   = $user->courses()->with('groups')->get();
            $courseIds = $courses->pluck('id')->all(); // array of IDs
		
        $assignments = Assignment::whereIn('course_id', $courseIds)->get();
        return view('assignments.student_list', compact('assignments','title'));
    }
}
