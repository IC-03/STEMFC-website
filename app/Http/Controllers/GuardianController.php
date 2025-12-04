<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;           
use App\Models\Payment; 

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role') // Eager load to avoid N+1 queries
            ->where('role_id', 4)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->get(); // Restore pagination

        $title = 'Guardians List';
        return view('admin.guardian.list', compact('title', 'users'));
    }

    public function downloadReceipt(string $uuid)
    {
        $guardian = Auth::user();

        // 1. Confirm the current user really is a guardian
        if ($guardian->role_id !== 4) {
            abort(403, 'Only guardians may download receipts.');
        }

        // 2. Find the child and ensure it belongs to this guardian and is a student
        $child = User::where('uuid', $uuid)
                     ->where('guard_id', $guardian->id)
                     ->where('role_id', 3)
                     ->where('is_delete', 0)
                     ->firstOrFail();

        // 3. Prepare receipt data
        $currentDate   = now();
        $lastDate      = (clone $currentDate)->addDays(15);

        // a) total of all that child's course fees
        $initialAmount = Registration::where('user_id', $child->id)
                                     ->join('courses', 'registrations.course_id', '=', 'courses.id')
                                     ->sum('courses.course_value');

        // b) that child's payments
        $fees = Payment::where('user_id', $child->id)
                       ->orderBy('id', 'desc')
                       ->get();

        $total_paid = $fees->sum('amount_paid');
        $lastBalance = $fees->isNotEmpty()
            ? $fees->first()->balance
            : $initialAmount;

        // 4. Render a parent‐friendly receipt view
       return view('admin.student.receipt', [
            'value'         => $child,
            'fees'          => $fees,
            'initialAmount' => $initialAmount,
            'total_paid'    => $total_paid,
            'lastBalance'   => $lastBalance,
            'currentDate'   => $currentDate,
            'lastDate'      => $lastDate,
            'title'         => "{$child->first_name} {$child->last_name} Receipt"
       ]);
    }

    public function edit($uuid)
    {
        $guardian = User::where('uuid', $uuid)->firstOrFail();
        $title = 'Edit Record';
        return view('admin.guardian.edit', compact('guardian', 'title'));
    }

    public function update(Request $request, $uuid)
    {
        $guardian = User::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'id_no' => 'required|string|unique:users,id_no,' . $guardian->id,
            'gender' => 'required|in:male,female,other',
            'password' => 'nullable|string|min:6|confirmed',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $guardian->first_name = $validated['first_name'];
        $guardian->last_name = $validated['last_name'];
        $guardian->telephone = $validated['telephone'];
        $guardian->id_no = $validated['id_no'];
        $guardian->gender = $validated['gender'];
        $guardian->notes = $validated['notes'] ?? null;

        if ($request->filled('password')) {
            $guardian->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('guardians', 'public');
            $guardian->picture = $imagePath;
        }

        $guardian->save();

        return redirect()->route('admin.guardian.list', ['uuid' => $guardian->uuid])
                        ->with('success', 'Guardian updated successfully.');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'New Guardian';
        return view('admin.guardian.new', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|min:3|max:50',
            'last_name'    => 'required|string|min:3|max:50',
            'telephone'   => 'nullable|string|max:20',
            'id_no'       => 'required|string|unique:users,id_no',
            'gender'      => 'required|in:male,female,other',
            'password'    => 'nullable|confirmed|min:8',
            'image'       => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'notes'       => 'nullable|string|max:500',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
        }

        $user = new User();
        $user->uuid        = Str::uuid();
        $user->first_name  = ucwords(trim($request->first_name));
        $user->last_name   = ucwords(trim($request->last_name));
        $user->telephone   = trim($request->telephone);
        $user->id_no       = trim($request->id_no);
        $user->gender      = $request->gender;
        $user->notes       = trim($request->notes);
        $user->password    = Hash::make($request->password);
        $user->role_id     = 4;
        $user->picture     = $path;
        $user->save();

        $fullName = $user->first_name . ' ' . $user->last_name;
        return redirect()->route('admin.guardian.list')
                         ->with('success', "$fullName added successfully.");
    }

    /**
     * Display the specified guardian and their children.
     */
    public function show($uuid)
    {
        $parent = User::with('role')
                      ->where('uuid', $uuid)
                      ->where('is_delete', 0)
                      ->firstOrFail();

        $students = User::where('guard_id', $parent->id)
                        ->where('is_delete', 0)
                        ->get();

        $title = $parent->first_name . ' ' . $parent->last_name . ' Profile';

        return view('admin.guardian.profile', compact('parent', 'students', 'title'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $fullName = $user->first_name . ' ' . $user->last_name;
        $user->delete();

        return redirect()->route('admin.guardian.list')
                         ->with('error', "$fullName deleted successfully.");
    }
}
