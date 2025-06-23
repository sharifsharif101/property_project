; سكربت AutoHotkey لفتح WhatsApp Web والضغط على زر

Run, chrome.exe --new-window https://web.whatsapp.com/
; انتظر 7 ثواني لإعطاء فرصة للموقع حتى يُحمّل
Sleep, 7000

; حرّك الماوس إلى الموضع الظاهر في الصورة
; Screen: 664, 285
MouseMove, 664, 285, 10

; انقر بزر الماوس الأيسر
Click
