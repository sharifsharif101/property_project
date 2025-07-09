<?php

namespace App\Http\Controllers;

 use App\Models\RentInstallment; // <-- أهم نموذج
use Carbon\Carbon; // <-- للتعامل مع التواريخ
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
  // داخل DashboardController.php

 // في app/Http/Controllers/DashboardController.php

public function index()
{
    // --- بطاقات المؤشرات ---
    $activeContractsCount = \App\Models\Contract::where('status', 'active')->count();
    $totalOverdue = \App\Models\RentInstallment::where('status', 'Overdue')->sum(DB::raw('amount_due + late_fee - amount_paid'));
    $upcomingRent = \App\Models\RentInstallment::where('status', 'Due')
                        ->whereBetween('due_date', [now(), now()->addDays(30)])
                        ->sum('amount_due');
    $availableUnitsCount = \App\Models\Unit::where('status', 'available')->count();

    // --- أشرطة التقدم ---
    $totalUnits = \App\Models\Unit::count();
    $rentedUnits = $totalUnits - $availableUnitsCount;
    $occupancyRate = ($totalUnits > 0) ? ($rentedUnits / $totalUnits) * 100 : 0;
    
    // --- قوائم الإجراءات العاجلة ---
    $upcomingExpiries = \App\Models\Contract::where('status', 'active')
                        ->whereBetween('end_date', [now(), now()->addDays(60)])
                        ->orderBy('end_date', 'asc')
                        ->limit(5)
                        ->get();

    $recentPayments = \App\Models\Payment::with('contract.tenant')
                        ->latest()
                        ->limit(5)
                        ->get();


    return view('dashboard.index', compact(
        'activeContractsCount',
        'totalOverdue',
        'upcomingRent',
        'availableUnitsCount',
        'occupancyRate',
        'upcomingExpiries',
        'recentPayments'
    ));
}
}
