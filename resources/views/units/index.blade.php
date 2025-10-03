@extends('layouts.app')

@section('title', 'قائمة الوحدات')

@section('content')
<div class="container-fluid mt-4">
   <div class="flex items-center mb-4">
    <h1 class="text-2xl font-bold text-gray-800 flex-grow">قائمة الوحدات</h1>
    <a href="{{ route('units.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm inline-flex items-center">
        <i class="bi bi-plus-circle me-1"></i>
        إضافة وحدة جديدة
    </a>
</div>


    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm" role="alert">
            <strong class="font-bold">نجاح!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-md shadow-sm" role="alert">
            <strong class="font-bold">خطأ!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @php
        $statusConfig = [
            'available' => ['label' => 'متاحة', 'style' => 'bg-green-100 text-green-800 border border-green-200', 'icon' => 'bi-check-circle-fill'],
            'reserved' => ['label' => 'محجوزة', 'style' => 'bg-cyan-100 text-cyan-800 border border-cyan-200', 'icon' => 'bi-lock-fill'],
            'rented' => ['label' => 'مؤجرة', 'style' => 'bg-blue-100 text-blue-800 border border-blue-200', 'icon' => 'bi-key-fill'],
            'under_maintenance' => ['label' => 'تحت الصيانة', 'style' => 'bg-yellow-100 text-yellow-800 border border-yellow-200', 'icon' => 'bi-tools'],
            'disabled' => ['label' => 'معطلة', 'style' => 'bg-gray-200 text-gray-800 border border-gray-300', 'icon' => 'bi-x-circle-fill'],
        ];
    @endphp

    <div class="card shadow-sm border-0">
        <div class="card-body">
          
<div class="flex flex-col md:flex-row justify-between items-center mb-3 gap-3">
    <!-- صندوق البحث -->
    <div class="search-box flex-grow md:flex-grow-0 w-full md:w-64">
        <input type="text" id="searchInput" class="form-control w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="البحث في الجدول...">
    </div>

    <!-- خيارات الإدخالات -->
    <div class="flex items-center gap-2">
        <select id="entriesSelect" class="form-select w-auto px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <label class="text-gray-700 whitespace-nowrap">إدخالات لكل صفحة</label>
    </div>
</div>

            <div class="table-responsive">
                <table id="units-table" class="table table-hover align-middle min-w-full divide-y divide-gray-200" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العقار</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الوحدة</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">أضيف بتاريخ</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        @forelse ($units as $unit)
                        <tr class="hover:bg-gray-50" data-search="{{ $loop->iteration }} {{ $unit->property->name ?? 'غير معروف' }} {{ $unit->unit_number }} {{ $unit->status }} {{ $unit->created_at->format('Y-m-d') }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $unit->property->name ?? 'غير معروف' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $unit->unit_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @php 
                                    $config = $statusConfig[$unit->status] ?? ['label' => $unit->status, 'style' => 'bg-gray-100 text-gray-800 border border-gray-200', 'icon' => 'bi-question-circle'];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['style'] }}">
                                    <i class="bi {{ $config['icon'] }} me-1"></i>{{ $config['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600" dir="ltr">{{ $unit->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-x-3">
                                    <a href="{{ route('units.show', $unit->id) }}" class="text-gray-500 hover:text-blue-600" title="عرض">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    @if($unit->contracts()->whereIn('status', ['active', 'draft'])->exists())
                                        <span class="text-gray-300 cursor-not-allowed" title="لا يمكن تعديل وحدة مرتبطة بعقد نشط">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @else
                                        <a href="{{ route('units.edit', $unit) }}" class="text-gray-500 hover:text-yellow-600" title="تعديل">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="bi bi-journal-x text-gray-400 text-5xl"></i>
                                    <h4 class="mt-3 text-lg text-gray-700">لا توجد وحدات لعرضها حالياً.</h4>
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

 @push('scripts')
  <style>
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
    // حالة الصفحة الحالية وعدد الإدخالات الافتراضي
    let currentPage = 1;
    let entriesPerPage = 10;

    // جلب كل صفوف الجدول القابلة للبحث (نستثني صف "لا نتائج" إن وُجِد)
    function getAllRows() {
        return Array.from(document.querySelectorAll('#tableBody tr:not([data-no-search])'));
    }

    // تهيئة dataset.visible لكل الصفوف (تُستخدم لتحديد ما إذا كان الصف مرئياً وفق البحث)
    function initVisibility() {
        const allRows = getAllRows();
        allRows.forEach(row => row.dataset.visible = '1');
    }

    // تجزئة المصفوفة لصفحة محددة
    function paginate(array, page_size, page_number) {
        return array.slice((page_number - 1) * page_size, page_number * page_size);
    }

    // تحسب عدد الصفحات بناءً على الصفوف المرئية
    function pageCountVisible(visibleCount, page_size) {
        return Math.max(1, Math.ceil(visibleCount / page_size));
    }

    // عرض محتوى الجدول بالاعتماد على النتائج المرئية الحاليّة والصفحة
    function renderTable() {
        const allRows = getAllRows();
        const visibleRows = allRows.filter(row => row.dataset.visible !== '0');

        // صف لا نتائج (سننشئ/نستخدم صف موجود داخل tbody مع data-no-search="1")
        let noResultsRow = document.getElementById('noResultsRow');
        if (!noResultsRow) {
            noResultsRow = document.createElement('tr');
            noResultsRow.id = 'noResultsRow';
            noResultsRow.setAttribute('data-no-search', '1');
            noResultsRow.innerHTML = `
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="bi bi-journal-x text-gray-400 text-5xl"></i>
                        <h4 class="mt-3 text-lg text-gray-700">لا توجد نتائج تطابق البحث.</h4>
                    </div>
                </td>
            `;
            noResultsRow.style.display = 'none';
            document.querySelector('#tableBody').appendChild(noResultsRow);
        }

        // إخفاء كل الصفوف أولاً
        allRows.forEach(row => row.style.display = 'none');

        if (visibleRows.length === 0) {
            // عرض صف "لا نتائج" وإخفاء pagination
            noResultsRow.style.display = '';
            document.getElementById('pagination').innerHTML = '';
            return;
        } else {
            noResultsRow.style.display = 'none';
        }

        // حساب عدد الصفحات والتأكد من أن currentPage ضمن الحدود
        const totalPages = pageCountVisible(visibleRows.length, entriesPerPage);
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        // عرض الصفوف في الصفحة الحالية
        const rowsToShow = paginate(visibleRows, entriesPerPage, currentPage);
        rowsToShow.forEach(row => row.style.display = '');
    }

    // رسم عناصر الترقيم (pagination)
    function renderPagination() {
        const allRows = getAllRows();
        const visibleRows = allRows.filter(row => row.dataset.visible !== '0');
        const pagination = document.getElementById('pagination');

        // إذا لا يوجد صفوف مرئية، نعيد رسم فارغ
        if (visibleRows.length === 0) {
            pagination.innerHTML = '';
            return;
        }

        const totalPages = pageCountVisible(visibleRows.length, entriesPerPage);
        pagination.innerHTML = '';

        // زر السابق
        const prevLi = document.createElement('li');
        prevLi.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
        prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">السابق</a>`;
        pagination.appendChild(prevLi);

        // أرقام الصفحات — لإنقاص الفوضى نعرض حتى 7 أرقام مع تقديم/تأخير عند الحاجة
        // لكن للكود البسيط الآن سنعرض كل الأرقام (يمكن تحسينه لاحقًا إن رغبت)
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>`;
            pagination.appendChild(li);
        }

        // زر التالي
        const nextLi = document.createElement('li');
        nextLi.className = 'page-item' + (currentPage === totalPages ? ' disabled' : '');
        nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">التالي</a>`;
        pagination.appendChild(nextLi);
    }

    // تغيير الصفحة (مع ضبط الحدود)
    function changePage(page) {
        const allRows = getAllRows();
        const visibleRows = allRows.filter(row => row.dataset.visible !== '0');

        if (visibleRows.length === 0) {
            currentPage = 1;
            renderTable();
            renderPagination();
            return;
        }

        const totalPages = pageCountVisible(visibleRows.length, entriesPerPage);
        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;

        currentPage = page;
        renderTable();
        renderPagination();
    }

    // دالة البحث: تضبط dataset.visible ثم تعيد رسم الجدول والpagination
    function searchTable() {
        const searchTerm = document.getElementById('searchInput').value.trim().toLowerCase();
        const allRows = getAllRows();

        if (searchTerm === '') {
            // إذا خالٍ، نجعل كل الصفوف مرئية
            allRows.forEach(row => row.dataset.visible = '1');
        } else {
            allRows.forEach(row => {
                const searchData = (row.getAttribute('data-search') || '').toLowerCase();
                row.dataset.visible = searchData.includes(searchTerm) ? '1' : '0';
            });
        }

        currentPage = 1;
        renderTable();
        renderPagination();
    }

    // تهيئة عند تحميل DOM
    document.addEventListener('DOMContentLoaded', function() {
        initVisibility();

        // عرض أول مرة
        renderTable();
        renderPagination();

        // أحداث
        document.getElementById('searchInput').addEventListener('input', searchTable);

        document.getElementById('entriesSelect').addEventListener('change', function() {
            entriesPerPage = parseInt(this.value) || 10;
            currentPage = 1;
            renderTable();
            renderPagination();
        });
    });

    // كشف الدوال للعالم الخارجي (مطلوب لأننا نستدعيها من HTML inline)
    window.changePage = changePage;
    window.searchTable = searchTable;
  </script>
@endpush
