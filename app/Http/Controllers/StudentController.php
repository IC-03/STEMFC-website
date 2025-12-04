<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // For QR Code
use Picqer\Barcode\BarcodeGeneratorPNG;     // For Barcode
use App\Models\Payment;
use App\Models\History;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Course;
use App\Models\Group;
use App\Models\Grade;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       /* $users = User::with(['role','coursesName'])
            ->where('role_id', 3)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->get();*/
		
		
	$users = User::leftJoin('registrations', 'users.id', '=', 'registrations.user_id')
    ->leftJoin('courses', 'registrations.course_id', '=', 'courses.id')
    ->join('roles', 'users.role_id', '=', 'roles.id')
    ->where('users.role_id', 3)
    ->where('users.is_delete', 0)
    ->orderBy('users.id', 'desc')
    ->select(
        'users.*',
        'courses.course_name',
        'roles.role_name' 
    )
    ->get();

		
		
//echo '<pre>'; print_r( $users); exit;
        $title = 'Student List';
        return view('admin.student.list', compact('title', 'users'));
    }

    public function downloadReceipt()
    {
        $student = auth()->user(); // Assumes role_id = 3; consider adding guard if needed

        // 1) Total fee: sum of all their course values
        $initialAmount = Registration::where('user_id', $student->id)
            ->join('courses', 'registrations.course_id', '=', 'courses.id')
            ->sum('courses.course_value');

        // 2) All payments they’ve made, newest first
        $fees = Payment::where('user_id', $student->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // 3) Totals
        $totalPaid = $fees->sum('amount_paid');
        $runningBalance = max(0, $initialAmount - $totalPaid);

        // 4) Dates
        $currentDate = now();
        $dueDate = $currentDate->copy()->addDays(15);

        // 5) Return Blade view (no PDF)
        return view('student.receipt', [
            'value'         => $student,
            'fees'          => $fees,
            'initialAmount' => $initialAmount,
            'total_paid'    => $totalPaid,
            'lastBalance'   => $runningBalance,
            'currentDate'   => $currentDate,
            'lastDate'      => $dueDate,
            'title'         => 'My Receipt',
        ]);
    }


    /**
     * Generate QR Code for a student.
     */
    public function generateQRCode($uuid)
    {
        $student = User::select('first_name','last_name','id_no','telephone','address','city')
                       ->where('uuid', $uuid)
                       ->firstOrFail();
        $qrCode = QrCode::size(100)->generate(json_encode($student));
        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }

    /**
     * Generate Barcode for a student.
     */
    public function generateBarcode($id)
    {
        $generator = new BarcodeGeneratorPNG();
        $barcode   = $generator->getBarcode($id, $generator::TYPE_CODE_128);
        return response($barcode)->header('Content-Type', 'image/png');
    }

    /**
     * Show form for creating a new student.
     */
    public function create()
    {
        $parents = User::where('role_id', 4)
                       ->where('is_delete', 0)
                       ->orderBy('id', 'desc')
                       ->get();
        $title = 'Create New Student';
        return view('admin.student.new', compact('title', 'parents'));
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname'             => 'required|string|min:3|max:50',
            'lastname'              => 'required|string|min:3|max:50',
            'age'                   => 'required|numeric|min:1|max:50',
            'gender'                => 'required|in:male,female,other',
            'id_no'                 => 'required|string|unique:users,id_no',
            'password'              => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
            'guardname'             => 'nullable|exists:users,id',
            'image'                 => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ], [
            'firstname.required' => 'First name is required.',
            'firstname.min'      => 'First name must be at least 3 characters.',
            'id_no.unique'       => 'ID Number already exists.',
            'gender.required'    => 'Please select a gender.',
        ]);

        $path = $request->hasFile('image')
            ? $request->file('image')->store('images','public')
            : null;

        $usr = new User();
        $usr->uuid        = Str::uuid();
        $usr->first_name  = ucwords(trim($request->firstname));
        $usr->last_name   = ucwords(trim($request->lastname));
        $usr->age         = $request->age;
        $usr->gender      = $request->gender;
        $usr->telephone   = $request->phone;
        $usr->address     = trim($request->address);
        $usr->city        = trim($request->city);
        $usr->id_no       = trim($request->id_no);
        $usr->password    = Hash::make($request->password);
        $usr->stratum     = trim($request->stratum);
        $usr->notes       = trim($request->comments);
        $usr->guard_id    = $request->guardname;
        $usr->role_id     = 3;
        $usr->picture     = $path;
        $usr->save();

        return redirect()
            ->route('admin.student.list')
            ->with('success', "{$usr->first_name} {$usr->last_name} added successfully.");
    }

    /**
     * Display the specified student's profile.
     */
    public function show($uuid)
    {
        // 1) Load student, ensure not deleted and correct role
        $value = User::where('uuid', $uuid)
                    ->where('is_delete', 0)
                    ->where('role_id', 3)      // if you only want students
                    ->firstOrFail();

        // 2) Load parent manually
        $parent = User::where('id', $value->guard_id)
                    ->where('is_delete', 0)
                    ->first();

        // 3) Eager-load courses and their groups
        $courses = $value->courses()->with('groups')->get();


        $groupIds = $courses->pluck('pivot.group_id')->unique()->filter();
        $groups = Group::whereIn('id', $groupIds)->get()->keyBy('id');
    

        // 4) Compute total due
        $initialAmount = $courses->sum('course_value');

		//this is for custom one
		$amountToPay = Payment::where('user_id', $value->id)
                   ->orderBy('created_at', 'asc')
                   ->value('amount_to_pay') ?? 0;

		
        // 5) Get payments
        $fees = Payment::where('user_id', $value->id)
                    ->orderBy('created_at', 'desc')
                    ->get();
		//search the field for custom amount
		$payment_option = $fees->contains('payment_option', 'custom amount');
		if($payment_option){
			$custom_amount = 'custom amount';
		}else{
				$custom_amount = '';
		}

	
	
	// 6) Sum paid
        $total_paid = $fees->sum('amount_paid');

        // 7) Running balance = due – paid
        $runningBalance = $initialAmount - $total_paid;

        // 8) Attendance stats
        $stats7 = Attendance::where('user_uuid', $uuid)
            ->where('date', '>=', now()->subDays(7))
            ->selectRaw('attendance_status, COUNT(*) as count')
            ->groupBy('attendance_status')
            ->pluck('count','attendance_status')
            ->toArray();

        $statsYear = Attendance::where('user_uuid', $uuid)
            ->whereYear('date', now()->year)
            ->selectRaw('attendance_status, COUNT(*) as count')
            ->groupBy('attendance_status')
            ->pluck('count','attendance_status')
            ->toArray();

        $statsAll = Attendance::where('user_uuid', $uuid)
            ->selectRaw('attendance_status, COUNT(*) as count')
            ->groupBy('attendance_status')
            ->pluck('count','attendance_status')
            ->toArray();

        // 9) Optional monthly attendance for charts
        $attendanceData = Attendance::where('user_uuid', $uuid)
            ->selectRaw("DATE_FORMAT(date, '%b-%Y') as month_year")
            ->selectRaw("SUM(CASE WHEN attendance_status='Present' THEN 1 ELSE 0 END) as Present")
            ->selectRaw("SUM(CASE WHEN attendance_status='Absent' THEN 1 ELSE 0 END) as Absent")
            ->selectRaw("SUM(CASE WHEN attendance_status='Excused' THEN 1 ELSE 0 END) as Excused")
            ->groupBy('month_year')
            ->orderByRaw("STR_TO_DATE(month_year, '%b-%Y') asc")
            ->get()
            ->toArray();


        // 10) Title
        $title = "{$value->first_name} {$value->last_name} Profile";

        // NEW: get all grades for this student, eager-load course & teacher
        $grades = Grade::with(['course', 'teacher'])
                 ->where('student_id', $value->id)
                 ->orderBy('date', 'desc')
                 ->get();
		
		
	$periodMap = [1 => '8:15', 2 => '10:25'];

    $roleId = auth()->user()->role_id;
    //$userId = auth()->id();

    $today = Carbon::today();
    $ranges = [
        'lastSevenDays' => [$today->copy()->subDays(6), $today],
        'lastYear'      => [$today->copy()->subYear(), $today],
        'overall'       => [null, null],
    ];

    $datasets = [];
    foreach ($ranges as $key => [$startDate, $endDate]) {
        $datasets[$key] = $this->fetchAttendanceData($startDate, $endDate, $roleId, $uuid);
    }
//echo '<pre>'; print_r($datasets); exit;
        // 11) Return view
        // Add 'groups' to the view
        return view('admin.student.profile', [
            'value'          => $value,
            'parent'         => $parent,
            'courses'        => $courses,
            'fees'           => $fees,
            'initialAmount'  => $initialAmount,
            'total_paid'     => $total_paid,
            'lastBalance'    => $runningBalance,
            'stats7'         => $stats7,
            'statsYear'      => $statsYear,
            'statsAll'       => $statsAll,
            'attendanceData' => $attendanceData,
            'title'          => $title,
            'groups'         => $groups, //pass group info to blade
            'grades'         => $grades,
			'lastSevenDays' => $datasets['lastSevenDays'],
			'lastYear'      => $datasets['lastYear'],
			'overall'       => $datasets['overall'],
			'periodMap'     => $periodMap,
			'custom_amount'=>$custom_amount,
			'amountToPay'=>$amountToPay,
        ]);

      
    }
private function fetchAttendanceData($startDate, $endDate, $roleId, $userId)
{
    $query = DB::table('attendances')
        ->join('users', 'attendances.user_id', '=', 'users.id')
        ->select(
            'attendances.date',
            'attendances.period',
            'attendances.attendance_status',
            'users.id as user_id',
            'users.first_name',
            'users.last_name'
        );

    // Role filter
  /*  if ($roleId == 2) {
        $query->where('attendances.teacher_id', $userId);
    } elseif ($roleId == 3) {
        $query->where('attendances.user_uuid', $userId);
    }*/
	 $query->where('attendances.user_uuid', $userId);

    // Date filter
    if ($startDate && $endDate) {
        $query->whereBetween('attendances.date', [$startDate, $endDate]);
    }

    $attendances = $query
        ->orderBy('users.first_name')
        ->orderBy('attendances.date')
        ->orderBy('attendances.period')
        ->get();

    // Group by student
    $students = [];
    foreach ($attendances as $rec) {
        $status = match ($rec->attendance_status) {
            'Excused' => 'EXC',
            'Present' => 'P',
            'Absent'  => 'A',
            default   => 'NA',
        };
        $fullName = $rec->first_name . ' ' . $rec->last_name;
        $students[$rec->user_id]['name'] = $fullName;
        $students[$rec->user_id]['data'][$rec->date][$rec->period] = $status;
    }

    return $students;
}
    /**
     * Permanently delete a student.
     */
    public function destroy($uuid)
    {
        $user = User::where('uuid',$uuid)->firstOrFail();
        $user->delete();

        return redirect()
            ->route('admin.student.list')
            ->with('error', "{$user->first_name} {$user->last_name} deleted successfully.");
    }

        /**
     * Show the form for editing the specified student.
     */
    public function edit($uuid)
    {
        $student = User::where('uuid', $uuid)
                    ->where('role_id', 3)
                    ->firstOrFail();

        $parents = User::where('role_id', 4)
                    ->where('is_delete', 0)
                    ->orderBy('id', 'desc')
                    ->get();

        $title = 'Edit Student';
        return view('admin.student.edit', compact('student', 'parents', 'title'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, $uuid)
    {
        $student = User::where('uuid', $uuid)->where('role_id', 3)->firstOrFail();

        $request->validate([
            'first_name'             => 'required|string|min:3|max:50',
            'last_name'              => 'required|string|min:3|max:50',
            'age'                   => 'required|numeric|min:1|max:50',
            'gender'                => 'required|in:male,female,other',
            'id_no'                 => 'required|string|unique:users,id_no,' . $student->id,
            'guardname'             => 'nullable|exists:users,id',
            'image'                 => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $student->picture = $path;
        }

        $student->first_name = ucwords(trim($request->first_name));
        $student->last_name  = ucwords(trim($request->last_name));
        $student->age        = $request->age;
        $student->gender     = $request->gender;
        $student->telephone  = $request->phone;
        $student->address    = trim($request->address);
        $student->city       = trim($request->city);
        $student->id_no      = trim($request->id_no);
        $student->stratum    = trim($request->stratum);
        $student->notes      = trim($request->comments);
        $student->guard_id   = $request->guardname;

        // Only update password if filled
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'confirmed|min:8',
            ]);
            $student->password = Hash::make($request->password);
        }

        $student->save();

        return redirect()
            ->route('admin.student.list')
            ->with('success', "{$student->first_name} {$student->last_name} updated successfully.");
    }


    /**
     * Deposit new payment.
     */
    public function deposit(Request $request)
    {
        // 1) Validate incoming fields
        $data = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'month'          => 'required|date_format:Y-m',
            'amount_to_pay'  => 'required|numeric|min:0',
            'amount_paid'    => 'required|numeric|min:0',
            'balance'        => 'required|numeric|min:0',
            'payment_date'   => 'required|date',
        ]);
		$payment_option = $request->input('payment_option');
        // 2) Create payment record
        Payment::create([
            'uuid'           => Str::uuid(),
            'user_id'        => $data['user_id'],
            'month'          => $data['month'],
            'amount_to_pay'  => $data['amount_to_pay'],
            'amount_paid'    => $data['amount_paid'],
            'balance'        => $data['balance'] - $data['amount_paid'],
            'payment_date'   => $data['payment_date'],
			'payment_option'=>$payment_option,
        ]);

        return back()->with('success', 'Payment recorded successfully.');
    }

    /**
     * Delete a payment.
     */
    public function deposit_delete($uuid)
    {
        $payment = Payment::where('uuid', $uuid)->firstOrFail();
        $payment->delete();

        return back()->with('success', 'Payment deleted successfully.');
    }

    /**
     * Show/print a receipt (admin or student).
     */
    public function receipt(Request $request, $uuid)
    {
        // 1) Load student
        $value = User::where('uuid', $uuid)
                     ->where('is_delete', 0)
                     ->firstOrFail();

        // 2) Initial total fee from enrolled courses
        $initialAmount = Registration::where('user_id', $value->id)
                                     ->join('courses', 'registrations.course_id', '=', 'courses.id')
                                     ->sum('courses.course_value');

		
		
		//this is for custom one
		$amountToPay = Payment::where('user_id', $value->id)
                   ->orderBy('created_at', 'asc')
                   ->value('amount_to_pay') ?? 0;
        // 3) Fetch all payments, newest first
        $fees = Payment::where('user_id', $value->id)
                       ->orderBy('created_at', 'desc')
                       ->get();
		
		
		$payment_option = $fees->contains('payment_option', 'custom amount');
		
		if($payment_option){
			$custom_amount = 'custom amount';
		}else{
				$custom_amount = '';
		}
        // 4) Totals
        $totalPaid      = $fees->sum('amount_paid');
        $runningBalance = $initialAmount - $totalPaid;

        // 5) Dates
        $currentDate = now();
        $dueDate     = $currentDate->copy()->addDays(15);
		
		
	// Check if invoice_date is passed in query string
    if ($request->has('invoice_date')) {
			$invoiceDate = Carbon::parse($request->input('invoice_date'));
			

			if ($invoiceDate->day >= 15) {
				// Move to next month’s 15th
				$billingDate = $invoiceDate->copy()->addMonthNoOverflow()->day(15);
			} else {
				// Stay in this month’s 15th
				$billingDate = $invoiceDate->copy()->day(15);
			}
			$invoice_date = $invoiceDate->format('l, M d, Y h:i A');
			$customBillingDate =  $billingDate->toDateString(); // 2025-10-15
	
		}else{
			$invoice_date  = $currentDate->format('l, M d, Y h:i A');
			$customBillingDate = '';
		}
		
		
		

        // 6) Render the view
        return view('admin.student.receipt', [
            'title'         => 'Receipt',
            'value'         => $value,
            'fees'          => $fees,
            'initialAmount' => $initialAmount,
            'total_paid'    => $totalPaid,
            'lastBalance'   => $runningBalance,
            'currentDate'   => $currentDate,
            'lastDate'      => $dueDate,
			'invoice_date'=>$invoice_date,
			'customBillingDate'=>$customBillingDate,
			'custom_amount'=>$custom_amount,
			'amountToPay'=>$amountToPay,
        ]);
    }


    // ... (rom and temp methods remain unchanged) ...

    /**
     * Temporary page
     */
    public function rom()
    {
        $title = 'Temporary Page';
        return view('admin.student.temp', compact('title'));
    }

    /**
     * Handle temp AJAX
     */
    public function temp(Request $request)
    {
        $payment = Payment::create([
            'month' => $request->month,
            'amount' => $request->amount,
            'paid'   => $request->paid,
            'date'   => $request->date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully!',
            'data'    => $payment
        ], 201);
    }
}
