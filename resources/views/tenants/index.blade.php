 @extends('layouts.app')

@section('title', 'قائمة المستأجرين')

@section('content')
{{-- We will wrap the entire content in an Alpine.js component for the modal functionality --}}
<div x-data="{ showModal: false, modalImage: '' }" @keydown.escape.window="showModal = false">
    
    {{-- Session Messages --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
            class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-md shadow-sm" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-md">
        <!-- Card Header -->
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-xl font-bold text-gray-800">قائمة المستأجرين</h2>
            <a href="{{ route('tenants.create') }}" 
               class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 11a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1v-1z" /></svg>
                <span>إضافة مستأجر جديد</span>
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <div class="overflow-x-auto">
                <table id="tenantsTable" class="w-full text-sm text-right">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-4 font-medium">الاسم الكامل</th>
                            <th class="p-4 font-medium">بيانات الاتصال</th>
                            <th class="p-4 font-medium">نوع الهوية</th>
                            <th class="p-4 font-medium">الدخل الشهري</th>
                            <th class="p-4 font-medium text-center">صورة الهوية</th>
                            <th class="p-4 font-medium text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tenants as $tenant)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $tenant->first_name }} {{ $tenant->father_name }} {{ $tenant->last_name }}
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                <div class="text-gray-700">{{ $tenant->phone }}</div>
                                <div class="text-xs text-gray-500">{{ $tenant->email ?? '-' }}</div>
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap">
                                @php
                                    $idTypes = ['national_card' => 'بطاقة وطنية', 'passport' => 'جواز سفر', 'residence' => 'إقامة'];
                                @endphp
                                {{ $idTypes[$tenant->id_type] ?? 'غير معروف' }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap">
                                {{ $tenant->monthly_income ? number_format($tenant->monthly_income, 0) . ' ريال' : '-' }}
                            </td>
                            <td class="p-4 text-center">
                                @if($tenant->image_path)
                                    <img @click="modalImage = '{{ asset('storage/' . $tenant->image_path) }}'; showModal = true"
                                         src="{{ asset('storage/' . $tenant->image_path) }}" 
                                         alt="هوية {{ $tenant->first_name }}"
                                         class="w-16 h-10 object-cover rounded-md mx-auto cursor-pointer transition-transform hover:scale-110">
                                @else
                                    <span class="text-xs text-gray-400">لا توجد</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-x-2">
                                    {{-- View Button --}}
                                    <a href="{{ route('tenants.show', $tenant->id) }}" class="relative group">
                                        <div class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full transition-colors hover:bg-blue-200">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                        </div>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded-md px-2 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity whitespace-nowrap">عرض</span>
                                    </a>
                                    {{-- Edit Button --}}
                                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="relative group">
                                        <div class="w-8 h-8 flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full transition-colors hover:bg-yellow-200">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                        </div>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded-md px-2 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity whitespace-nowrap">تعديل</span>
                                    </a>
                                    {{-- Delete Button --}}
                                    <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستأجر؟');" class="relative group">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-full transition-colors hover:bg-red-200">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </button>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs rounded-md px-2 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity whitespace-nowrap">حذف</span>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-16 text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-3-5.197M15 21a9 9 0 00-3-1.95m-3 1.95a9 9 0 00-3-1.95m0 0A2.25 2.25 0 015.25 15.75v-1.5a2.25 2.25 0 012.25-2.25" /></svg>
                                    <p class="mt-4 font-semibold">لا يوجد مستأجرون حالياً.</p>
                                    <p class="text-sm">ابدأ بإضافة مستأجر جديد لعرض بياناته هنا.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <!-- Modal Overlay -->
        <div @click="showModal = false" class="fixed inset-0 bg-black/70"></div>
        <!-- Modal Content -->
        <div @click.away="showModal = false"
             class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <img :src="modalImage" alt="عرض صورة الهوية" class="w-full h-auto rounded-t-lg">
            <div class="p-4 text-center">
                <button @click="showModal = false"
                        class="px-6 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300">
                    إغلاق
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tenantsTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/2.0.3/i18n/ar.json"
                },
                "columnDefs": [
                    { "orderable": false, "targets": [4, 5] } // Disable sorting on image and actions
                ]
            });
        });
    </script>
@endpush