<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\Course;
use App\Models\Group;

class ReceiptController extends Controller
{
    // Student views their own receipt
    public function myReceipt()
    {
        $student = Auth::user();          // role_id == 3
        return $this->buildReceipt($student);
    }

    // Parent views a specific child's receipt
    public function childReceipt($uuid)
    {
        $parent = Auth::user();           // role_id == 4
        $child  = User::where('uuid', $uuid)
                      ->where('guard_id', $parent->id)
                      ->firstOrFail();

        return $this->buildReceipt($child);
    }

    // Shared logic to gather data & render
    protected function buildReceipt(User $student)
    {
        // compute all the same variables you had in admin student controller:
        $courses       = $student->courses()->pluck('course_id');
        $initialAmount = Course::whereIn('id', $courses)->sum('course_value');
        $fees          = Payment::where('user_id', $student->id)
                                ->orderBy('id','desc')->get();
        $total_paid    = $fees->sum('amount_paid');
        $lastBalance   = $fees->isNotEmpty()
                         ? $fees->first()->balance
                         : $initialAmount;
        $currentDate   = now();
        $lastDate      = now()->addDays(15);

        return view('admin.receipt', compact(
            'student','fees','initialAmount','total_paid',
            'lastBalance','currentDate','lastDate'
        ));
    }
}
