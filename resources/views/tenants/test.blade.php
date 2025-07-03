<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>عقود الإيجار</title>
  <style>
    body {
      font-family: 'Tahoma', sans-serif;
      direction: rtl;
      background-color: #f8f9fa;
      padding: 40px;
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 12px 15px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #007bff;
      color: #fff;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .badge {
      padding: 4px 8px;
      border-radius: 6px;
      color: white;
      font-size: 0.85em;
    }

    .active     { background-color: #28a745; }
    .terminated { background-color: #dc3545; }
    .cancelled  { background-color: #6c757d; }
    .draft      { background-color: #ffc107; color: #000; }
  </style>
</head>
<body>

<h2>قائمة العقود</h2>

<table>
  <thead>
    <tr>
      <th>رقم العقد</th>
      <th>الوحدة</th>
      <th>المستأجر</th>
      <th>العقار</th>
      <th>بداية العقد</th>
      <th>نهاية العقد</th>
      <th>نوع الإيجار</th>
      <th>الحالة</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>CTR-001</td>
      <td>U-12</td>
      <td>أحمد علي</td>
      <td>الروضة 5</td>
      <td>2025-01-01</td>
      <td>2025-12-31</td>
      <td>شهري</td>
      <td><span class="badge active">نشط</span></td>
    </tr>
    <tr>
      <td>CTR-002</td>
      <td>U-09</td>
      <td>فهد السالم</td>
      <td>الياسمين 3</td>
      <td>2024-06-15</td>
      <td>2025-06-14</td>
      <td>سنوي</td>
      <td><span class="badge terminated">منتهي</span></td>
    </tr>
    <tr>
      <td>CTR-003</td>
      <td>U-21</td>
      <td>سارة حسن</td>
      <td>الفيحاء 1</td>
      <td>2024-11-01</td>
      <td>2025-04-30</td>
      <td>شهري</td>
      <td><span class="badge cancelled">ملغى</span></td>
    </tr>
    <tr>
      <td>CTR-004</td>
      <td>U-05</td>
      <td>ياسر العتيبي</td>
      <td>الخالدية 2</td>
      <td>2025-03-01</td>
      <td>2025-03-31</td>
      <td>يومي</td>
      <td><span class="badge active">نشط</span></td>
    </tr>
    <tr>
      <td>CTR-005</td>
      <td>U-30</td>
      <td>نورة البابطين</td>
      <td>السلام 6</td>
      <td>2025-02-01</td>
      <td>2025-07-31</td>
      <td>أسبوعي</td>
      <td><span class="badge draft">مسودة</span></td>
    </tr>
    <tr>
      <td>CTR-006</td>
      <td>U-17</td>
      <td>خالد المانع</td>
      <td>الصفا 8</td>
      <td>2024-09-01</td>
      <td>2025-08-31</td>
      <td>سنوي</td>
      <td><span class="badge active">نشط</span></td>
    </tr>
    <tr>
      <td>CTR-007</td>
      <td>U-03</td>
      <td>منى الشمراني</td>
      <td>النهضة 4</td>
      <td>2025-06-01</td>
      <td>2025-06-30</td>
      <td>شهري</td>
      <td><span class="badge terminated">منتهي</span></td>
    </tr>
    <tr>
      <td>CTR-008</td>
      <td>U-15</td>
      <td>علي الزهراني</td>
      <td>المروج 2</td>
      <td>2025-01-10</td>
      <td>2025-12-10</td>
      <td>شهري</td>
      <td><span class="badge active">نشط</span></td>
    </tr>
  </tbody>
</table>

</body>
</html>
