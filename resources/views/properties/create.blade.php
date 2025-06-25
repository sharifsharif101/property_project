@extends('layouts.app')

@section('title', 'عرض العقارات')

@section('content')
    <section class="content">


        <div
            class="w-4/5 mx-auto p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <div class="text-center my-6">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">
                    إضافة عقار جديد
                </h1>
            </div>

            <div class="row">
                <div class="col-xs-12">


                    <div class="box-body">
                        <form action="{{ route('properties.store') }}" method="POST" class="w-4/5 mx-auto">
                            @csrf

                            <!-- اسم العقار -->
                            <div>
                                <label for="name" class="block text-gray-700 font-medium mb-1">اسم العقار</label>
                                <input type="text" name="name" id="name"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ old('name') }}" required>

                            </div>

                            <!-- العنوان -->
                            <div>
                                <label for="address" class="block text-gray-700 font-medium mb-1">العنوان</label>
                                <input type="text" name="address" id="address"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ old('address') }}" required>

                            </div>

                            <!-- النوع -->
                            <div>
                                <label for="type" class="block text-gray-700 font-medium mb-1">نوع العقار</label>
                                <select name="type" id="type"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="">اختر النوع</option>
                                    <option value="big_house" {{ old('type') == 'big_house' ? 'selected' : '' }}>بيت كبير
                                    </option>
                                    <option value="building" {{ old('type') == 'building' ? 'selected' : '' }}>عمارة
                                    </option>
                                    <option value="villa" {{ old('type') == 'villa' ? 'selected' : '' }}>فلة</option>
                                </select>

                            </div>

                            <!-- الحالة -->
                            <div>
                                <label for="status" class="block text-gray-700 font-medium mb-1">الحالة</label>
                                <select name="status" id="status"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="">اختر الحالة</option>
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>متاح
                                    </option>
                                    <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>مؤجر</option>
                                    <option value="under_maintenance"
                                        {{ old('status') == 'under_maintenance' ? 'selected' : '' }}>تحت
                                        الصيانة</option>
                                </select>
                            </div>

                            <!-- زر الحفظ -->
                            <div class="text-center pt-4">


                                <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                    style="color: white !important;">حفظ العقار</button>

                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>

    </section>
@endsection
