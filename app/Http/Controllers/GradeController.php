<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    /**
     * Show the “Select Course” page.
     * Only list the courses this teacher is assigned to (via the course_user pivot).
     */
    public function selectCourse()
    {
        $teacherId = Auth::id();

        // (a) Pull all course_ids from the pivot "course_user" for this teacher
        $courseIds = DB::table('course_user')
            ->where('user_id', $teacherId)
            ->pluck('course_id');

        // (b) Fetch those Course models
        $courses = Course::whereIn('id', $courseIds)
                    ->orderBy('course_name')
                    ->get();

        return view('teacher.grades.select-course', compact('courses'));
    }

    /**
     * Show the “Select Student” page for a given course.
     * The teacher must be assigned to this course in course_user.
     *
     * @param  int  $courseId
     */
    public function selectStudent($courseId)
    {
        $teacherId = Auth::id();
        $course    = Course::findOrFail($courseId);

        // (a) Check pivot "course_user" to ensure the teacher does indeed teach this course:
        $teachesThatCourse = DB::table('course_user')
            ->where('user_id', $teacherId)
            ->where('course_id', $course->id)
            ->exists();

        if (! $teachesThatCourse) {
            abort(403, 'Unauthorized to view students for that course.');
        }

        // (b) Fetch all students registered in this course (via "registrations" pivot):
        $students = $course->students()
                           ->orderBy('first_name')
                           ->orderBy('last_name')
                           ->get();

        return view('teacher.grades.select-student', [
            'course'   => $course,
            'students' => $students,
        ]);
    }

    /**
     * Show the “Create Grade” form for a given student & course.
     * We rely on route-model binding for both $student and $course.
     * We double-check that $teacher really teaches $course, and that $student is registered.
     *
     * @param  \App\Models\User   $student   (must have role_id = 3)
     * @param  \App\Models\Course $course
     */
    public function create(User $student, Course $course)
    {
        $teacherId = Auth::id();

        // (a) Check that the teacher teaches $course via pivot
        $teaches = DB::table('course_user')
            ->where('user_id', $teacherId)
            ->where('course_id', $course->id)
            ->exists();

        if (! $teaches) {
            abort(403, 'You are not authorized to grade this course.');
        }

        // (b) Check that $student is actually registered in this course
        $registered = DB::table('registrations')
            ->where('course_id', $course->id)
            ->where('user_id', $student->id)
            ->exists();

        if (! $registered) {
            abort(404, 'That student is not registered in this course.');
        }

        // If both checks pass, render the “create grade” form:
        return view('teacher.grades.create', [
            'student' => $student,
            'course'  => $course,
        ]);
    }

    /**
     * Store a newly created grade in storage (single-student workflow).
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'      => ['required','integer','exists:users,id'],
            'course_id'       => ['required','integer','exists:courses,id'],
            'assessment_type' => ['required','in:test,classwork,homework'],
            'percentage'      => ['required','integer','min:0','max:100'],
            'date'            => ['required','date'],
        ]);

        $teacherId = Auth::id();
        $student   = User::findOrFail($data['student_id']);
        $course    = Course::findOrFail($data['course_id']);

        // (a) Confirm teacher teaches this course:
        $teaches = DB::table('course_user')
            ->where('user_id', $teacherId)
            ->where('course_id', $course->id)
            ->exists();

        if (! $teaches) {
            abort(403, 'Unauthorized to record grade for this course.');
        }

        // (b) Confirm student is registered in the course:
        $registered = DB::table('registrations')
            ->where('course_id', $course->id)
            ->where('user_id', $student->id)
            ->exists();

        if (! $registered) {
            abort(404, 'That student is not registered in this course.');
        }

        // (c) Check if a grade for this student/course/assessment_type/date already exists
        $existing = Grade::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('assessment_type', $data['assessment_type'])
            ->where('date', $data['date'])
            ->first();

        if ($existing) {
            $gradedBy = User::find($existing->teacher_id)->full_name;
            return redirect()->back()
                ->with('error', "{$student->full_name} was already graded by {$gradedBy} on {$existing->date}.");
        }

        // Convert percentage (0–100) into a 1.0–5.0 scale, rounded to nearest 0.5
        $pct = (int) $data['percentage']; // e.g. 0..100
        $rawGrade = 1 + ($pct / 100.0) * 4;       // Linear: 0%→1.0, 100%→5.0
        $decimalGrade = round($rawGrade * 2) / 2; // Round to nearest 0.5
        $decimalGrade = max(1.0, min(5.0, $decimalGrade)); // Clamp between 1.0 and 5.0

        Grade::create([
            'student_id'      => $student->id,
            'course_id'       => $course->id,
            'teacher_id'      => $teacherId,
            'assessment_type' => $data['assessment_type'],
            'percentage'      => $pct,
            'grade'           => $decimalGrade,       // save decimal
            'date'            => $data['date'],
        ]);


        return redirect()
            ->route('grades.create', [
                'student' => $student->id,
                'course'  => $course->id
            ])
            ->with('success', 'Grade recorded successfully.');
    }

    /**
     * Show the bulk‐grading form for a given course.
     * Displays all students in that course with inline inputs.
     *
     * @param  \App\Models\Course  $course
     */
    public function bulkForm(Course $course)
    {
        $teacherId = Auth::id();

        // 1) Confirm the teacher is assigned to the course at all (via course_user).
        $teaches = DB::table('course_user')
            ->where('user_id', $teacherId)
            ->where('course_id', $course->id)
            ->exists();

        if (! $teaches) {
            abort(403, 'Unauthorized to view this course’s roster.');
        }

        // 2) Get all group IDs that this teacher belongs to (group_user pivot).
        $teacherGroupIds = DB::table('group_user')
            ->where('user_id', $teacherId)
            ->pluck('group_id')
            ->toArray();

        // 3) Get all group IDs linked to this course (course_group pivot).
        $courseGroupIds = DB::table('course_group')
            ->where('course_id', $course->id)
            ->pluck('group_id')
            ->toArray();

        // 4) Intersect them to get only groups the teacher actually teaches for this course.
        $validGroupIds = array_intersect($teacherGroupIds, $courseGroupIds);

        // 5) Fetch student user IDs from registrations where
        //    - course_id matches
        //    - group_id is in the intersection
        $studentIds = DB::table('registrations')
            ->where('course_id', $course->id)
            ->whereIn('group_id', $validGroupIds)
            ->pluck('user_id');

        // 6) Load those User models, sorted by name
        $students = User::whereIn('id', $studentIds)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        // 7) Preload any existing grades for “today” by this teacher (for prefill)
        $today = now()->toDateString();
        $existingGrades = Grade::where('course_id', $course->id)
            ->where('teacher_id', $teacherId)
            ->where('date', $today)
            ->get()
            ->keyBy('student_id');

        return view('teacher.grades.bulk', [
            'course'         => $course,
            'students'       => $students,
            'existingGrades' => $existingGrades,
            'today'          => $today,
        ]);
    }


    /**
     * Handle submission of multiple grade entries (bulk‐store).
     *
     * Expects request data in the form:
     *   grades => [
     *       [ 'student_id' => ..., 'assessment_type' => ..., 'percentage' => ..., 'date' => ... ],
     *       ...
     *   ]
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course        $course
     */
    public function bulkStore(Request $request, Course $course)
    {
        $teacherId = Auth::id();
		
		
		
		//$grade_comments = $request->input('grade_comments') ?? null;
        // (a) Confirm teacher teaches this course:
        $teaches = DB::table('course_user')
            ->where('user_id', $teacherId)
            ->where('course_id', $course->id)
            ->exists();

        if (! $teaches) {
            abort(403, 'Unauthorized to record grades for this course.');
        }

        // Loosen validation so that empty rows don't fail:
        $data = $request->validate([
            'grades'                     => ['required','array'],
            'grades.*.student_id'        => ['required','integer','exists:users,id'],
            'grades.*.assessment_type'   => ['nullable','in:test,classwork,homework'],
            'grades.*.percentage'        => ['nullable','numeric','min:0','max:5'],
            'grades.*.date'              => ['nullable','date'],  
			'grades.*.grade_comments'    => ['nullable','string'],
        ]);

        $toInsert = [];
        $warnings = [];

        foreach ($data['grades'] as $idx => $entry) {
            // If this row was left blank (no type or no percentage), skip it:
            if (empty($entry['assessment_type']) && empty($entry['percentage']) && empty($entry['date'])) {
                continue;
            }

            // Now require that all three are present:
            if (! ($entry['assessment_type'] && isset($entry['percentage']) && $entry['date'])) {
                continue;
            }

            // Confirm student is registered:
            $registered = DB::table('registrations')
                ->where('course_id', $course->id)
                ->where('user_id', $entry['student_id'])
                ->exists();
            if (! $registered) {
                continue;
            }

            // Avoid duplicates:
            $existing = Grade::where('student_id', $entry['student_id'])
                ->where('course_id', $course->id)
                ->where('assessment_type', $entry['assessment_type'])
                ->where('date', $entry['date'])
                ->first();
            if ($existing) {
                $studentName = User::find($entry['student_id'])->full_name;
                $gradedBy     = User::find($existing->teacher_id)->full_name;
                $warnings[]   = "{$studentName} was already graded by {$gradedBy} on {$existing->date}.";
                continue;
            }

            // Convert percentage to 1.0–5.0 scale:
			//This is old formula used by previous developer Hajara
            $pct = (int) $entry['percentage'];
            $raw = 1 + ($pct/100)*4;
            $dec = round($raw*2)/2;
            $dec = max(1.0, min(5.0, $dec));
			
			
			$grade = ($pct/2)/10;
			//1 + (percentage / 100) * (5 - 1);
			
			//New formula 
			
			$newGrades = floatval($entry['percentage']);
			
			$newpct = $newGrades * 20;
			
			$toInsert[] = [
                'student_id'      => $entry['student_id'],
                'course_id'       => $course->id,
                'teacher_id'      => $teacherId,
                'assessment_type' => $entry['assessment_type'],
                'percentage'      => round($newpct,2),
                'grade'           => $newGrades,
                'date'            => $entry['date'],
				'grade_comments'=> $entry['grade_comments'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
			
            /*$toInsert[] = [
                'student_id'      => $entry['student_id'],
                'course_id'       => $course->id,
                'teacher_id'      => $teacherId,
                'assessment_type' => $entry['assessment_type'],
                'percentage'      => $pct,
                'grade'           => $grade,
                'date'            => $entry['date'],
				'grade_comments'=> $entry['grade_comments'],
                'created_at'      => now(),
                'updated_at'      => now(),
            ];*/
        }

        // Bulk insert
        if (! empty($toInsert)) {
            Grade::insert($toInsert);
        }

        // Flash summary
        $successCount = count($toInsert);
        $flashData = ['success' => "Grades recorded for {$successCount} student(s)."];
        if (! empty($warnings)) {
            // join warnings or send as array
            $flashData['error'] = implode(' ', $warnings);
        }

        return redirect()
            ->route('grades.bulk.form', $course->id)
            ->with($flashData);
    }


    public function myGrades(Request $request)
    {
        $studentId = Auth::id();

        // 1) Fetch all courses the student is registered in:
        //    Assuming User->courses() relationship exists for "registrations" pivot.
        $courses = Auth::user()
                       ->courses()
                       ->orderBy('course_name')
                       ->get();

        // 2) Build the base grades query for this student:
        $gradesQuery = Grade::with('course')
            ->where('student_id', $studentId);

        // 3) Filter by course if supplied in query string:
        if ($request->filled('course_id')) {
            $gradesQuery->where('course_id', $request->course_id);
        }

        // 4) Filter by assessment type if supplied:
        if ($request->filled('type')) {
            $gradesQuery->where('assessment_type', $request->type);
        }

        // 5) Order by date descending:
        $grades = $gradesQuery->orderBy('date', 'desc')->get();

        return view('student.myGrades', [
            'courses'        => $courses,
            'grades'         => $grades,
            'selectedCourse' => $request->course_id ?? '',
            'selectedType'   => $request->type ?? '',
        ]);
    }

    public function viewAll()
    {
        $teacherId = Auth::id();

        // Fetch all grades where teacher_id = current user, eager‐load student and course
        $grades = Grade::where('teacher_id', $teacherId)
            ->with(['student', 'course'])
            ->orderBy('date', 'desc')
            ->get();

        return view('teacher.grades.view', compact('grades'));
    }

    public function edit($id)
    {
        $grade = Grade::with(['student', 'course'])
            ->findOrFail($id);

        $teacherId = Auth::id();

        // Ensure this teacher actually created this grade:
        if ($grade->teacher_id !== $teacherId) {
            abort(403, 'Unauthorized to edit this grade.');
        }

        return view('teacher.grades.edit', compact('grade'));
    }

    /**
     * Update a grade in storage.
     */
    public function update(Request $request, $id)
    {
        $grade = Grade::findOrFail($id);
        $teacherId = Auth::id();

        if ($grade->teacher_id !== $teacherId) {
            abort(403, 'Unauthorized to update this grade.');
        }

        // Validate input (similar to store)
        $data = $request->validate([
            'assessment_type' => ['required','in:test,classwork,homework'],
            'percentage'      => ['required','numeric','min:0','max:5'],
            'date'            => ['required','date'],
        ]);
		
		
			//New formula 
			
			$newGrades = floatval($data['percentage']);
			
			$newpct = $newGrades * 20;

        // Re‐calculate grade as 1.0–5.0 with 0.5 increments
        $pct = (int) $data['percentage'];
        $rawGrade = 1 + ($pct / 100.0) * 4;
        $decimalGrade = round($rawGrade * 2) / 2;
        $decimalGrade = max(1.0, min(5.0, $decimalGrade));
		
		$studentGrade = ($pct/2)/10;
		
		
		   $grade->update([
            'assessment_type' => $data['assessment_type'],
            'percentage'      => $newpct,
            'grade'           => $newGrades,
            'date'            => $data['date'],
        ]);

		
     /*   $grade->update([
            'assessment_type' => $data['assessment_type'],
            'percentage'      => $pct,
            'grade'           => $studentGrade,
            'date'            => $data['date'],
        ]);
*/

        return redirect()
            ->route('grades.view')
            ->with('success', 'Grade updated successfully.');
    }

    /**
     * Delete a grade.
     */
    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);
        $teacherId = Auth::id();

        if ($grade->teacher_id !== $teacherId) {
            abort(403, 'Unauthorized to delete this grade.');
        }

        $grade->delete();

        return redirect()
            ->route('grades.view')
            ->with('error', 'Grade deleted successfully.');
    }
}
