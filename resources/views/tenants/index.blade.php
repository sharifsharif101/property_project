@extends('layouts.app')

@section('title', 'قائمة المستأجرين')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2 sm:mb-0">
            قائمة المستأجرين
        </h1>
        <a href="{{ route('tenants.create') }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform transform hover:scale-105">
            <svg class="w-5 h-5 -ms-1 me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            إضافة مستأجر جديد
        </a>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div id="success-alert" 
             class="mb-4 p-4 bg-green-50 border-s-4 border-green-500 text-green-800 rounded-lg shadow-sm transition-opacity duration-500 ease-out" 
             role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 me-2 text-green-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <strong class="font-bold me-2">نجاح!</strong>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-6">
            
            {{-- Filters and Search --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
                <div class="relative w-full md:w-72">
                    <label for="searchInput" class="sr-only">بحث</label>
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput" 
                           class="block w-full ps-10 p-2.5 border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" 
                           placeholder="البحث عن مستأجر...">
                </div>
                <div class="flex items-center gap-2 w-full md:w-auto">
                    <label for="entriesSelect" class="text-sm text-gray-600 whitespace-nowrap">عرض:</label>
                    <select id="entriesSelect" 
                            class="block w-full md:w-auto border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm p-2.5 transition">
                        <option value="10">10 صفوف</option>
                        <option value="25">25 صفًا</option>
                        <option value="50">50 صفًا</option>
                        <option value="100">100 صف</option>
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table id="tenants-table" class="min-w-full divide-y divide-gray-200">
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
                        <tr class="hover:bg-gray-50 transition-colors duration-150" 
                            data-search="{{ $tenant->first_name }} {{ $tenant->father_name }} {{ $tenant->last_name }} {{ $tenant->phone }} {{ $tenant->email ?? '' }} {{ $tenant->id_type }} {{ $tenant->monthly_income }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $tenant->first_name }} {{ $tenant->father_name }} {{ $tenant->last_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="text-gray-800">{{ $tenant->phone }}</div>
                                <div class="text-gray-500 text-xs">{{ $tenant->email ?? '-' }}</div>
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
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($tenant->image_path)
                                    <img src="{{ asset('uploads/' . $tenant->image_path) }}" 
                                         alt="هوية {{ $tenant->first_name }}"
                                         class="w-16 h-10 object-cover rounded-md mx-auto cursor-pointer transition-transform hover:scale-110 hover:shadow-lg"
                                         onclick="showImageModal('{{ asset('uploads/' . $tenant->image_path) }}')">
                                @else
                                    <span class="text-xs text-gray-400">لا توجد</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-x-3">
                                    <a href="{{ route('tenants.show', $tenant->id) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="عرض">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                    </a>
                                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="text-gray-400 hover:text-yellow-600 transition-colors" title="تعديل">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                    </a>
                                    <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستأجر؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="حذف">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.962a3.75 3.75 0 015.25 0m-5.25 0a3.75 3.75 0 00-5.25 0M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" /></svg>
                                    <h4 class="mt-4 text-lg font-semibold text-gray-700">لا يوجد مستأجرون حالياً</h4>
                                    <p class="mt-1 text-sm">ابدأ بإضافة مستأجر جديد لعرض بياناته هنا.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <nav aria-label="Pagination" class="mt-6 flex justify-center">
                <ul class="pagination flex items-center -space-x-px h-9 text-sm" id="pagination">
                    {{-- Pagination links will be injected by JavaScript --}}
                </ul>
            </nav>
        </div>
    </div>

    {{-- Image Modal --}}
    <div id="imageModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-75 hidden">
        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col">
            <div class="overflow-auto p-2">
                <img id="modalImage" src="" alt="عرض صورة الهوية" class="w-full h-auto rounded-md">
            </div>
            <div class="p-4 text-center border-t border-gray-200">
                <button onclick="closeImageModal()" 
                        class="px-6 py-2 bg-gray-100 text-gray-800 font-semibold rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-colors">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    /* Custom styles for pagination to match the new design */
    .pagination li a, .pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin: 0;
        width: 2.25rem; /* h-9 */
        height: 2.25rem; /* w-9 */
        font-size: 0.875rem; /* text-sm */
        border: 1px solid #e5e7eb; /* border-gray-200 */
        color: #374151; /* text-gray-700 */
        background-color: #ffffff; /* bg-white */
        transition: all 0.2s ease-in-out;
    }
    .pagination li:first-child a {
        border-top-left-radius: 0.5rem; /* rounded-l-lg */
        border-bottom-left-radius: 0.5rem;
    }
    .pagination li:last-child a {
        border-top-right-radius: 0.5rem; /* rounded-r-lg */
        border-bottom-right-radius: 0.5rem;
    }
    .pagination li a:hover {
        background-color: #f3f4f6; /* hover:bg-gray-100 */
        color: #111827; /* hover:text-gray-900 */
    }
    .pagination li.active a {
        background-color: #3b82f6; /* bg-blue-600 */
        color: #ffffff; /* text-white */
        border-color: #3b82f6; /* border-blue-600 */
        font-weight: 600;
    }
    .pagination li.disabled a {
        color: #9ca3af; /* text-gray-400 */
        background-color: #f9fafb; /* bg-gray-50 */
        cursor: not-allowed;
    }
</style>
<script>
    // The existing JavaScript remains unchanged as it handles functionality.
    let currentPage = 1;
    let entriesPerPage = 10;

    function getAllRows() {
        return Array.from(document.querySelectorAll('#tableBody tr:not([data-no-search])'));
    }

    function initVisibility() {
        const allRows = getAllRows();
        allRows.forEach(row => row.dataset.visible = '1');
    }

    function paginate(array, page_size, page_number) {
        return array.slice((page_number - 1) * page_size, page_number * page_size);
    }

    function pageCountVisible(visibleCount, page_size) {
        return Math.max(1, Math.ceil(visibleCount / page_size));
    }

    function renderTable() {
        const allRows = getAllRows();
        const visibleRows = allRows.filter(row => row.dataset.visible !== '0');

        let noResultsRow = document.querySelector('tr[data-no-search="1"]');
        if (!noResultsRow) {
            noResultsRow = document.createElement('tr');
            noResultsRow.setAttribute('data-no-search', '1');
            noResultsRow.innerHTML = `
                <td colspan="6" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                        <svg class="w-16 h-16 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        <h4 class="mt-4 text-lg font-semibold text-gray-700">لا توجد نتائج تطابق البحث</h4>
                        <p class="mt-1 text-sm">حاول استخدام كلمات بحث مختلفة.</p>
                    </div>
                </td>
            `;
            noResultsRow.style.display = 'none';
            document.querySelector('#tableBody').appendChild(noResultsRow);
        }

        allRows.forEach(row => row.style.display = 'none');

        if (visibleRows.length === 0) {
            const emptyRow = document.querySelector('tr[data-empty-table]');
            if (!emptyRow) {
                noResultsRow.style.display = '';
            }
            document.getElementById('pagination').innerHTML = '';
            return;
        } else {
            noResultsRow.style.display = 'none';
        }

        const totalPages = pageCountVisible(visibleRows.length, entriesPerPage);
        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const rowsToShow = paginate(visibleRows, entriesPerPage, currentPage);
        rowsToShow.forEach(row => row.style.display = '');
    }

    function renderPagination() {
        const allRows = getAllRows();
        const visibleRows = allRows.filter(row => row.dataset.visible !== '0');
        const pagination = document.getElementById('pagination');

        if (visibleRows.length <= entriesPerPage) {
            pagination.innerHTML = '';
            return;
        }

        const totalPages = pageCountVisible(visibleRows.length, entriesPerPage);
        pagination.innerHTML = '';

        const prevLi = document.createElement('li');
        prevLi.className = (currentPage === 1 ? 'disabled' : '');
        prevLi.innerHTML = `<a href="#" onclick="changePage(${currentPage - 1}); return false;">السابق</a>`;
        pagination.appendChild(prevLi);

        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement('li');
            li.className = `${i === currentPage ? 'active' : ''}`;
            li.innerHTML = `<a href="#" onclick="changePage(${i}); return false;">${i}</a>`;
            pagination.appendChild(li);
        }

        const nextLi = document.createElement('li');
        nextLi.className = (currentPage === totalPages ? 'disabled' : '');
        nextLi.innerHTML = `<a href="#" onclick="changePage(${currentPage + 1}); return false;">التالي</a>`;
        pagination.appendChild(nextLi);
    }

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

    function searchTable() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const allRows = getAllRows();

        if (searchTerm === '') {
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

    function showImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const emptyRow = document.querySelector('#tableBody td[colspan="6"]');
        if (emptyRow) {
            emptyRow.parentElement.setAttribute('data-empty-table', '1');
        }

        initVisibility();
        renderTable();
        renderPagination();
        
        document.getElementById('searchInput').addEventListener('input', searchTable);
        document.getElementById('entriesSelect').addEventListener('change', function() {
            entriesPerPage = parseInt(this.value);
            currentPage = 1;
            searchTable();
        });
        
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });

        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 500);
            }, 5000);
        }
    });
    
    window.changePage = changePage;
    window.searchTable = searchTable;
    window.showImageModal = showImageModal;
    window.closeImageModal = closeImageModal;
</script>
@endpush
