{{-- This page extends the main layout --}}
@extends('layouts.app')

{{-- Define the page title --}}
@section('title', 'لوحة التحكم الرئيسية')

{{-- Define the page content --}}
@section('content')
<div class="container-fluid mt-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">سجل أقساط الإيجارات</h1>
        <a href="{{ route('payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm inline-flex items-center">
            <i class="bi bi-plus-circle me-1"></i> <!-- تأكد أن أيقونة Bootstrap تعمل مع Tailwind -->
             إضافة دفعة جديدة
        </a>
    </div>
 

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex justify-between items-center mb-3">
                <div class="search-box">
                    <input type="text" id="searchInput" class="form-control w-full md:w-64 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="البحث في الجدول...">
                </div>
                <div class="flex items-center space-x-2 space-x-reverse">
                    <select id="entriesSelect" class="form-select w-auto px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <label class="text-gray-700">إدخالات لكل صفحة</label>
                </div>
            </div>

            <div class="table-responsive">
                <table id="installments-table" class="table table-hover align-middle min-w-full divide-y divide-gray-200" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستأجر</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العقار / الوحدة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرقم المرجعي للعقد</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الاستحقاق</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ المستحق</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ المدفوع</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-red-600">المبلغ المتبقي</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($installments as $installment)
                        <tr class="hover:bg-gray-50" data-search="{{ $installment->id + 10000 }} {{ $installment->contract->tenant->first_name ?? '' }} {{ $installment->contract->tenant->last_name ?? '' }} {{ $installment->contract->tenant->phone ?? '' }} {{ $installment->contract->property->name ?? '' }} {{ $installment->contract->unit->unit_number ?? '' }} {{ $installment->contract->reference_number ?? '' }} {{ $installment->due_date }} {{ $installment->status }} {{ number_format($installment->amount_due + $installment->late_fee, 2) }} {{ number_format($installment->amount_paid, 2) }} {{ number_format(($installment->amount_due + $installment->late_fee) - $installment->amount_paid, 2) }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $installment->id + 10000 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $installment->contract->tenant->first_name ?? 'غير متوفر' }} {{ $installment->contract->tenant->last_name ?? '' }}</div>
                                <small class="text-gray-500" dir="ltr">{{ $installment->contract->tenant->phone ?? '' }}</small>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $installment->contract->property->name ?? 'غير متوفر' }}</div>
                                <small class="text-gray-500">وحدة رقم: {{ $installment->contract->unit->unit_number ?? 'N/A' }}</small>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">{{ $installment->contract->reference_number ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($installment->due_date)->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @php
                                    $statusConfig = [
                                        'Paid'           => ['class' => 'bg-green-100 text-green-800 border border-green-200', 'text' => 'مدفوع بالكامل', 'icon' => 'bi-check-circle-fill'],
                                        'Partially Paid' => ['class' => 'bg-yellow-100 text-yellow-800 border border-yellow-200', 'text' => 'مدفوع جزئياً', 'icon' => 'bi-pie-chart-fill'],
                                        'Overdue'        => ['class' => 'bg-red-100 text-red-800 border border-red-200', 'text' => 'متأخر', 'icon' => 'bi-exclamation-triangle-fill'],
                                        'Due'            => ['class' => 'bg-blue-100 text-blue-800 border border-blue-200', 'text' => 'مستحق', 'icon' => 'bi-hourglass-split'],
                                        'Cancelled'      => ['class' => 'bg-gray-100 text-gray-800 border border-gray-200', 'text' => 'ملغي', 'icon' => 'bi-x-circle-fill'],
                                    ];
                                    $config = $statusConfig[$installment->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => $installment->status, 'icon' => 'bi-question-circle'];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['class'] }}">
                                    <i class="bi {{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-left">{{ number_format($installment->amount_due + $installment->late_fee, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-left">{{ number_format($installment->amount_paid, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600 text-left">
                                {{ number_format(($installment->amount_due + $installment->late_fee) - $installment->amount_paid, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if($installment->status == 'Paid')
                                    <span class="text-green-600" title="تم الدفع بالكامل"><i class="bi bi-check2-all fs-5"></i></span>
                                @elseif($installment->status == 'Cancelled')
                                    <span class="text-gray-500" title="هذا القسط ملغي"><i class="bi bi-slash-circle fs-5"></i></span>
                                @else
                                    <a href="{{ route('payments.create', ['installment_id' => $installment->id]) }}" 
                                       class="btn btn-sm {{ $installment->status == 'Partially Paid' ? 'btn-warning bg-yellow-500 hover:bg-yellow-600' : 'btn-success bg-green-500 hover:bg-green-600' }} text-white py-1 px-2 text-sm rounded"
                                       title="تسجيل دفعة لهذا القسط">
                                        <i class="bi bi-cash-coin"></i>
                                        {{ $installment->status == 'Partially Paid' ? 'إكمال الدفع' : 'دفع الآن' }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-journal-x text-gray-400 text-5xl"></i>
                                    <h4 class="mt-3 text-lg text-gray-700">لا توجد أقساط لعرضها حالياً.</h4>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <nav aria-label="Navigation" class="mt-4">
                <ul class="pagination flex items-center justify-center space-x-2 space-x-reverse" id="pagination">
                    <!-- الأرقام ستُضاف هنا -->
                </ul>
            </nav>
        </div>
    </div>
</div>

@endsection

{{-- You can push page-specific scripts if needed --}}
@push('scripts')
  <style>
    /* تضمين تعريفات Tailwind الأساسية إذا لم تكن متوفرة */
    /* html { color-scheme: light; } */
    .pagination {
        list-style: none;
        padding: 0;
        margin: 1.5rem 0;
    }
    .pagination li {
        margin: 0 0.2rem;
    }
    .pagination a, .pagination span {
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        text-decoration: none;
        color: #3b82f6;
        display: block;
    }
    .pagination .active a {
        background-color: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }
    .pagination .disabled a {
        color: #9ca3af;
        cursor: not-allowed;
    }
  </style>
  <script>
    // إعداد الحالة
    let currentPage = 1;
    let entriesPerPage = 10; // يجب أن تكون let لجعلها قابلة للتعديل
    
    // دالة لجلب جميع الصفوف (استبعاد الصف الفارغ)
    function getAllRows() {
        return Array.from(document.querySelectorAll('#tableBody tr:not([colspan])'));
    }
    
    // دالة لتقسيم الصفوف إلى صفحات
    function paginate(array, page_size, page_number) {
        return array.slice((page_number - 1) * page_size, page_number * page_size);
    }
    
    // دالة لحساب عدد الصفحات
    function pageCount(array, page_size) {
        return Math.ceil(array.length / page_size);
    }
    
    // دالة لرسم الجدول
    function renderTable() {
        const allRows = getAllRows();
        if (allRows.length === 0) {
             // لا حاجة للتعامل مع pagination إذا كان الجدول فارغًا وله صف "لا توجد أقساط"
             const existingPagination = document.getElementById('pagination');
             if (existingPagination) {
                 existingPagination.innerHTML = '';
             }
            return;
        }

        const currentRows = paginate(allRows, entriesPerPage, currentPage);
        
        // إخفاء جميع الصفوف
        allRows.forEach(row => row.style.display = 'none');
        
        // عرض الصفوف الحالية فقط
        currentRows.forEach(row => row.style.display = '');
    }
    
    // دالة لرسم الترقيم
    function renderPagination() {
        const allRows = getAllRows();
        if (allRows.length === 0) return; // لا تظهر الترقيم إذا لم توجد بيانات

        const pagination = document.getElementById('pagination');
        const totalPages = pageCount(allRows, entriesPerPage);
        
        pagination.innerHTML = '';
        
        // زر "السابق"
        const prevLi = document.createElement('li');
        prevLi.className = 'page-item';
        if (currentPage === 1) prevLi.classList.add('disabled');
        prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">السابق</a>`;
        pagination.appendChild(prevLi);
        
        // أرقام الصفحات
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>`;
            pagination.appendChild(li);
        }
        
        // زر "التالي"
        const nextLi = document.createElement('li');
        nextLi.className = 'page-item';
        if (currentPage === totalPages) nextLi.classList.add('disabled');
        nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">التالي</a>`;
        pagination.appendChild(nextLi);
    }
    
    // دالة لتغيير الصفحة
    function changePage(page) {
        if (page < 1) page = 1;
        const allRows = getAllRows();
        if (allRows.length === 0) return; // لا تفعل شيئًا إذا لم توجد بيانات

        const totalPages = pageCount(allRows, entriesPerPage);
        if (page > totalPages) page = totalPages;
        
        currentPage = page;
        renderTable();
        renderPagination();
    }
    
    // دالة للبحث
    function searchTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const allRows = getAllRows();
        
        allRows.forEach(row => {
            const searchData = row.getAttribute('data-search').toLowerCase();
            if (searchData.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        // إعادة ترقيم الصفحات بعد البحث
        currentPage = 1;
        renderPagination();
        // بعد التصفية، قد نحتاج إلى إعادة رسم الجدول لعرض الصفحات الحالية فقط
        renderTable();
    }
    
    // تهيئة عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        renderTable();
        renderPagination(); // استدعاء الترقيم بعد تحميل الصفحة
        
        // إضافة مستمعي الأحداث
        document.getElementById('searchInput').addEventListener('input', searchTable);
        document.getElementById('entriesSelect').addEventListener('change', function() {
            entriesPerPage = parseInt(this.value); // تحديث القيمة
            currentPage = 1; // العودة للصفحة الأولى
            renderTable();
            renderPagination();
        });
    });
    
    // دالة للوصول إلى الدوال من HTML
    window.changePage = changePage;
  </script>
@endpush