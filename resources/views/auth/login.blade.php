@extends('layouts.guest') {{-- سنقوم بإنشاء هذا الـ layout البسيط --}}

@section('title', 'تسجيل الدخول')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg">
        <div>
            <h1 class="text-2xl font-bold text-center text-gray-800">تسجيل الدخول</h1>
            <p class="mt-2 text-sm text-center text-gray-500">مرحباً بعودتك! الرجاء إدخال بياناتك.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md text-sm" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block mb-1 text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="password" class="block mb-1 text-sm font-medium text-gray-700">كلمة المرور</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">تذكرني</label>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 text-white font-bold py-3 px-10 rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                    <span>دخول</span>
                </button>
            </div>
             <p class="text-sm text-center text-gray-500">
             ليس لديك حساب؟ <a href="{{ route('register.create') }}" class="font-medium text-blue-600 hover:text-blue-500">أنشئ حساباً جديداً</a>
         </p>
        </form>
    </div>
</div>
@endsection