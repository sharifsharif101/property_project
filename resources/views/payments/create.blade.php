<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إضافة دفعة جديدة</title>
    <!-- أضف هنا ملفات الـ CSS الخاصة بك، مثل Bootstrap أو Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>تسجيل دفعة إيجار جديدة</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('payments.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="rent_installment_id" class="form-label">القسط المستحق</label>
                <select name="rent_installment_id" id="rent_installment_id" class="form-select @error('rent_installment_id') is-invalid @enderror" required>
    <option value="">-- اختر القسط --</option>
    @foreach($unpaidInstallments as $installment)
        {{-- ✅✅✅ السطر التالي هو الذي تم تعديله ✅✅✅ --}}
        <option value="{{ $installment->id }}" {{ (isset($selectedInstallmentId) && $selectedInstallmentId == $installment->id) ? 'selected' : '' }}>
            {{-- مثال على نص منسق: اسم المستأجر - تاريخ الاستحقاق - المبلغ المتبقي --}}
            {{ $installment->contract->tenant->first_name ?? 'N/A' }} {{ $installment->contract->tenant->last_name ?? '' }} |
            تاريخ: {{ $installment->due_date }} |
            المتبقي: {{ number_format($installment->amount_due + $installment->late_fee - $installment->amount_paid, 2) }} ريال
        </option>
    @endforeach
</select>
                @error('rent_installment_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">المبلغ المدفوع</label>
                <input type="number" step="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="payment_date" class="form-label">تاريخ الدفع</label>
                <input type="date" name="payment_date" id="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                 @error('payment_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">طريقة الدفع</label>
                <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                    <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>نقد (كاش)</option>
                    <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>حوالة بنكية</option>
                    <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                    <option value="Online Payment" {{ old('payment_method') == 'Online Payment' ? 'selected' : '' }}>دفع إلكتروني</option>
                    <option value="Check" {{ old('payment_method') == 'Check' ? 'selected' : '' }}>شيك</option>
                </select>
                @error('payment_method')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="transaction_reference" class="form-label">الرقم المرجعي (اختياري)</label>
                <input type="text" name="transaction_reference" id="transaction_reference" class="form-control" value="{{ old('transaction_reference') }}">
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">ملاحظات (اختياري)</label>
                <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">تسجيل الدفعة</button>
        </form>
    </div>
</body>
</html>