
@extends('layouts.app')

@section('title', 'قائمة العقارات')

@section('content')
    {{-- Breadcrumbs (Optional, assuming you have it configured) --}}
    <div class="mb-6">
        {{-- Breadcrumbs::render('properties.index') --}}
        <p class="text-sm text-gray-500">لوحة التحكم / العقارات</p>
    </div>

    {{-- Session Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-800 rounded-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-800 rounded-lg shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-lg shadow-md">
        <!-- Card Header -->
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">قائمة العقارات</h2>
            <a href="{{ route('properties.create') }}" 
               class="inline-flex items-center gap-2 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                <span>إضافة عقار جديد</span>
            </a>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <div class="overflow-x-auto">
                {{-- The id "example2" is kept for your DataTables JS to work --}}
                <table id="example2" class="w-full text-sm text-right">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-medium">#</th>
                            <th scope="col" class="px-6 py-3 font-medium">اسم العقار</th>
                            <th scope="col" class="px-6 py-3 font-medium">العنوان</th>
                            <th scope="col" class="px-6 py-3 font-medium">النوع</th>
                            <th scope="col" class="px-6 py-3 font-medium text-center">الحالة</th>
                            <th scope="col" class="px-6 py-3 font-medium">أضيف بتاريخ</th>
                            <th scope="col" class="px-6 py-3 font-medium text-center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($properties as $property)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $property->property_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                <a href="{{ route('properties.show', $property->property_id) }}" class="text-blue-600 hover:underline">{{ $property->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $property->address }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                @switch($property->type)
                                    @case('big_house') بيت كبير @break
                                    @case('building') عمارة @break
                                    @case('villa') فيلا @break
                                    @default -
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $badgeStyles = ['available' => 'bg-green-100 text-green-800', 'rented' => 'bg-yellow-100 text-yellow-800', 'under_maintenance' => 'bg-orange-100 text-orange-800'];
                                    $statusText = ['available' => 'متاح', 'rented' => 'مؤجر', 'under_maintenance' => 'تحت الصيانة'];
                                    $style = $badgeStyles[$property->status] ?? 'bg-gray-100 text-gray-800';
                                    $label = $statusText[$property->status] ?? '-';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $style }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600" dir="ltr">{{ $property->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-x-3">
                                    <a href="{{ route('properties.show', $property->property_id) }}" class="text-gray-500 hover:text-blue-600" title="عرض">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                    </a>
                                    <a href="{{ route('properties.edit', $property->property_id) }}" class="text-gray-500 hover:text-yellow-600" title="تعديل">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                                    </a>
                                    <form action="{{ route('properties.destroy', $property->property_id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600" title="حذف">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="flex flex-col items-center justify-center text-center py-16 text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    <h4 class="mt-4 text-xl font-semibold">لا توجد عقارات لعرضها حالياً.</h4>
                                    <p class="mt-2">يمكنك البدء عن طريق <a href="{{ route('properties.create') }}" class="text-blue-600 hover:underline">إضافة عقار جديد</a>.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection