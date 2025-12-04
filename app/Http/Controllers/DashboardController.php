<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Registration;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\Payment;
use Illuminate\Support\Facades\DB; 
use App\Models\Group;
use Carbon\Carbon;
use App\Models\Assignment;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $role = $user->role_id;

        $title = 'Dashboard';
        $data = [
            'title'    => $title,
            'admins'   => User::where('role_id', 1)->where('is_delete', 0)->count(),
            'teachers' => User::where('role_id', 2)->where('is_delete', 0)->count(),
            'students' => User::where('role_id', 3)->where('is_delete', 0)->count(),
            'parents'  => User::where('role_id', 4)->where('is_delete', 0)->count(),
        ];



        // STUDENT DASHBOARD
        if ($role === 3) {
            $courses   = $user->courses()->with('groups')->get();
            $courseIds = $courses->pluck('id')->all(); // array of IDs
            
            $today = Carbon::today()->toDateString(); // e.g. “2025-06-04”
			
			
        
            /*
             | Fetch courses whose related group(s) have start_date ≥ today,
             | and order by the earliest group.start_date.
             */
            /*$upcoming = Course::query()
                // only consider courses the user is enrolled in
                ->whereIn('courses.id', $courseIds)
        
                // join the pivot and groups tables so we can filter & sort by groups.start_date
                ->join('course_group', 'course_group.course_id', '=', 'courses.id')
                ->join('groups',       'groups.id',         '=', 'course_group.group_id')
        
                // only groups whose start_date >= today
                ->whereDate('groups.start_date', '>=', $today)
        
                // order by the group.start_date ascending
                ->orderBy('groups.start_date', 'asc')
        
                // select only the courses.* columns (to avoid collisions with groups.*)
                ->select('courses.*')
        
                // if a given course appears under multiple qualifying groups,
                // DISTINCT ensures it only shows once in the final list
                ->distinct()
                
                // eager-load the groups relationship so you can access group data in blade
                ->with(['groups' => function ($q) use ($today) {
                    // optionally, only load groups that start >= today
                    $q->whereDate('start_date', '>=', $today);
                }])
                
                ->get();*/
			
			$upcoming = Course::query()
    // only consider courses the user is enrolled in
    ->whereIn('courses.id', $courseIds)
				
				

    // join the pivot and groups tables so we can filter & sort by groups.start_date
    ->join('course_group', 'course_group.course_id', '=', 'courses.id')
    ->join('groups', 'groups.id', '=', 'course_group.group_id')

    // only groups whose start_date >= today
    ->whereDate('groups.start_date', '>=', $today)

    // order by the group.start_date ascending
    ->orderBy('groups.start_date', 'asc')

    // select courses.* and groups.start_date to fix the DISTINCT + ORDER BY error
    ->select('courses.*', 'groups.start_date')

    // DISTINCT so courses don't repeat
    ->distinct()

    // eager-load the groups relationship
    ->with(['groups' => function ($q) use ($today) {
        $q->whereDate('start_date', '>=', $today);
    }])

    ->get();


            $stats7     = $this->getAttendanceStats([$user->uuid], Carbon::today()->subDays(7));
            $statsYear  = $this->getAttendanceStats([$user->uuid], Carbon::today()->subYear());
            $statsAll   = $this->getAttendanceStats([$user->uuid]);

            $totalCourseFee = $courses->sum('course_value');
            $totalPaid      = Payment::where('user_id', $user->id)->sum('amount_paid');
            $displayBalance = max($totalCourseFee - $totalPaid, 0);
			
			
			
			    $assignments = Assignment::whereIn('course_id', $courseIds)->get();

            return view('student.dashboard', array_merge($data, [
                'student' => User::with('payments')->find($user->id),
                'courses'          => $courses,
                'upcoming'         => $upcoming,
                'stats7'           => $stats7,
                'statsYear'        => $statsYear,
                'statsAll'         => $statsAll,
                'displayTotalFee'  => $totalCourseFee,
                'totalPaid'        => $totalPaid,
                'displayBalance'   => $displayBalance,
				'assignments'=>$assignments,
            ]));
        }



        // TEACHER DASHBOARD
        if ($role === 2) {
            $teacherId = $user->id;

            //
            // STEP 1: which group_ids does this teacher belong to? (via group_user pivot)
            //
            $groupIds = DB::table('group_user')
                ->where('user_id', $teacherId)
                ->pluck('group_id');

            //
            // STEP 2: which course_ids are linked to those group_ids? (via course_group pivot)
            //
            $courseIdsFromGroups = DB::table('course_group')
                ->whereIn('group_id', $groupIds)
                ->pluck('course_id');

            //
            // STEP 3: which course_ids are directly assigned to this teacher? (via course_user pivot)
            //
            $teacherCourseIds = DB::table('course_user')
                ->where('user_id', $teacherId)
                ->pluck('course_id');

            //
            // STEP 4: intersect those two lists → “actual courses this teacher teaches”
            //
            $taughtCourseIds = $courseIdsFromGroups->intersect($teacherCourseIds);

            //
            // Now build all four dashboard numbers:
            //

            // (A) Classes count = how many distinct groups this teacher belongs to
            $classesCount = $groupIds->unique()->count();

            // (B) Subjects count = how many distinct courses in that intersection
            $subjectsCount = $taughtCourseIds->unique()->count();

            // (C) Students count = how many distinct students are in any of those groups
            //     (We look in registrations by group_id)
            $studentIds = Registration::whereIn('group_id', $groupIds)->pluck('user_id')->unique();
            $studentsCount = $studentIds->count();

            // (D) Attendance count = how many attendance records exist for those groups
            $attendanceCount = Attendance::whereIn('group_id', $groupIds)->count();

            return view('teacher.dashboard', array_merge($data, [
                'classesCount'    => $classesCount,
                'subjectsCount'   => $subjectsCount,
                'studentsCount'   => $studentsCount,
                'attendanceCount' => $attendanceCount,
            ]));
        }


        // ADMIN DASHBOARD
        if ($role === 1) {
            return view('admin.dashboard', $data);
        }

        abort(403);
    }

    private function getAttendanceStats($uuids, $since = null)
    {
        $query = Attendance::whereIn('user_uuid', $uuids);

        if ($since) {
            $query->whereDate('date', '>=', $since);
        }

        $raw = $query->selectRaw('attendance_status, COUNT(*) as count')
                     ->groupBy('attendance_status')
                     ->pluck('count', 'attendance_status')
                     ->toArray();

        return [
            'Present' => $raw['Present'] ?? 0,
            'Absent'  => $raw['Absent']  ?? 0,
            'Excused' => $raw['Excused'] ?? 0,
        ];
    }

       // PARENT DASHBOARD
       /***  if ($role === 4) {
        // 1. Fetch children
        $children   = User::where('guard_id', $user->id)
                          ->where('is_delete', 0)
                          ->get();
        $childIds   = $children->pluck('id')->toArray();
        $childUuids = $children->pluck('uuid')->toArray();

        // 2. Upcoming Classes
        $upcoming = Course::whereIn('id', function($q) use ($childIds) {
                            $q->select('course_id')
                              ->from('registrations')
                              ->whereIn('user_id', $childIds);
                        })
                        ->whereDate('start_date', '>=', Carbon::today())
                        ->orderBy('start_date')
                        ->get();

        // 3. Attendance Stats
        $stats7    = $this->getAttendanceStats($childUuids, Carbon::today()->subDays(7));
        $statsYear = $this->getAttendanceStats($childUuids, Carbon::today()->subYear());
        $statsAll  = $this->getAttendanceStats($childUuids);

        // 4. Fee logic
        $initialAmount = Course::whereIn('id', function($q) use ($childIds) {
                                $q->select('course_id')
                                  ->from('registrations')
                                  ->whereIn('user_id', $childIds);
                            })->sum('course_value');

        $totalPaid   = Payment::whereIn('user_id', $childIds)
                              ->sum('amount_paid');
        $lastBalance = $initialAmount - $totalPaid;

        // 5. Enrolled Courses list
        $enrolledCourseIds = Registration::whereIn('user_id', $childIds)
                                         ->distinct()
                                         ->pluck('course_id');
        $enrolled = Course::whereIn('id', $enrolledCourseIds)->get();

        // 6. Per-child courses & attendance
        $childCourseData = [];
        foreach ($children as $child) {
            // Courses for this child
            $courses = Course::whereIn('id', function($q) use ($child) {
                                $q->select('course_id')
                                  ->from('registrations')
                                  ->where('user_id', $child->id);
                            })->get();

            // Attendance for this child
            $attendance = DB::table('attendances')
                            ->select('date', 'attendance_status')
                            ->where('user_uuid', $child->uuid)
                            ->orderBy('date', 'desc')
                            ->get();

            $childCourseData[$child->id] = [
                'courses'    => $courses,
                'attendance' => $attendance,
            ];
        }

        return view('parent.dashboard', array_merge($data, [
            'children'        => $children,
            'upcoming'        => $upcoming,
            'stats7'          => $stats7,
            'statsYear'       => $statsYear,
            'statsAll'        => $statsAll,
            'initialAmount'   => $initialAmount,
            'totalPaid'       => $totalPaid,
            'lastBalance'     => $lastBalance,
            'enrolled'        => $enrolled,
            'childCourseData' => $childCourseData,
        ]));
    }  */
}
