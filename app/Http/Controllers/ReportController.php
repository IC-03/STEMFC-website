<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Course;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
	
	public function agingReport()
    {
		  
        // 1) Page title
        $title = 'Accounts Receivable Aging Report';
		
		$report = DB::table('users as u')
    ->join(DB::raw('(
        SELECT p1.*
        FROM payments p1
        INNER JOIN (
            SELECT user_id, MAX(payment_date) AS latest_date
            FROM payments
            GROUP BY user_id
        ) p2 ON p1.user_id = p2.user_id AND p1.payment_date = p2.latest_date
    ) as latest'), 'u.id', '=', 'latest.user_id')
    ->select(
        'u.id',
        DB::raw('CONCAT(u.first_name, " ", u.last_name) as student_name'),
        DB::raw('SUM(latest.balance) as total_balance'),
        DB::raw('SUM(CASE WHEN DATEDIFF(CURDATE(), latest.payment_date) <= 30 THEN latest.balance ELSE 0 END) as current'),
        DB::raw('SUM(CASE WHEN DATEDIFF(CURDATE(), latest.payment_date) BETWEEN 31 AND 60 THEN latest.balance ELSE 0 END) as over_30'),
        DB::raw('SUM(CASE WHEN DATEDIFF(CURDATE(), latest.payment_date) BETWEEN 61 AND 90 THEN latest.balance ELSE 0 END) as over_60'),
        DB::raw('SUM(CASE WHEN DATEDIFF(CURDATE(), latest.payment_date) > 90 THEN latest.balance ELSE 0 END) as over_90')
    )
    ->groupBy('u.id', 'student_name')
    ->orderBy('student_name')
    ->get();
		
		//echo '<Pre>'; print_r($reportArray); exit;
        return view('admin.reports.agingreport', compact(
            'title','report'));
    
	  }

    public function fees(Request $request)
    {
        // 1) Page title
        $title = 'Fee Report';

        // 2) Raw filter inputs
        $stratum  = $request->get('stratum');
        $courseId = $request->get('course_id');
        $year     = $request->get('year');
        $month    = $request->get('month');
        $dateFrom = $request->get('from');
        $dateTo   = $request->get('to');
        $mode     = $request->get('mode');
		

        // 3) Apply default year filter if no date/year/month filters are selected
        if (!$dateFrom && !$dateTo && !$year && !$month) {
            $year = now()->year;
        }

        // 4) Build dateFrom and dateTo if year/month selected
        if ($year) {
            $dateFrom = "$year-01-01";
            $dateTo   = "$year-12-31";

            if ($month) {
                $dateFrom = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01";
                $dateTo   = date("Y-m-t", strtotime($dateFrom));
            }else{
				 $dateFrom = $request->get('from');
                 $dateTo   = $request->get('to');
			}
        }

        // 5) Build student list (only role_id=3 and optional filters)
        $students = User::where('role_id', 3)
            ->when($stratum, fn($q) => $q->where('stratum', $stratum))
            ->when($courseId, fn($q) => $q->whereHas('courses', fn($qc) =>
                $qc->where('courses.id', $courseId)
            ))
            ->get();
	//echo '<pre>'; print_r( $students); exit;	
/*		
		$students = User::where('role_id', 3)
    ->when($stratum, fn($q) => $q->where('stratum', $stratum))
    ->when($courseId, fn($q) => $q->whereHas('courses', fn($qc) =>
        $qc->where('courses.id', $courseId)
    ))
    ->with(['courses.groups']) // load groups via course
    ->get();*/

        // 6) Map each student to a dues/paid/balance row
        $rows = $students->map(function($stu) use ($dateFrom, $dateTo) {
            $stratum = (int)$stu->stratum;

		//echo '<pre>'; print_r($stu);
            // Amounts
            $discAmt = match ($stratum) {
                1, 2 => 160_000,
                3    => 200_000,
                4    => 260_000,
                default => 0,
            };

            $instAmt = match ($stratum) {
                1, 2 => 180_000,
                3    => 220_000,
                4    => 280_000,
                default => 0,
            };

            // Payments within date range
            $payments = $stu->payments()
                ->when($dateFrom, fn($q) => $q->where('payment_date', '>=', $dateFrom))
                ->when($dateTo,   fn($q) => $q->where('payment_date', '<=', $dateTo))
                ->get();

            $paid     = $payments->sum('amount_paid');
            $lastDate = $payments->max('payment_date');

            // Payment mode
            if ($paid >= $discAmt && $discAmt > 0) {
                $mode     = 'full';
                $dueDisc  = $discAmt;
                $dueInst  = 0;
                $balDisc  = 0;
                $balInst  = 0;
                $totalDue = $discAmt;
            } else {
                $mode     = 'installment';
                $dueDisc  = 0;
                $dueInst  = $instAmt;
                $balDisc  = 0;
                $balInst  = max(0, $instAmt - $paid);
                $totalDue = $instAmt;
            }
			
			
			
			$groupNames = DB::table('courses as c')
    ->join('registrations as r', 'r.course_id', '=', 'c.id')
    ->where('r.user_id', $stu->id)
    ->pluck('c.course_name');
			if ($groupNames->isNotEmpty()) {
    $usergourps = $groupNames->join(', ');
} else {
     $usergourps = '';
}
			

            return [
                'student'      => $stu->full_name,
                'id_no'        => $stu->id_no,
                'stratum'      => $stu->stratum,
				'group_name'        => $usergourps,
                'mode'         => $mode,
                'due_discount' => $dueDisc,
                'due_install'  => $dueInst,
                'paid'         => $paid,
                'bal_disc'     => $balDisc,
                'bal_inst'     => $balInst,
                'total_due'    => $totalDue,
                'last_date'    => $lastDate,
            ];
        });

			//echo '<pre>'; print_r($rows); exit;
        // 7) Filter by mode if selected
        if ($mode) {
            $rows = $rows->filter(fn($r) => $r['mode'] === $mode)->values();
        }

        // 8) Summary grouped by stratum
        $summary = $rows
            ->groupBy('stratum')
            ->map(fn(Collection $grp) => [
                'registrations'    => $grp->count(),
                'billed_discount'  => $grp->sum('due_discount'),
                'billed_install'   => $grp->sum('due_install'),
                'paid'             => $grp->sum('paid'),
                'outstanding_disc' => $grp->sum('bal_disc'),
                'outstanding_inst' => $grp->sum('bal_inst'),
            ]);

        // 9) Other data for the view
        $totalRegs = Registration::count();
        $courses   = Course::orderBy('course_name')->pluck('course_name','id');
		
		
	DB::statement("SET SQL_MODE=''");
		
/*$reportQuery = DB::table('users as u')
    ->join('payments as p', 'p.user_id', '=', 'u.id')
    ->selectRaw("CONCAT(u.first_name, ' ', u.last_name) AS full_name,
                 u.id_no,
                 u.stratum,
                 u.id as userId,
                 MAX(p.payment_date) as last_payment_date,
                 SUM(p.amount_to_pay) as total_to_pay,
                 SUM(p.amount_paid) as total_paid,
                 SUM(p.balance) as total_balance_amount")
    ->where('p.month', '2025-07')
    ->groupBy('u.id_no', 'u.first_name', 'u.last_name', 'u.stratum', 'u.id')
    ->get();*/

		$reportQuery = DB::table('users as u')
    ->join('payments as p', 'p.user_id', '=', 'u.id')
    ->selectRaw("CONCAT(u.first_name, ' ', u.last_name) AS full_name,
                 u.id_no,
                 u.stratum,
                 u.id as userId,
				 u.uuid,
                 MAX(p.payment_date) as last_payment_date,
                 MAX(p.amount_to_pay) as total_to_pay,
                 SUM(p.amount_paid) as total_paid,
                 SUM(p.balance) as total_balance_amount")
			->where('u.role_id', 3)
    ->when(isset($dateFrom) && isset($dateTo), function ($q) use ($dateFrom, $dateTo) {
        $q->whereBetween('p.payment_date', [$dateFrom, $dateTo]);
    })
    ->groupBy('u.id_no', 'u.first_name', 'u.last_name', 'u.stratum', 'u.id')
    ->get();
		
		

		
		
		$reportArray = array();
		if($reportQuery->count() > 0){
			foreach($reportQuery as $paymentRow){
				 $stratum = (int)$paymentRow->stratum;
				$discAmt = match ($stratum) {
                1, 2 => 160_000,
                3    => 200_000,
                4    => 260_000,
                default => 0,
            };

            $instAmt = match ($stratum) {
                1, 2 => 180_000,
                3    => 220_000,
                4    => 280_000,
                default => 0,
            };
				
			//echo $discAmt.'<br>';	
			 // Payment mode
            if ($paymentRow->total_paid >= $discAmt && $discAmt > 0) {
                $mode     = 'full';
                $dueDisc  = $discAmt;
                $dueInst  = 0;
                $balDisc  = 0;
                $balInst  = 0;
                $totalDue = $discAmt;
            } else {
                $mode     = 'installment';
                $dueDisc  = 0;
                $dueInst  = $instAmt;
                $balDisc  = 0;
                $balInst  = max(0, $instAmt - $paymentRow->total_paid);
                $totalDue = $instAmt;
            }
				
	$jgroupNames = DB::table('courses as c')
    ->join('registrations as r', 'r.course_id', '=', 'c.id')
    ->where('r.user_id', $paymentRow->userId)
    ->pluck('c.course_name');
			if ($jgroupNames->isNotEmpty()) {
    $jusergourps = $jgroupNames->join(', ');
} else {
     $jusergourps = '';
}
				
			$fullNameLinked ='<a href="' . route('admin.student.profile', ['uuid' => $paymentRow->uuid]) . '" target="_blank">'.$paymentRow->full_name.'</a>';	
				
				$reportArray[] = [
				 'student'      => $fullNameLinked,
                'id_no'        => $paymentRow->id_no,
                'stratum'      => $stratum,
				'group_name'   => $jusergourps,
                'mode'         => $mode,
                'due_discount' => $dueDisc,
                'due_install'  => $dueInst,
                'paid'         =>  $paymentRow->total_paid,
                'bal_disc'     => $balDisc,
                'bal_inst'     => $paymentRow->total_balance_amount,
                'total_due'    => $paymentRow->total_to_pay,
                'last_date'    => $paymentRow->last_payment_date,
					'discAmt'=>$discAmt,
						'instAmt'=>$instAmt,
					
				
				];
			}
		}
		
		//echo '<Pre>'; print_r($reportArray); exit;
        return view('admin.reports.fees', compact(
            'title','rows','summary','totalRegs','courses',
            'stratum','courseId','dateFrom','dateTo','year','month','mode','reportArray',
        ));
    }


    public function exportFees(Request $request)
    {
        // reuse the above logic to get all variables
        $view   = $this->fees($request);
        $data   = $view->getData();
        $format = $request->query('format', 'csv');

        if ($format === 'pdf') {
            // render PDF
            $pdf = PDF::loadView('admin.reports.fees_pdf', (array) $data);
            return $pdf->download('fee_report_'.now()->format('Ymd').'.pdf');
        }

        // otherwise stream CSV
        $filename = 'fee_report_'.now()->format('Ymd').'.csv';
        $columns  = ['Student','ID No.','Stratum','Mode','Due Disc','Due Inst','Paid','Bal Disc','Bal Inst','Last Payment'];

        $callback = function() use($data, $columns) {
            $handle = fopen('php://output','w');
            // header row
            fputcsv($handle, $columns);
            // data rows
            foreach ($data['rows'] as $r) {
                fputcsv($handle, [
                    $r['student'],
                    $r['id_no'],
                    $r['stratum'],
                    ucfirst($r['mode']),
                    $r['due_discount'],
                    $r['due_install'],
                    $r['paid'],
                    $r['bal_disc'],
                    $r['bal_inst'],
                    $r['last_date'],
                ]);
            }

            // totals row
            fputcsv($handle, []); // blank line
            fputcsv($handle, ['Totals:', '', '',
                '',
                $data['rows']->sum('due_discount'),
                $data['rows']->sum('due_install'),
                $data['rows']->sum('paid'),
                $data['rows']->sum('bal_disc'),
                $data['rows']->sum('bal_inst'),
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
