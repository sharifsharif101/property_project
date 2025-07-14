<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AuthenticatedSessionController extends Controller
{
    
    public function createRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * معالجة طلب تسجيل مستخدم جديد.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // (اختياري) تعيين دور افتراضي للمستخدم الجديد
        // إذا لم يكن هناك دور "Data Entry", ستحتاج لإنشائه أو اختيار دور آخر.
        $defaultRole = Role::where('name', 'Data Entry')->first();
        if ($defaultRole) {
            $user->assignRole($defaultRole);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }


    // --- تسجيل الدخول ---

    /**
     * عرض صفحة تسجيل الدخول.
     */
    public function createLoginForm()
    {
        return view('auth.login');
    }

    /**
     * معالجة طلب تسجيل الدخول.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'البيانات المقدمة غير متطابقة مع سجلاتنا.',
        ])->onlyInput('email');
    }


    // --- تسجيل الخروج ---

    /**
     * معالجة طلب تسجيل الخروج.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}