@extends('layouts.guest')

@section('title', 'إنشاء حساب جديد')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg">
        <div>
            <h1 class="text-2xl font-bold text-center text-gray-800">إنشاء حساب جديد</h1>
            <p class="mt-2 text-sm text-center text-gray-500">انضم إلينا اليوم وابدأ في إدارة عقاراتك.</p>
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

        <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block mb-1 text-sm font-medium text-gray-700">الاسم الكامل</label>
                <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label for="email" class="block mb-1 text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="password" class="block mb-1 text-sm font-medium text-gray-700">كلمة المرور</label>
                <input id="password" name="password" type="password" required
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="password_confirmation" class="block mb-1 text-sm font-medium text-gray-700">تأكيد كلمة المرور</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 text-white font-bold py-3 px-10 rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                    <span>تسجيل</span>
                </button>
            </div>
            <p class="text-sm text-center text-gray-500">
             لديك حساب بالفعل؟ <a href="{{ route('login.create') }}" class="font-medium text-blue-600 hover:text-blue-500">قم بتسجيل الدخول</a>
         </p>
        </form>
    </div>
</div>
@endsection