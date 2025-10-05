@extends('layouts.app')

@section('title', 'إضافة عقار جديد')

@section('content')
 
    <div class="bg-gray-50 min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-2xl mx-auto">

            {{-- Session Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="bg-green-50 border-r-4 border-green-400 text-green-800 p-4 rounded-lg shadow-md mb-6"
                    role="alert">
                    <div class="flex items-center">
                        <div class="ml-3">
                            <p class="font-bold text-lg">نجاح!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    class="bg-red-50 border-r-4 border-red-400 text-red-800 p-4 rounded-lg shadow-md mb-6"
                    role="alert">
                    <div class="flex items-center">
                        <div class="ml-3">
                            <p class="font-bold text-lg">خطأ!</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="bg-white p-8 sm:p-10 border border-gray-200 rounded-2xl shadow-lg">
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800">
                        إضافة عقار جديد
                    </h1>
                    <p class="text-gray-500 mt-2">أدخل تفاصيل العقار لإضافته إلى النظام.</p>
                </div>

                <form action="{{ route('properties.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Property Name --}}
                    <div class="relative">
                        <input type="text" name="name" id="name"
                            class="block w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 peer"
                            value="{{ old('name') }}" required placeholder=" ">
                        <label for="name"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-50 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                            اسم العقار
                        </label>
                    </div>

                    {{-- Address --}}
                    <div class="relative">
                        <input type="text" name="address" id="address"
                            class="block w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 peer"
                            value="{{ old('address') }}" required placeholder=" ">
                        <label for="address"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-gray-50 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                            العنوان
                        </label>
                    </div>

                    {{-- Property Type --}}
                    <div class="relative">
                        <select name="type" id="type"
                            class="block w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                            required>
                            <option value="" disabled selected>اختر نوع العقار</option>
                            <option value="big_house" {{ old('type') == 'big_house' ? 'selected' : '' }}>بيت كبير</option>
                            <option value="building" {{ old('type') == 'building' ? 'selected' : '' }}>عمارة</option>
                            <option value="villa" {{ old('type') == 'villa' ? 'selected' : '' }}>فلة</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <div class="flex justify-between space-x-2 rtl:space-x-reverse" id="status-boxes">
                            <div class="status-box flex-1 text-center p-4 border border-gray-300 cursor-pointer bg-gray-50" data-value="available">
                                متاح
                            </div>
                            <div class="status-box flex-1 text-center p-4 border border-gray-300 cursor-pointer bg-gray-50" data-value="rented">
                                مؤجر
                            </div>
                            <div class="status-box flex-1 text-center p-4 border border-gray-300 cursor-pointer bg-gray-50" data-value="under_maintenance">
                                تحت الصيانة
                            </div>
                        </div>
                        <input type="hidden" name="status" id="status-hidden" value="{{ old('status', 'available') }}">
                    </div>

                    {{-- Submit Button --}}
                    <div class="text-center pt-4">
                        <button type="submit"
                            class="w-full sm:w-auto text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-base px-8 py-3 transition-all duration-300 ease-in-out shadow-md hover:shadow-lg focus:outline-none">
                            حفظ العقار
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

<style>
    .status-box {
        transition: all 0.3s ease;
        border-radius: 0; /* Remove border-radius */
    }
    .status-box.selected {
        background-color: #3b82f6; /* blue-600 */
        color: white;
        border-color: #2563eb; /* blue-700 */
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusBoxes = document.querySelectorAll('.status-box');
        const statusInput = document.getElementById('status-hidden');

        // Function to update selection
        function updateSelection(selectedValue) {
            statusBoxes.forEach(box => {
                if (box.dataset.value === selectedValue) {
                    box.classList.add('selected');
                } else {
                    box.classList.remove('selected');
                }
            });
            statusInput.value = selectedValue;
        }

        // Set initial state from hidden input's value
        updateSelection(statusInput.value);

        // Add click event listeners
        statusBoxes.forEach(box => {
            box.addEventListener('click', function () {
                const selectedValue = this.dataset.value;
                updateSelection(selectedValue);
            });
        });
    });
</script>

@endsection
