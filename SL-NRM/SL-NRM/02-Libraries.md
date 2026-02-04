# RECOMMENDED LIBRARIES & INSTALLATION
Dưới đây là các thư viện cần cài thêm để phục vụ đúng nhu cầu SpaceLink (Excel, API, Payments).

## 1. Laravel Sanctum (Authentication)
**Trạng thái**: Đã cài (thông qua `install:api`), nhưng check `composer.json` chưa thấy dòng require.
**Mục đích**: Quản lý Token (Login, Logout) cho ReactJS.
**Cài đặt**:
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```
*Lưu ý: Nếu bạn đã chạy `php artisan install:api`, nó đã tự cài và cấu hình migration.*

## 2. Maatwebsite Excel (Export Excel)
**Mục đích**: Đáp ứng yêu cầu chức năng #20 "Xuất danh sách đơn hàng ra Excel".
**Relevance**: Đây là thư viện chuẩn nhất của Laravel cho việc này.
**Cài đặt**:
```bash
composer require maatwebsite/excel
```
**Cách dùng**:
Tạo file Export: `php artisan make:export OrdersExport --model=Order`
Trong Controller: `return Excel::download(new OrdersExport, 'orders.xlsx');`

## 3. L5-Swagger (API Documentation)
**Mục đích**: Tạo trang document tự động (như Swagger UI) để team Frontend (React) nhìn vào biết gọi API thế nào (params, response).
**Relevance**: Rất quan trọng khi làm việc team.
**Cài đặt**:
```bash
composer require darkaonline/l5-swagger
```
**Alternative**: `khulnasoft/scramble` (Dễ cài hơn, tự động 100% không cần viết annotation). Ưu tiên Scramble nếu muốn nhanh.

## 4. Guzzle (HTTP Client)
**Mục đích**: Gọi API bên thứ 3 (VNPAY, MOMO, GHN/GHTK).
**Trạng thái**: Thường có sẵn trong Laravel framework dependencies, nhưng nếu thiếu thì cài.
**Cách dùng**: Dùng qua `Illuminate\Support\Facades\Http`.
```php
$response = Http::post('https://sandbox.vnpayment.vn/...', [...]);
```

## 5. Laravel Telescope (Debug - Optional)
**Mục đích**: Xem log request, query SQL, mail đã gửi. Rất tiện khi debug API xem tại sao FE gọi lỗi mà không cần `dd()`.
**Cài đặt**:
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

---
## TỔNG KẾT
Với yêu cầu của bạn (API + DB Migrations), stack sau là chuẩn:
1.  **Core**: Eloquent, Resources, Requests, Events (Notification).
2.  **Auth**: Sanctum.
3.  **Features**: Maatwebsite Excel (Export).
4.  **Payment**: HTTP Client (Core).
5.  **Docs**: Scramble/Swagger.
