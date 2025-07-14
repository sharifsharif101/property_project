<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockMobile
{
   
    public function handle(Request $request, Closure $next)
    {
        // نفس كود التحقق الذي استخدمناه سابقاً
        $userAgent = $request->header('User-Agent');

        // البحث عن كلمات تدل على أنه هاتف (مع استثناء الأجهزة اللوحية)
        if (preg_match('/Mobi|Android|iPhone|iPod/i', $userAgent) && !preg_match('/Tablet|iPad/i', $userAgent)) {
            
            // إذا كان هاتفاً، أرجع صفحة التحذير مع كود 403 (Forbidden)
            // استخدام view أفضل من كتابة HTML مباشرة هنا
            return response(view('mobile-warning'), 403);
        }

        // إذا لم يكن هاتفاً، اسمح للطلب بالمرور إلى وجهته التالية (الراوت)
        return $next($request);
    }
}