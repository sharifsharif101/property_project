<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User; // <-- لا تنس استدعاء موديل User

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // إعادة تعيين الكاش الخاص بالأدوار والصلاحيات
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- إنشاء الصلاحيات (Permissions) ---
        // صلاحيات العقود
        Permission::create(['name' => 'view contracts']);
        Permission::create(['name' => 'create contracts']);
        Permission::create(['name' => 'edit contracts']);
        Permission::create(['name' => 'delete contracts']);

        // صلاحيات المدفوعات
        Permission::create(['name' => 'view payments']);
        Permission::create(['name' => 'create payments']);
        Permission::create(['name' => 'delete payments']); // إلغاء دفعة

        // صلاحيات المستخدمين (للمدراء فقط)
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);


        // --- إنشاء الأدوار (Roles) وتعيين الصلاحيات لها ---

        // دور مدخل البيانات (أقل الصلاحيات)
        $dataEntryRole = Role::create(['name' => 'Data Entry']);
        $dataEntryRole->givePermissionTo([
            'view contracts',
            'create contracts',
            'view payments',
            'create payments',
        ]);

        // دور المحاسب
        $accountantRole = Role::create(['name' => 'Accountant']);
        $accountantRole->givePermissionTo([
            'view contracts',
            'view payments',
            'create payments',
            'delete payments', // المحاسب يمكنه إلغاء الدفعات
        ]);

        // دور مدير العقارات
        $managerRole = Role::create(['name' => 'Property Manager']);
        $managerRole->givePermissionTo([
            'view contracts',
            'create contracts',
            'edit contracts',
            'delete contracts',
            'view payments',
            'create payments',
            'delete payments',
            // المدير يمكنه إدارة المستخدمين ذوي الصلاحيات الأقل
            'view users',
            'create users',
            'edit users',
        ]);

        // دور المدير الخارق (يمتلك كل الصلاحيات)
        // لا داعي لتعيين الصلاحيات يدوياً، الحزمة توفر طريقة خاصة له
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        // لا نعطيه صلاحيات هنا، سنتحقق من اسمه مباشرة في الكود

        
        // --- (اختياري ولكن موصى به) إنشاء مستخدم تجريبي لكل دور ---
        
        // إنشاء مستخدم تجريبي لـ Super Admin
        $superAdminUser = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@app.com',
            'password' => bcrypt('123'), // كلمة السر هي 'password'
        ]);
        $superAdminUser->assignRole($superAdminRole);

        // إنشاء مستخدم تجريبي لـ مدير العقارات
        $managerUser = User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@app.com',
            'password' => bcrypt('123'),
        ]);
        $managerUser->assignRole($managerRole);

        // إنشاء مستخدم تجريبي لـ المحاسب
        $accountantUser = User::factory()->create([
            'name' => 'Accountant User',
            'email' => 'accountant@app.com',
            'password' => bcrypt('123'),
        ]);
        $accountantUser->assignRole($accountantRole);
    }
}