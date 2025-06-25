<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PropertySeeder extends Seeder
{
  public function run(): void
    {
        // قائمة بأسماء عقارات عربية
        $names = [
            'فيلا الأحلام', 'قصر السلام', 'شقة النخيل', 'عمارة السعادة', 
            'بيت الزهور', 'فيلا الرقي', 'شقة الأفق', 'عمارة الضياء', 
            'بيت الأمان', 'فيلا الفخامة'
        ];

        // قائمة بعناوين عربية
        $addresses = [
            'شارع التحلية، الرياض', 'حي النرجس، جدة', 'شارع الملك فهد، الدمام', 
            'حي المروج، المدينة المنورة', 'شارع الأمير سلطان، مكة', 
            'حي الروضة، الخبر', 'شارع الخليج، القطيف', 'حي الصفا، الطائف', 
            'شارع الستين، القصيم', 'حي البديعة، أبها'
        ];

        // تعبئة الجدول بـ 10 سجلات
        for ($i = 0; $i < 10; $i++) {
            DB::table('properties')->insert([
                'name' => $names[$i], // اسم العقار (من القائمة)
                'address' => $addresses[$i], // العنوان (من القائمة)
                'type' => $this->getRandomType(), // النوع (اختيار عشوائي)
                'status' => $this->getRandomStatus(), // الحالة (اختيار عشوائي)
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * اختيار نوع العقار بشكل عشوائي
     */
    private function getRandomType(): string
    {
        return ['big_house', 'building', 'villa'][array_rand(['big_house', 'building', 'villa'])];
    }

 
    private function getRandomStatus(): string
    {
        return ['available', 'rented', 'under_maintenance'][array_rand(['available', 'rented', 'under_maintenance'])];
    }
}
