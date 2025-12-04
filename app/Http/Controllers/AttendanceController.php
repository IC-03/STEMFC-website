<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Course;
use App\Models\Registration;
use App\Models\User;
use App\Models\Group;
use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class AttendanceController extends Controller
{
	
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $title   = 'Attendance Form';

        $groups = $teacher->teachingGroups()->orderBy('name')->get();

        $group_id  = $request->input('group_id');
        $course_id = $request->input('course_id');
        $mydate    = $request->input('date', today()->toDateString());
        $period    = $request->input('period'); 
		$course_student_id    = $request->input('courseStudents');
		

        $availableCourses = collect();
        $students         = collect();
        $className        = null;
        $courseName       = null;
		
				
		if (!empty($group_id) && !empty($course_id) && !empty($period)) {
			$attendanceSql = Attendance::where('group_id', $group_id)
				->where('date', \Carbon\Carbon::parse($mydate)->toDateString())
				->where('course_id', $course_id)
				->where('period', $period);

			if (!empty($student_id)) {
				$attendanceSql->where('user_id', $student_id);
			}

			$attendanceQry = $attendanceSql->get()->toArray(); // or ->get() if multiple expected
		}else{
			$attendanceQry = [];
		}
				
        if ($group_id) {
            $group = Group::find($group_id);

            if (! $group || ! $group->users->contains($teacher->id)) {
                abort(403, 'Unauthorized to view attendance for that group.');
            }

            $className = $group->name;

            $courseIdsInGroup = DB::table('course_group')
                ->where('group_id', $group_id)
                ->pluck('course_id');

            $teacherCourseIds = DB::table('course_user')
                ->where('user_id', $teacher->id)
                ->pluck('course_id');

            $actualCourseIds = $courseIdsInGroup->intersect($teacherCourseIds);

            $availableCourses = Course::whereIn('id', $actualCourseIds)
                ->orderBy('course_name')
                ->get();

            if ($course_id && in_array($course_id, $actualCourseIds->all())) {
				
                $courseName = Course::find($course_id)->course_name;

              /*  $registrations = Registration::where('group_id', $group_id)
                    ->where('course_id', $course_id)
                    ->with('user')
                    ->get();
				
				if(isset($course_student_id) && !empty($course_student_id)){
					 $registrations->where('user_id', $course_student_id);
				}

                $students = $registrations->pluck('user');*/
				
				$regquery = Registration::where('group_id', $group_id)
					->where('course_id', $course_id)
					->with('user');

				if(isset($course_student_id) && !empty($course_student_id)){
					$regquery->where('user_id', $course_student_id);
				}

				$registrations = $regquery->get();

				$students = $registrations->pluck('user');
            }
        }
			
	if(isset($group_id) && !empty( $group_id) && isset( $course_id) && !empty( $course_id)){
		$stdregistrations = Registration::where('group_id', $group_id)
                    ->where('course_id', $course_id)
                    ->with('user')
                    ->get();

     	$studentsListDD = $stdregistrations->pluck('user');
	}else{
		$studentsListDD = [];
	}
		
		
        return view('teacher.attendance.list', [
            'title'            => $title,
            'groups'           => $groups,
            'availableCourses' => $availableCourses,
            'students'         => $students,
            'group_id'         => $group_id,
            'course_id'        => $course_id,
            'mydate'           => $mydate,
            'period'           => $period,
			'student_id'       => $course_student_id,
            'className'        => $className,
            'courseName'       => $courseName,
			'studentsListDD'=>$studentsListDD,
			'attendanceQry'=>$attendanceQry,
        ]);
    }
public function getCoursesByGroup($group_id)
{
	  $teacher = Auth::user();
    $courseIdsInGroup = DB::table('course_group')
                ->where('group_id', $group_id)
                ->pluck('course_id');

            $teacherCourseIds = DB::table('course_user')
                ->where('user_id', $teacher->id)
                ->pluck('course_id');

            $actualCourseIds = $courseIdsInGroup->intersect($teacherCourseIds);

            $availableCourses = Course::whereIn('id', $actualCourseIds)
                ->orderBy('course_name')
                ->get();

    return response()->json($availableCourses);
}
	
public function getStudentsByCoursesGroup($group_id,$course_id)
{
	  $teacher = Auth::user();
 
	
	 $registrations = Registration::where('group_id', $group_id)
                    ->where('course_id', $course_id)
                    ->with('user')
                    ->get();

     $students = $registrations->pluck('user');

    return response()->json($students);
}	
	
	
	
    public function storeAttendance(Request $request)
    {
        $validated = $request->validate([
            'group_id'  => ['required', 'integer', 'exists:groups,id'],
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'mydate'    => ['required', 'date'],
            'period'    => ['required', 'in:1,2'],
            'users'     => ['required', 'array', 'min:1'],
            'users.*'   => ['required', 'integer', 'exists:users,id'],
            'status'    => ['required', 'array', 'size:' . count($request->input('users', []))],
            'status.*'  => ['required', 'in:Present,Absent,Excused'],
        ]);

        $teacher   = Auth::user();
        $group_id  = $validated['group_id'];
        $course_id = $validated['course_id'];
        $date      = $validated['mydate'];
        $period    = $validated['period'];
        $users     = $validated['users'];
        $statuses  = $validated['status'];
		$student_id = $request->input('student_id');
		$attendance_comments = $request->input('attendance_comments');
		
	

        $group = Group::findOrFail($group_id);
        if (! $group->users->contains($teacher->id)) {
            abort(403, 'Unauthorized to record attendance for this group.');
        }

        $courseIdsInGroup = DB::table('course_group')
            ->where('group_id', $group_id)
            ->pluck('course_id');

        $teacherCourseIds = DB::table('course_user')
            ->where('user_id', $teacher->id)
            ->pluck('course_id');

        $actualCourseIds = $courseIdsInGroup->intersect($teacherCourseIds);

        if (! in_array($course_id, $actualCourseIds->all())) {
            abort(403, 'Unauthorized to record attendance for this course in that group.');
        }
//echo '<pre>'; print_r($users); exit;
        foreach ($users as $index => $studentId) {
            $student = User::findOrFail($studentId);
			//echo '<pre>'; print_r($student);

            if (! $student->uuid) {
                $student->uuid = (string) \Str::uuid();
                $student->save();
            }
//\DB::enableQueryLog();
            $attendanceQry = Attendance::updateOrCreate(
                [
                    'group_id'  => $group_id,
                    'course_id' => $course_id,
                    'date'      => $date,
                    'period'    => $period,
                    'user_id'   => $studentId,
                ],
                [
                    'user_uuid'         => $student->uuid,
                    'attendance_status' => $statuses[$index],
                    'teacher_id'        => $teacher->id,
					'attendance_comments'=>$attendance_comments[$index],
                ]
            );
			//echo '<pre>'; print_r( $attendanceQry); 
			//dd(\DB::getQueryLog());
        }

        return redirect()
            ->route('teacher.attendance.list', [
                'group_id'  => $group_id,
                'course_id' => $course_id,
                'date'      => $date,
                'period'    => $period,
				'courseStudents'=>$student_id,
            ])
            ->with('success', 'Attendance recorded successfully!');
    }

    public function adminList(Request $request)
    {
        $statusFilter = $request->input('status', '');
        $nameFilter   = $request->input('name', '');
        $idNoFilter   = $request->input('id_no', '');
        $groupFilter  = $request->input('group_id', '');
        $courseFilter = $request->input('course_id', '');
        $dateFrom     = $request->input('date_from', '');
        $dateTo       = $request->input('date_to', '');

        $query = Attendance::with(['group.teachers', 'user'])
            ->when($statusFilter && in_array($statusFilter, ['Present','Absent','Excused']), function($q) use ($statusFilter) {
                return $q->where('attendance_status', $statusFilter);
            })
            ->when($groupFilter, function($q) use ($groupFilter) {
                return $q->where('group_id', $groupFilter);
            })
            ->when($courseFilter, function($q) use ($courseFilter) {
                return $q->where('course_id', $courseFilter);
            })
            ->when($nameFilter, function($q) use ($nameFilter) {
                return $q->whereHas('user', function($userQ) use ($nameFilter) {
                    $userQ->where('first_name', 'LIKE', "%{$nameFilter}%")
                          ->orWhere('last_name', 'LIKE', "%{$nameFilter}%");
                });
            })
            ->when($idNoFilter, function($q) use ($idNoFilter) {
                return $q->whereHas('user', function($userQ) use ($idNoFilter) {
                    $userQ->where('id_no', 'LIKE', "%{$idNoFilter}%");
                });
            })
            ->when($dateFrom && $dateTo, function($q) use ($dateFrom, $dateTo) {
                return $q->whereBetween('date', [$dateFrom, $dateTo]);
            })
            ->orderBy('date', 'desc');

        $attendances = $query->paginate(20)
                             ->appends(request()->only(['status','name','id_no','group_id','course_id','date_from','date_to']));

        $summary = Attendance::select('group_id','attendance_status', DB::raw('count(*) as total'))
            ->groupBy('group_id','attendance_status')
            ->get()
            ->groupBy('group_id');

        $groups = Group::all()->keyBy('id');
        $courses = Course::orderBy('course_name')->get()->keyBy('id');
        $title  = "Attendance List";

        return view('admin.attendance.index', compact(
            'attendances',
            'statusFilter',
            'nameFilter',
            'idNoFilter',
            'groupFilter',
            'courseFilter',
            'dateFrom',
            'dateTo',
            'summary',
            'groups',
            'courses',
            'title'
        ));
    }

    public function userAttendanceBarChart($user_id)
    {
        $user = User::findOrFail($user_id);

        $attendanceData = Attendance::where('user_id', $user_id)
            ->select(
                DB::raw("DATE_FORMAT(date, '%Y-%m') as month_year"),
                DB::raw("SUM(CASE WHEN attendance_status = 'Present' THEN 1 ELSE 0 END) as present"),
                DB::raw("SUM(CASE WHEN attendance_status = 'Absent' THEN 1 ELSE 0 END) as absent"),
                DB::raw("SUM(CASE WHEN attendance_status = 'Excused' THEN 1 ELSE 0 END) as Excused")
            )
            ->groupBy(DB::raw("DATE_FORMAT(date, '%Y-%m')"))
            ->orderBy('month_year')
            ->get()
            ->toArray();

        return view('attendance.bar-chart', compact('user', 'attendanceData'));
    }

    public function exportCsv(Request $request): StreamedResponse
    {
		
        $query = Attendance::with(['user', 'group.teachers', 'course', 'teacher'])
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('attendance_status', $request->status);
            })
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('first_name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%');
                });
            })
            ->when($request->filled('id_no'), function ($q) use ($request) {
                $q->whereHas('user', function ($u) use ($request) {
                    $u->where('id_no', 'like', '%' . $request->id_no . '%');
                });
            })
            ->when($request->filled('group_id'), function ($q) use ($request) {
                $q->where('group_id', $request->group_id);
            })
            ->when($request->filled('course_id'), function ($q) use ($request) {
                $q->where('course_id', $request->course_id);
            })
            ->when($request->filled('date_from') && $request->filled('date_to'), function ($q) use ($request) {
                $q->whereBetween('date', [$request->date_from, $request->date_to]);
            })
            ->orderBy('date', 'desc');

        $fileName = 'attendance_' . now()->format('Ymd_His') . '.csv';

        $columns = [
            'No.',
            'ID No.',
            'Date',
            'Course',
            'Period',
            'Student Name',
            'Class',
            'Status',
            'Teacher(s)'
        ];

        $callback = function () use ($query, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);

            $rowNumber = 1;
            $query->chunk(200, function ($attendances) use ($handle, &$rowNumber) {
                foreach ($attendances as $attendance) {
                    $idNo     = $attendance->user->id_no ?? 'N/A';
                    $date     = $attendance->date;
                    $course   = optional($attendance->course)->course_name ?? 'N/A';
                    $period   = $attendance->period ?? 'N/A';
                    $student  = trim(($attendance->user->first_name ?? '') . ' ' . ($attendance->user->last_name ?? ''));
                    $class    = optional($attendance->group)->name ?? 'N/A';
                    $status   = $attendance->attendance_status;
                    $teachers = optional($attendance->group)
                                ->teachers
                                ->pluck('first_name')
                                ->implode(', ') ?: 'N/A';

                    fputcsv($handle, [
                        $rowNumber,
                        $idNo,
                        $date,
                        $course,
                        $period,
                        $student,
                        $class,
                        $status,
                        $teachers,
                    ]);

                    $rowNumber++;
                }
            });

            fclose($handle);
        };

        $response = new StreamedResponse($callback);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="' . $fileName . '"'
        );

        return $response;
    }
	

	
	public function attendanceSheet(Request $request){
		
		$class_period = $request->input('class_period', '');
		
		
		if(isset($class_period) && $class_period=='Period 1'){
			$periodMap = [1 => '8:15'];
		}elseif(isset($class_period) && $class_period=='Period 2'){
			$periodMap = [1 => '10:25'];
		}else{
			$periodMap = [1 => '8:15', 2 => '10:25'];
		}
		
		//echo count($periodMap); exit;
				
/*	$activeGroups = Course::select('id', 'name')
		->where('status', 1)
		->get();*/	
		
		$activeGroups = Course::select('id', 'course_name as name')
		->where('is_delete', 0)
		->get();
	$group_id = $request->input('group_id', '');
	
    //Default to last 5 months up to today if filter not provided
    $startDate = $request->input('start_date')
        ? Carbon::parse($request->input('start_date'))
        : Carbon::now()->subMonths(5)->startOfMonth();

    $endDate = $request->input('end_date')
        ? Carbon::parse($request->input('end_date'))
        : Carbon::today();

    //Generate list of all Saturdays between start and end
    $saturdays = [];
    $current = $startDate->copy();
    while ($current <= $endDate) {
        if ($current->isSaturday()) {
            $saturdays[] = $current->toDateString();
        }
        $current->addDay();
    }

    if (empty($saturdays)) {
		
		// Select all groups
/*	$groupNames = DB::table('groups')
    ->select('id', 'name')
	 ->where('status', 1)
    ->get();*/


        return view('admin.attendance.calendar', [
            'students' => [],
            'dates' => [],
            'periodMap' => $periodMap,
            'title' => 'Attendance Sheet',
			'activeGroups'=>$activeGroups
        ]);
    }
    /*//Query attendance only for Saturdays in that list
    $attendances = DB::table('attendances')
        ->join('users', 'attendances.user_id', '=', 'users.id')
        ->whereIn('attendances.date', $saturdays)
        ->select(
            'attendances.date',
            'attendances.period',
            'attendances.attendance_status',
            'users.id as user_id',
            'users.first_name',
            'users.last_name'
        )
        ->orderBy('users.first_name')
        ->orderBy('attendances.date')
        ->orderBy('attendances.period')
        ->get();*/
		
		$query = DB::table('attendances')
    ->join('users', 'attendances.user_id', '=', 'users.id')
    ->whereIn('attendances.date', $saturdays)
    ->select(
        'attendances.date',
        'attendances.period',
        'attendances.attendance_status',
        'users.id as user_id',
        'users.first_name',
        'users.last_name'
    );
		
		
		// Apply role-based filter
		$roleId = auth()->user()->role_id;
		$userId = auth()->id();
//echo $roleId.'<br>'.$userId; exit;
		if ($roleId == 2) {
			// Teacher
			$query->where('attendances.teacher_id', $userId);
		} elseif ($roleId == 3) {
			// Student
			$query->where('attendances.user_id', $userId);
		}
if (isset($group_id) && !empty($group_id)) {
    $query->where('attendances.course_id', $group_id);
}
// Finalize query with ordering
$attendances = $query
    ->orderBy('users.first_name')
    ->orderBy('attendances.date')
    ->orderBy('attendances.period')
    ->get();

    //Group attendance by student
    $students = [];
    foreach ($attendances as $rec) {
		if ($rec->attendance_status === 'Excused') {
    $attendance_status = 'EXC';
} elseif ($rec->attendance_status === 'Present') {
    $attendance_status = 'P';
} elseif ($rec->attendance_status === 'Absent') {
    $attendance_status = 'A';
} else {
    $attendance_status = 'NA'; // optional fallback
}
        $fullName = $rec->first_name . ' ' . $rec->last_name;
        $students[$rec->user_id]['name'] = $fullName;
        $students[$rec->user_id]['data'][$rec->date][$rec->period] = $attendance_status;
    }

    //Fill NA for missing periods
    foreach ($students as &$student) {
        foreach ($saturdays as $date) {
            foreach ($periodMap as $period => $label) {
                if (!isset($student['data'][$date][$period])) {
                    $student['data'][$date][$period] = '';
                }
            }
        }
    }

	
    return view('admin.attendance.calendar', [
        'students' => $students,
        'dates' => $saturdays,
        'periodMap' => $periodMap,
        'title' => 'Calendar',
			'activeGroups'=>$activeGroups,
    ]);
	}
	
	public function attendanceSheetExportToPdf(Request $request)
{
	
	$class_period = $request->input('class_period', '');
		
		
		if(isset($class_period) && $class_period=='Period 1'){
			$periodMap = [1 => '8:15'];
		}elseif(isset($class_period) && $class_period=='Period 2'){
			$periodMap = [1 => '10:25'];
		}else{
			$periodMap = [1 => '8:15', 2 => '10:25'];
		}	
		
    // Reuse same logic from exactGrid
    //$periodMap = [1 => '8:15', 2 => '10:25'];

	$group_id = $request->input('group_id', '');
    $startDate = $request->input('start_date')
        ? Carbon::parse($request->input('start_date'))
        : Carbon::now()->subMonths(5)->startOfMonth();

    $endDate = $request->input('end_date')
        ? Carbon::parse($request->input('end_date'))
        : Carbon::today();

    $saturdays = [];
    $current = $startDate->copy();
    while ($current <= $endDate) {
        if ($current->isSaturday()) {
            $saturdays[] = $current->toDateString();
        }
        $current->addDay();
    }

    /*$attendances = DB::table('attendances')
        ->join('users', 'attendances.user_id', '=', 'users.id')
        ->whereIn('attendances.date', $saturdays)
        ->select(
            'attendances.date',
            'attendances.period',
            'attendances.attendance_status',
            'users.id as user_id',
            'users.first_name',
            'users.last_name'
        )
        ->orderByRaw("CONCAT(users.first_name, ' ', users.last_name)")
        ->orderBy('attendances.date')
        ->orderBy('attendances.period')
        ->get();*/
		
		$query = DB::table('attendances')
    ->join('users', 'attendances.user_id', '=', 'users.id')
    ->whereIn('attendances.date', $saturdays)
    ->select(
        'attendances.date',
        'attendances.period',
        'attendances.attendance_status',
        'users.id as user_id',
        'users.first_name',
        'users.last_name'
    );
		
		
		// Apply role-based filter
		$roleId = auth()->user()->role_id;
		$userId = auth()->id();
//echo $roleId.'<br>'.$userId; exit;
		if ($roleId == 2) {
			// Teacher
			$query->where('attendances.teacher_id', $userId);
		} elseif ($roleId == 3) {
			// Student
			$query->where('attendances.user_id', $userId);
		}
	
		if (isset($group_id) && !empty($group_id)) {
			$query->where('attendances.course_id', $group_id);
		}
// Finalize query with ordering
$attendances = $query
    ->orderBy('users.first_name')
    ->orderBy('attendances.date')
    ->orderBy('attendances.period')
    ->get();

		

    $students = [];
    foreach ($attendances as $rec) {
        $fullName = $rec->first_name . ' ' . $rec->last_name;

        if ($rec->attendance_status === 'Excused') {
            $attendance_status = 'EXC';
        } elseif ($rec->attendance_status === 'Present') {
            $attendance_status = 'P';
        } elseif ($rec->attendance_status === 'Absent') {
            $attendance_status = 'A';
        } else {
            $attendance_status = '';
        }

        $students[$rec->user_id]['name'] = $fullName;
        $students[$rec->user_id]['data'][$rec->date][$rec->period] = $attendance_status;
    }

    foreach ($students as &$student) {
        foreach ($saturdays as $date) {
            foreach ($periodMap as $period => $label) {
                if (!isset($student['data'][$date][$period])) {
                    $student['data'][$date][$period] = '';
                }
            }
        }
    }

    $html = View::make('admin.attendance.calendar_pdf', [
        'students' => $students,
        'dates' => $saturdays,
        'periodMap' => $periodMap,
    ])->render();

    $mpdf = new Mpdf(['orientation' => 'L']);
    $mpdf->WriteHTML($html);
    return $mpdf->Output('attendance_calendar.pdf', 'I'); // show in browser
}
}
