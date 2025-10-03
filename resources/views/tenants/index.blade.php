@extends('layouts.app')

@section('title', 'قائمة المستأجرين')

@section('content')
<div class="container-fluid mt-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">قائمة المستأجرين</h1>
        <a href="{{ route('tenants.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm inline-flex items-center">
            <i class="bi bi-plus-circle me-1"></i>
            إضافة مستأجر جديد
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm" role="alert">
            <strong class="font-bold">نجاح!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

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
                <table id="tenants-table" class="table table-hover align-middle min-w-full divide-y divide-gray-200" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم الكامل</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">بيانات الاتصال</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الهوية</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدخل الشهري</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">صورة الهوية</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        @forelse($tenants as $tenant)
                        <tr class="hover:bg-gray-50" data-search="{{ $tenant->first_name }} {{ $tenant->father_name }} {{ $tenant->last_name }} {{ $tenant->phone }} {{ $tenant->email ?? '' }} {{ $tenant->id_type }} {{ $tenant->monthly_income }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $tenant->first_name }} {{ $tenant->father_name }} {{ $tenant->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="text-gray-700">{{ $tenant->phone }}</div>
                                <small class="text-gray-500">{{ $tenant->email ?? '-' }}</small>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                @php
                                    $idTypes = ['national_card' => 'بطاقة وطنية', 'passport' => 'جواز سفر', 'residence' => 'إقامة'];
                                @endphp
                                {{ $idTypes[$tenant->id_type] ?? 'غير معروف' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $tenant->monthly_income ? number_format($tenant->monthly_income, 0) . ' ريال' : '-' }}
                            </td>
          

                         <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
@if($tenant->image_path)
    <img src="{{ asset('uploads/' . $tenant->image_path) }}" 
         alt="هوية {{ $tenant->first_name }}"
         class="w-16 h-10 object-cover rounded-md mx-auto cursor-pointer transition-transform hover:scale-110"
         onclick="showImageModal('{{ asset('uploads/' . $tenant->image_path) }}')">
@else
    <span class="text-xs text-gray-400">لا توجد</span>
@endif

</td>

                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-x-2">
                                    <a href="{{ route('tenants.show', $tenant->id) }}" class="relative group text-gray-500 hover:text-blue-600" title="عرض">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="relative group text-gray-500 hover:text-yellow-600" title="تعديل">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="relative group" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستأجر؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-3-5.197M15 21a9 9 0 00-3-1.95m-3 1.95a9 9 0 00-3-1.95m0 0A2.25 2.25 0 015.25 15.75v-1.5a2.25 2.25 0 012.25-2.25" />
                                    </svg>
                                    <h4 class="mt-3 text-lg text-gray-700">لا يوجد مستأجرون حالياً.</h4>
                                    <p class="text-sm text-gray-500">ابدأ بإضافة مستأجر جديد لعرض بياناته هنا.</p>
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

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 hidden overflow-y-auto">
        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-full flex flex-col">
            <div class="overflow-auto">
                <img id="modalImage" src="" alt="عرض صورة الهوية" class="w-full h-auto rounded-t-lg">
            </div>
            <div class="p-4 text-center flex-shrink-0">
                <button onclick="closeImageModal()" class="px-6 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300">
                    إغلاق
                </button>
            </div>
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
    let currentPage = 1;
    let entriesPerPage = 10;
    
    function getAllRows() {
        return Array.from(document.querySelectorAll('#tableBody tr:not([colspan])'));
    }
    
    function paginate(array, page_size, page_number) {
        return array.slice((page_number - 1) * page_size, page_number * page_size);
    }
    
    function pageCount(array, page_size) {
        return Math.ceil(array.length / page_size);
    }
    
    function renderTable() {
        const allRows = getAllRows();
        if (allRows.length === 0) {
             const existingPagination = document.getElementById('pagination');
             if (existingPagination) {
                 existingPagination.innerHTML = '';
             }
            return;
        }

        const currentRows = paginate(allRows, entriesPerPage, currentPage);
        
        allRows.forEach(row => row.style.display = 'none');
        currentRows.forEach(row => row.style.display = '');
    }
    
    function renderPagination() {
        const allRows = getAllRows();
        if (allRows.length === 0) return;

        const pagination = document.getElementById('pagination');
        const totalPages = pageCount(allRows, entriesPerPage);
        
        pagination.innerHTML = '';
        
        const prevLi = document.createElement('li');
        prevLi.className = 'page-item';
        if (currentPage === 1) prevLi.classList.add('disabled');
        prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">السابق</a>`;
        pagination.appendChild(prevLi);
        
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>`;
            pagination.appendChild(li);
        }
        
        const nextLi = document.createElement('li');
        nextLi.className = 'page-item';
        if (currentPage === totalPages) nextLi.classList.add('disabled');
        nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">التالي</a>`;
        pagination.appendChild(nextLi);
    }
    
    function changePage(page) {
        if (page < 1) page = 1;
        const allRows = getAllRows();
        if (allRows.length === 0) return;

        const totalPages = pageCount(allRows, entriesPerPage);
        if (page > totalPages) page = totalPages;
        
        currentPage = page;
        renderTable();
        renderPagination();
    }
    
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
        
        currentPage = 1;
        renderPagination();
        renderTable();
    }
    
    function showImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
    }
    
    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        renderTable();
        renderPagination();
        
        document.getElementById('searchInput').addEventListener('input', searchTable);
        document.getElementById('entriesSelect').addEventListener('change', function() {
            entriesPerPage = parseInt(this.value);
            currentPage = 1;
            renderTable();
            renderPagination();
        });
        
        // Close modal when clicking outside
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
    });
    
    window.changePage = changePage;
  </script>
@endpush