@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto mt-12 bg-white rounded-2xl shadow-lg p-8 text-[18px]">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 space-y-4 md:space-y-0">
        <h2 class="text-4xl font-extrabold text-gray-900">
            قائمة العقود <span class="text-gray-500 text-2xl">({{ $contracts->count() }})</span>
        </h2>

        <div class="flex items-center space-x-4 rtl:space-x-reverse">
            <div class="relative">
                <button id="actionsBtn" data-dropdown-toggle="actionsDropdown" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    إجراءات
                    <svg class="w-5 h-5 ms-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="actionsDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-20 text-[18px]">
                    <ul class="py-2 text-gray-700">
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">تصدير CSV</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100">طباعة</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 text-red-600">حذف الكل</a></li>
                    </ul>
                </div>
            </div>

            <div class="relative">
                <input id="searchInput" type="text" placeholder="ابحث في العقود..." class="pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-80" />
                <svg class="w-6 h-6 absolute top-2.5 start-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-300 shadow-sm">
        <table class="w-full text-right text-gray-700 text-[18px]">
            <thead class="bg-indigo-600 text-white uppercase text-[19px] tracking-wide select-none">
                <tr>
                    <th class="p-6"><input id="selectAll" type="checkbox" class="w-5 h-5 text-indigo-600 rounded" /></th>
                    <th class="p-6">المستأجر</th>
                    <th class="p-6">العقار</th>
                    <th class="p-6">الوحدة</th>
                    <th class="p-6">البداية</th>
                    <th class="p-6">النهاية</th>
                    <th class="p-6">الحالة</th>
                    <th class="p-6">المرجع</th>
                    <th class="p-6">الإجراءات</th>
                </tr>
            </thead>
           <tbody class="divide-y divide-gray-200">
    @forelse($contracts as $contract)
        @php
            $today = \Carbon\Carbon::now();
            $endDate = \Carbon\Carbon::parse($contract->end_date);
            $daysRemaining = $today->diffInDays($endDate, false);
            $statusLabel = match ($contract->status) {
                'active' => 'نشط',
                'terminated' => 'منتهي',
                'cancelled' => 'ملغي',
                'draft' => 'مسودة',
                default => 'غير معروف'
            };
            $statusColor = match ($contract->status) {
                'active' => 'text-green-700 bg-green-100',
                'terminated' => 'text-gray-600 bg-gray-100',
                'cancelled' => 'text-red-700 bg-red-100',
                'draft' => 'text-yellow-600 bg-yellow-100',
                default => 'text-gray-400 bg-gray-100'
            };
        @endphp
        <tr class="hover:bg-indigo-50 transition cursor-pointer">
            <td class="p-10 text-center"><input type="checkbox" class="w-5 h-5 text-indigo-600 rounded contract-checkbox" /></td>
            <td class="p-10 whitespace-nowrap font-semibold">{{ $contract->tenant->first_name ?? '---' }} {{ $contract->tenant->last_name ?? '' }}</td>
            <td class="p-10 whitespace-nowrap">{{ $contract->property->name ?? 'عقار #' . $contract->property->property_id }}</td>
            <td class="p-10 whitespace-nowrap">وحدة رقم {{ $contract->unit->unit_number ?? $contract->unit_id }}</td>
            <td class="p-10 text-center">{{ $contract->start_date }}</td>
            <td class="p-10 text-center">
                <div class="{{ $daysRemaining <= 3 && $daysRemaining >= 0 ? 'text-red-700 font-semibold' : '' }}">
                    {{ $contract->end_date }}
                    @if ($daysRemaining <= 3 && $daysRemaining >= 0)
                        <span class="block text-sm text-red-600">⚠️ ينتهي خلال {{ $daysRemaining }} يوم</span>
                    @endif
                </div>
            </td>
            <td class="p-10 text-center">
                <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </td>
            <td class="p-10 text-center font-mono">{{ $contract->reference_number }}</td>
            <td class="p-10 text-center space-x-2 rtl:space-x-reverse">
                <a href="{{ route('contracts.show', $contract) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">عرض</a>
                <button data-modal-target="editContractModal" data-modal-show="editContractModal" class="text-yellow-600 hover:text-yellow-800 font-semibold">تعديل</button>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="p-20 text-center text-gray-400 text-2xl select-none">لا توجد عقود حالياً</td>
        </tr>
    @endforelse
</tbody>

        </table>
    </div>

    <!-- تعديل العقد -->
    <div id="editContractModal" tabindex="-1" aria-hidden="true" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 hidden z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full p-8">
            <h3 class="text-3xl font-extrabold mb-6">تعديل العقد</h3>
            <form>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="text" placeholder="اسم المستأجر" class="border border-gray-300 rounded-md p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <input type="text" placeholder="اسم العقار" class="border border-gray-300 rounded-md p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <input type="text" placeholder="رقم الوحدة" class="border border-gray-300 rounded-md p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <input type="date" placeholder="تاريخ البداية" class="border border-gray-300 rounded-md p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <input type="date" placeholder="تاريخ النهاية" class="border border-gray-300 rounded-md p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    <select class="border border-gray-300 rounded-md p-4 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">اختر الحالة</option>
                        <option value="active">نشط</option>
                        <option value="terminated">منتهي</option>
                        <option value="cancelled">ملغي</option>
                        <option value="draft">مسودة</option>
                    </select>
                </div>
                <div class="mt-6 flex justify-end space-x-4 rtl:space-x-reverse">
                    <button type="button" id="closeModalBtn" class="px-6 py-3 rounded-md border border-gray-300 hover:bg-gray-100">إلغاء</button>
                    <button type="submit" class="px-6 py-3 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('actionsBtn').addEventListener('click', function() {
        const dropdown = document.getElementById('actionsDropdown');
        dropdown.classList.toggle('hidden');
    });

    document.querySelectorAll('[data-modal-show="editContractModal"]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('editContractModal').classList.remove('hidden');
        });
    });
    document.getElementById('closeModalBtn').addEventListener('click', () => {
        document.getElementById('editContractModal').classList.add('hidden');
    });

    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.contract-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
@endsection
