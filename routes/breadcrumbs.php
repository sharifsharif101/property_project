<?php
use App\Models\Property;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as Trail;

// الرئيسية (الرئيسية توجه للعقارات مباشرة حسب راوت properties.index)
Breadcrumbs::for('home', function (Trail $trail) {
    $trail->push('الرئيسية', route('home'));
});

// الرئيسية > العقارات (صفحة القائمة)
Breadcrumbs::for('properties.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('العقارات', route('properties.index'));
});

// الرئيسية > العقارات > إضافة عقار
Breadcrumbs::for('properties.create', function (Trail $trail) {
    $trail->parent('properties.index');
    $trail->push('إضافة عقار', route('properties.create'));
});

// الرئيسية > العقارات > تفاصيل العقار
Breadcrumbs::for('properties.show', function (Trail $trail, Property $property) {
    $trail->parent('properties.index');
    $trail->push('تفاصيل العقار', route('properties.show', $property));
});

// الرئيسية > العقارات > تعديل العقار
Breadcrumbs::for('properties.edit', function (Trail $trail, Property $property) {
    $trail->parent('properties.index');
    $trail->push('تعديل العقار', route('properties.edit', $property));
});