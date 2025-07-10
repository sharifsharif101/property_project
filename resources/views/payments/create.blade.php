@extends('layouts.app')

@section('title', 'إضافة دفعة جديدة')

@section('content')

    {{-- رسائل النظام --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md shadow-sm" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- البطاقة الرئيسية -->
    <div class="bg-white rounded-xl shadow-lg">
        <!-- رأس البطاقة -->
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-slate-800">تسجيل دفعة إيجار جديدة</h2>
            {{-- زر للعودة إلى الصفحة السابقة أو السجل --}}
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center gap-2 bg-slate-200 text-slate-700 font-semibold py-2 px-4 rounded-lg shadow-sm hover:bg-slate-300 transition-colors">
                <span>العودة</span>
            </a>
        </div>

        <!-- جسم البطاقة (يحتوي على الفورم) -->
        <div class="p-6 md:p-8">
            <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- حقل القسط المستحق -->
                <div>
                    <label for="rent_installment_id" class="block text-sm font-medium text-slate-700 mb-2">القسط المستحق <span class="text-red-500">*</span></label>
                    <select name="rent_installment_id" id="rent_installment_id" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('rent_installment_id') border-red-500 ring-red-500 @enderror" required>
                        <option value="">-- اختر القسط --</option>
                        @foreach($unpaidInstallments as $installment)
                            <option value="{{ $installment->id }}" {{ old('rent_installment_id', $selectedInstallmentId ?? '') == $installment->id ? 'selected' : '' }}>
                                {{ $installment->contract->tenant->first_name ?? 'N/A' }} {{ $installment->contract->tenant->last_name ?? '' }} | تاريخ: {{ $installment->due_date }} | المتبقي: {{ number_format($installment->amount_due + $installment->late_fee - $installment->amount_paid, 2) }} ريال
                            </option>
                        @endforeach
                    </select>
                    @error('rent_installment_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل المبلغ المدفوع -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-slate-700 mb-2">المبلغ المدفوع <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="amount" id="amount" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('amount') border-red-500 ring-red-500 @enderror" value="{{ old('amount') }}" required>
                    @error('amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- حقل تاريخ الدفع -->
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-slate-700 mb-2">تاريخ الدفع <span class="text-red-500">*</span></label>
                    <input type="date" name="payment_date" id="payment_date" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('payment_date') border-red-500 ring-red-500 @enderror" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                    @error('payment_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل طريقة الدفع -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-slate-700 mb-2">طريقة الدفع <span class="text-red-500">*</span></label>
                    <select name="payment_method" id="payment_method" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('payment_method') border-red-500 ring-red-500 @enderror" required>
                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>نقد (كاش)</option>
                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>حوالة بنكية</option>
                        <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                        <option value="Online Payment" {{ old('payment_method') == 'Online Payment' ? 'selected' : '' }}>دفع إلكتروني</option>
                        <option value="Check" {{ old('payment_method') == 'Check' ? 'selected' : '' }}>شيك</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- حقل الرقم المرجعي -->
                <div>
                    <label for="transaction_reference" class="block text-sm font-medium text-slate-700 mb-2">الرقم المرجعي (اختياري)</label>
                    <input type="text" name="transaction_reference" id="transaction_reference" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" value="{{ old('transaction_reference') }}">
                </div>
                
                <!-- حقل الملاحظات -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">ملاحظات (اختياري)</label>
                    <textarea name="notes" id="notes" rows="3" class="block w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('notes') }}</textarea>
                </div>

                <!-- زر الإرسال -->
                <div class="pt-4 border-t border-slate-200">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                        تسجيل الدفعة
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- لا توجد سكربتات خاصة بهذه الصفحة حالياً --}}
@endpush