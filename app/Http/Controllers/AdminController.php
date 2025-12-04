<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::where('role_id', 1)
            ->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->get();

        if ($admins->isEmpty()) {
            abort(404);
        }

        $title = 'Admin List';
        return view('admin.admin.list', compact('title', 'admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Create New Admin";
        return view('admin.admin.new', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|min:3|max:50',
            'lastname' => 'required|string|min:3|max:50',
            'id_no' => 'required|string|unique:users,id_no',
            'telephone' => 'nullable|string|max:20',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ], [
            'firstname.required' => 'First name cannot be blank.',
            'firstname.min' => 'First name must be at least 3 characters.',
            'lastname.required' => 'Last name cannot be blank.',
            'id_no.required' => 'ID Number is required.',
            'id_no.unique' => 'This ID Number already exists.',
            'password.required' => 'Password cannot be blank.',
            'password.confirmed' => 'Password and confirmation do not match.',
        ]);


        $usr = new User();
        $usr->uuid = Str::uuid();
        $usr->first_name = ucwords(trim($request->firstname));
        $usr->last_name = ucwords(trim($request->lastname));
        $usr->id_no = trim($request->id_no);
        $usr->telephone = trim($request->telephone);
        $usr->password = Hash::make($request->password);
        $usr->role_id = 1;
        $usr->save();

        $fullName = "{$usr->first_name} {$usr->last_name}";
        return redirect()->route('admin.admin.list')
            ->with('success', "$fullName added successfully.");
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        $admin = User::where('uuid', $uuid)
            ->where('role_id', 1)
            ->firstOrFail();

        $title = "Edit Admin";
        return view('admin.admin.edit', compact('admin', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $uuid)
    // {
    //     // Only admins can update their own profile here; adjust as needed
    //     $admin = User::where('uuid', $uuid)
    //                  ->where('role_id', 1)
    //                  ->firstOrFail();

    //     $request->validate([
    //         'firstname'             => 'required|string|min:3|max:50',
    //         'lastname'              => 'required|string|min:3|max:50',
    //         'id_no'                 => 'required|string|unique:users,id_no,' . $admin->id,
    //         'telephone'             => 'nullable|string|max:20',
    //         'old_password'          => 'nullable|string',
    //         'password'              => 'nullable|confirmed|min:8',
    //         'password_confirmation' => 'nullable',
    //     ], [
    //         'id_no.required'        => 'ID Number is required.',
    //         'id_no.unique'          => 'This ID Number is already taken.',
    //         'password.confirmed'    => 'Password and confirmation do not match.',
    //     ]);

    //     // Handle password change
    //     if ($request->filled('password')) {
    //         if (! Hash::check($request->old_password, $admin->password)) {
    //             return back()->with('error', 'Old password is incorrect.');
    //         }
    //         $admin->password = Hash::make($request->password);
    //     }

    //     $admin->first_name = ucwords(trim($request->firstname));
    //     $admin->last_name  = ucwords(trim($request->lastname));
    //     $admin->id_no      = trim($request->id_no);
    //     $admin->telephone  = trim($request->telephone);
    //     $admin->save();

    //     $fullName = "{$admin->first_name} {$admin->last_name}";

    //     // If password changed, log out
    //     if ($request->filled('password')) {
    //         Auth::logout();
    //         return redirect()->route('login')
    //                          ->with('success', "$fullName updated. Please log in again.");
    //     }

    //     return redirect()->route('admin.admin.list')
    //                      ->with('success', "$fullName updated successfully.");
    // }

    public function update(Request $request, $uuid)
    {
        $admin = User::where('uuid', $uuid)
            ->where('role_id', 1)
            ->firstOrFail();

        $request->validate([
            'firstname' => 'required|string|min:3|max:50',
            'lastname' => 'required|string|min:3|max:50',
            'id_no' => 'required|string|unique:users,id_no,' . $admin->id,
            'telephone' => 'nullable|string|max:20',
            'password' => 'nullable|confirmed|min:8',
        ], [
            'firstname.required' => 'First name cannot be blank.',
            'lastname.required' => 'Last name cannot be blank.',
            'id_no.required' => 'ID Number is required.',
            'id_no.unique' => 'This ID Number is already taken.',
            'password.confirmed' => 'Password and confirmation do not match.',
        ]);

        // Datos básicos
        $admin->first_name = ucwords(trim($request->firstname));
        $admin->last_name = ucwords(trim($request->lastname));
        $admin->id_no = trim($request->id_no);
        $admin->telephone = trim($request->telephone);

        // Cambiar contraseña solo si se llenó
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        $fullName = "{$admin->first_name} {$admin->last_name}";

        return redirect()
            ->route('admin.admin.list')
            ->with('success', "$fullName updated successfully.");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        $admin = User::where('uuid', $uuid)
            ->where('role_id', 1)
            ->firstOrFail();

        $fullName = "{$admin->first_name} {$admin->last_name}";

        // Use soft-delete flag if applicable, otherwise delete
        // $admin->is_delete = 1;
        // $admin->save();
        $admin->delete();

        return redirect()->route('admin.admin.list')
            ->with('success', "$fullName deleted successfully.");
    }
}
