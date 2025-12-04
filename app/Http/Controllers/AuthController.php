<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Show login form (or redirect authenticated user)
    public function login()
    {
        if (Auth::check()) {
            // Already logged in → redirect to the appropriate dashboard
            switch (Auth::user()->role_id) {
                case 1:
                    return redirect()->route('admin.dashboard');
                case 2:
                    return redirect()->route('teacher.dashboard');
                case 3:
                    return redirect()->route('student.dashboard');
                case 4:
                    return redirect()->route('parent.dashboard');
            }
        }

        $title = 'Login';
        return view('auth.login', compact('title'));
    }

    // Handle login attempt
    public function authlogin(Request $request)
    {
        $data = $request->validate([
            'id_no'    => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'id_no'    => $data['id_no'],
            'password' => $data['password'],
        ];
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect based on role
            switch (Auth::user()->role_id) {
                case 1:
                    return redirect()->route('admin.dashboard');
                case 2:
                    return redirect()->route('teacher.dashboard');
                case 3:
                    return redirect()->route('student.dashboard');
                case 4:
                    return redirect()->route('parent.dashboard');
                default:
                    Auth::logout();
                    return back()->with('error', 'Invalid role.');
            }
        }

        // Failed login → back to login page with error flash and old input
        return redirect()
            ->route('login')
            ->withInput($request->only('id_no', 'remember'))
            ->with('error', 'Incorrect ID number or password.');
    }

    // Logout user
    public function authlogout()
    {
        Auth::logout();
        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    // Show registration form
    public function register()
    {
        $title = 'Register';
        return view('auth.register', compact('title'));
    }

    // Handle registration
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'id_no'     => 'required|string|unique:users,id_no',
            'password'  => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'first_name' => $request->firstname,
            'last_name'  => $request->lastname,
            'id_no'      => $request->id_no,
            'password'   => Hash::make($request->password),
            'role_id'    => 1,            // default role
            'picture'    => 'default.png',
        ]);

        Auth::login($user);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Registration successful. Welcome, ' . $user->full_name . '!');
    }

    // Show forgot-password form
    public function forgotpassword()
    {
        return view('auth.forgotpassword');
    }

    // Handle password recovery
    public function recoverpassword(Request $request)
    {
        $request->validate([
            'id_no'             => 'required|string',
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'password'          => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('id_no', $request->id_no)
                    ->where('first_name', $request->first_name)
                    ->where('last_name', $request->last_name)
                    ->first();

        if (! $user) {
            return redirect()
                ->route('auth.forgotpassword')
                ->withInput($request->only('id_no', 'first_name', 'last_name'))
                ->with('error', 'No matching user found.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('login')
            ->with('success', 'Password updated—please log in.');
    }

    // (Optional) Show password-reset form by token
    /*
    public function reset($token)
    {
        $user = User::where('remember_token', $token)->firstOrFail();
        return view('auth.reset', compact('user'));
    }

    // Handle reset password via token
    public function resetpass($token, Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('remember_token', $token)->firstOrFail();
        $user->password       = Hash::make($request->password);
        $user->remember_token = null;
        $user->save();

        return redirect()
            ->route('login')
            ->with('success', 'Password reset successful.');
    }
    */

    // (Optional) Barcode & QR utilities
    public function generateBarcode($id)
    {
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode   = $generator->getBarcode($id, $generator::TYPE_CODE_128);
        return response($barcode)->header('Content-Type', 'image/png');
    }

    public function generateQRCode($id)
    {
        $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(200)->generate($id);
        return response($qrcode)->header('Content-Type', 'image/png');
    }
}
